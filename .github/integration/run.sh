#!/usr/bin/env bash
# Integration test of the Netis stack: creates a project with create-netis and checks that it installs,
# migrates, passes PHPStan, builds its assets and answers HTTP requests.
#
# MODE=released  packages from Packagist, as users get them
# MODE=main      main branches of all drago-ex packages (cloned, or taken from PACKAGES_DIR), the check before a release
# VARIANT=base        create-netis without permissions
# VARIANT=permission  create-netis --permission (project-preset-permission)
#
# The database is given by DB_HOST, DB_PORT, DB_USER, DB_PASSWORD and DB_NAME.
set -euo pipefail

MODE=${MODE:-released}
VARIANT=${VARIANT:-permission}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_USER=${DB_USER:-super}
DB_PASSWORD=${DB_PASSWORD:-pass}
DB_NAME=${DB_NAME:-app}
HTTP_PORT=${HTTP_PORT:-8080}

NETIS=$(cd "$(dirname "$0")/../.." && pwd)
WORK=${WORK:-$NETIS/temp/integration-$MODE}
APP=$WORK/app

# Path for native programs; Git Bash on Windows converts arguments, but not environment variables.
native() {
	if command -v cygpath > /dev/null; then cygpath -m "$1"; else echo "$1"; fi
}

step() {
	echo
	echo "=== $*"
}

# Fails unless the report in .drago-project/ ends with result "ok".
check_report() {
	php -r '
		$report = json_decode((string) @file_get_contents($argv[1]), true);
		if (!is_array($report)) {
			fwrite(STDERR, "Report $argv[1] is missing.\n");
			exit(1);
		}
		echo "{$report["tool"]}: {$report["result"]} " . json_encode($report["stats"]) . "\n";
		foreach ($report["entries"] as $entry) {
			if (in_array($entry["action"], ["error", "failed", "conflict"], true)) {
				echo "  {$entry["action"]} {$entry["package"]}: " . ($entry["message"] ?? "") . "\n";
			}
		}
		exit($report["result"] === "ok" ? 0 : 1);
	' "$APP/.drago-project/$1.json"
}

rm -rf "$WORK"
mkdir -p "$WORK"

if [ "$MODE" = main ]; then
	step "Packages from the main branches"
	PACKAGES=${PACKAGES_DIR:-$WORK/packages}
	if [ -z "${PACKAGES_DIR:-}" ]; then
		mkdir -p "$PACKAGES"
		repos=$(curl -fsSL "https://api.github.com/orgs/drago-ex/repos?per_page=100&type=public" \
			| php -r 'foreach (json_decode(stream_get_contents(STDIN), true) as $r) if (!$r["archived"]) echo $r["name"], "\n";')
		for repo in $repos; do
			git clone -q --filter=blob:none "https://github.com/drago-ex/$repo.git" "$PACKAGES/$repo"
		done
	fi

	# Path repositories with every drago-ex package; version <major>.99.0 satisfies the constraints between the
	# packages, so the main branches are installed instead of the releases.
	mkdir -p "$WORK/composer-home"
	export COMPOSER_HOME=$(native "$WORK/composer-home")
	php "$NETIS/.github/integration/composer-config.php" "$(native "$PACKAGES")" "$COMPOSER_HOME/config.json"
fi

step "create-netis ($MODE, $VARIANT)"
if [[ $VARIANT == permission ]]; then
	(cd "$WORK" && php "$NETIS/bin/create-netis" app --permission)
else
	(cd "$WORK" && php "$NETIS/bin/create-netis" app)
fi
check_report install
check_report commands

step "Migrations"
mkdir -p "$APP/local"
cat > "$APP/local/db.neon" <<NEON
extensions:
	dibi: Dibi\\Bridges\\Nette\\DibiExtension3

dibi:
	host: $DB_HOST
	port: $DB_PORT
	username: $DB_USER
	password: $DB_PASSWORD
	database: $DB_NAME
	driver: mysqli
	lazy: true
NEON
(cd "$APP" && php vendor/bin/migration db:migrate --packages)
check_report migration

step "PHPStan"
(cd "$APP" && php vendor/bin/phpstan analyse --no-progress --memory-limit=1G)

step "Assets"
(cd "$APP" && npm install --no-audit --no-fund && npm run npm-loader && npm run vite:build)

step "HTTP"
start_server() {
	# the upload limits of docker/php.ini (project-docker): with the PHP default of 2 MB, Nette Forms warn about
	# the 8 MB image limit of the blog and the gallery, which Tracy turns into an error page in debug mode
	php -d upload_max_filesize=64M -d post_max_size=64M -S "127.0.0.1:$HTTP_PORT" -t "$APP/www" "$APP/www/index.php" >> "$WORK/server.log" 2>&1 &
	server=$!
}
start_server
trap 'kill $server 2>/dev/null || true' EXIT
# The first request also warms up the DI container, so the server gets time to start and answer. The PHP
# built-in server on GitHub sometimes crashes with a segmentation fault on that first request; it is restarted.
status=000
for attempt in $(seq 1 30); do
	status=$(curl -s -o "$WORK/index.html" -w '%{http_code}' --max-time 30 "http://127.0.0.1:$HTTP_PORT/" || true)
	[ "$status" != 000 ] && break
	if ! kill -0 "$server" 2> /dev/null; then
		echo "The PHP server stopped, starting it again."
		start_server
	fi
	sleep 1
done
echo "GET / -> $status"
if [ "$status" != 200 ] || ! grep -q '<title>' "$WORK/index.html"; then
	cat "$WORK/server.log"
	exit 1
fi

step "Application after install"
# The same state the web installer leaves: website settings, an administrator and the install lock.
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=integration-test-password
sql() {
	php -r '
		[, $host, $port, $user, $password, $name, $query] = $argv;
		$db = new mysqli($host, $user, $password, $name, (int) $port);
		$db->query($query) or exit($db->error . "\n");
	' "$DB_HOST" "$DB_PORT" "$DB_USER" "$DB_PASSWORD" "$DB_NAME" "$1"
}
# the first column of the first row of a query
sql_value() {
	php -r '
		[, $host, $port, $user, $password, $name, $query] = $argv;
		$db = new mysqli($host, $user, $password, $name, (int) $port);
		$result = $db->query($query) or exit($db->error . "\n");
		echo $result->fetch_row()[0] ?? "";
	' "$DB_HOST" "$DB_PORT" "$DB_USER" "$DB_PASSWORD" "$DB_NAME" "$1"
}
sql "INSERT INTO settings (name, value) VALUES ('website', 'Netis integration'), ('description', 'Integration test'), ('installed', '1')"
(cd "$APP" && php vendor/bin/create-user admin "$ADMIN_EMAIL" "$ADMIN_PASSWORD")
# with permissions the web installer gives the administrator the admin role
if [[ $VARIANT == permission ]]; then
	sql "INSERT INTO users_roles (user_id, role_id) SELECT id, 1 FROM users WHERE email = '$ADMIN_EMAIL'"
fi
(cd "$APP" && php -r 'require "vendor/autoload.php"; Install\InstallLock::create(getcwd(), "integration test");')

BASE=http://127.0.0.1:$HTTP_PORT
JAR=$WORK/cookies.txt
# GET or POST with the session cookie; prints the status and the redirect target, keeps the page in $WORK/page.html.
request() {
	curl -s -b "$JAR" -c "$JAR" -o "$WORK/page.html" -w '%{http_code} %{redirect_url}' --max-time 30 \
		-H "Origin: $BASE" -H 'Sec-Fetch-Site: same-origin' "$@" || true
}
expect() {
	local expected=$1 label=$2 result=$3
	echo "$label -> $result"
	if [ "${result%% *}" != "$expected" ]; then
		cat "$WORK/server.log"
		exit 1
	fi
	# in debug mode an error page of Tracy may come with status 200
	if grep -q 'id="tracy-bs"' "$WORK/page.html" 2> /dev/null; then
		echo "Tracy error page: $(grep -o '<title>[^<]*' "$WORK/page.html" | head -1 | cut -c8-)"
		exit 1
	fi
}

expect 200 'GET / (front page)' "$(request -L "$BASE/")"
grep -q 'Netis integration' "$WORK/page.html" || { echo 'The front page does not show the website name.'; exit 1; }
result=$(request "$BASE/admin/")
expect 302 'GET /admin/ (signed out)' "$result"
[[ "$result" == *"/sign/in"* ]] || { echo 'The administration does not redirect to the sign-in page.'; exit 1; }
expect 200 'GET /sign/in' "$(request -L "$BASE/sign/in")"

# The sign-in form as rendered: its action, hidden fields and the names of the email and password inputs.
form=$(php -r '
	$dom = new DOMDocument;
	@$dom->loadHTML((string) file_get_contents($argv[1]));
	foreach ($dom->getElementsByTagName("form") as $form) {
		$fields = [];
		foreach ($form->getElementsByTagName("input") as $input) {
			$type = $input->getAttribute("type");
			$value = match ($type) { "email" => $argv[2], "password" => $argv[3], "hidden", "submit" => $input->getAttribute("value"), default => null };
			if ($value !== null && $input->getAttribute("name") !== "") {
				$fields[] = rawurlencode($input->getAttribute("name")) . "=" . rawurlencode($value);
			}
		}
		if (str_contains(implode("&", $fields), rawurlencode($argv[3]))) {
			echo $form->getAttribute("action"), "\n", implode("&", $fields);
			exit;
		}
	}
	exit(1);
' "$WORK/page.html" "$ADMIN_EMAIL" "$ADMIN_PASSWORD") || { echo 'The sign-in form was not found.'; exit 1; }
action=$(echo "$form" | head -1 | sed 's/&amp;/\&/g')
# A failed sign-in renders the form again with 200; a successful one redirects to the administration.
result=$(request --data "$(echo "$form" | tail -1)" "$BASE$action")
expect 303 'POST sign-in' "$result"
[[ "$result" == *"/admin"* ]] || { echo 'The sign-in does not lead to the administration.'; exit 1; }
expect 200 'GET /admin/ (signed in)' "$(request -L "$BASE/admin/")"
expect 200 'GET /admin/gallery/' "$(request -L "$BASE/admin/gallery/")"
if [[ $VARIANT == permission ]]; then
	expect 200 'GET /admin/permission/roles' "$(request -L "$BASE/admin/permission/roles")"
else
	expect 200 'GET /admin/settings/ (no permissions installed)' "$(request -L "$BASE/admin/settings/")"
fi

step "Saving and editing"
# Entities and form values have camelCase properties, the columns are snake_case: drago-ex/database maps them.
# Each form is filled from the rendered page and posted, the columns are checked in the database, and the edit
# signal (AJAX) must fill the form again.
form_tool() {
	php "$NETIS/.github/integration/form.php" "$@"
}
# posts the form of $WORK/page.html that has the field $1, with the values $2...
submit() {
	local form
	form=$(form_tool fill "$WORK/page.html" "$@") || exit 1
	request --data "$(echo "$form" | tail -1)" "$BASE$(echo "$form" | head -1)"
}
expect_same() {
	local expected=$1 label=$2 actual=$3
	echo "$label: $actual"
	if [ "$actual" != "$expected" ]; then
		echo "Expected: $expected"
		cat "$WORK/server.log"
		exit 1
	fi
}
ajax() {
	request -H 'X-Requested-With: XMLHttpRequest' "$@"
}
ADMIN_ID=$(sql_value "SELECT id FROM users WHERE email = '$ADMIN_EMAIL'")

expect 200 'GET /admin/page/' "$(request -L "$BASE/admin/page/")"
echo "POST page -> $(submit inMenu title='Integration page' slug=integration-page status=published inMenu=on menuPosition=3)"
expect_same '1,3' 'static_page in_menu,menu_position' \
	"$(sql_value "SELECT CONCAT(in_menu, ',', menu_position) FROM static_page WHERE slug = 'integration-page'")"
PAGE_ID=$(sql_value "SELECT id FROM static_page WHERE slug = 'integration-page'")
expect 200 'GET edit page (AJAX)' "$(ajax "$BASE/admin/page/?do=pages-edit&pages-id=$PAGE_ID")"
expect_same 'checked' 'form inMenu' "$(form_tool value "$WORK/page.html" inMenu)"
expect_same '3' 'form menuPosition' "$(form_tool value "$WORK/page.html" menuPosition)"
expect 200 'GET /integration-page' "$(request -L "$BASE/integration-page")"
grep -q 'Integration page' "$WORK/page.html" || { echo 'The page is not shown.'; exit 1; }

expect 200 'GET /admin/blog/' "$(request -L "$BASE/admin/blog/")"
# comments are allowed by default, so an unchecked box shows that the value reaches its column
echo "POST article -> $(submit commentsEnabled title='Integration article' slug=integration-article \
	newCategory='Integration category' status=published content='Text of the article.' commentsEnabled=)"
expect_same "0,1,$ADMIN_ID,1" 'blog_article comments_enabled,category,author_id,published' \
	"$(sql_value "SELECT CONCAT(comments_enabled, ',', category_id IS NOT NULL, ',', author_id, ',', published_at IS NOT NULL) FROM blog_article WHERE slug = 'integration-article'")"
ARTICLE_ID=$(sql_value "SELECT id FROM blog_article WHERE slug = 'integration-article'")
CATEGORY_ID=$(sql_value "SELECT category_id FROM blog_article WHERE id = $ARTICLE_ID")
expect 200 'GET edit article (AJAX)' "$(ajax "$BASE/admin/blog/?do=articles-edit&articles-id=$ARTICLE_ID")"
expect_same '' 'form commentsEnabled' "$(form_tool value "$WORK/page.html" commentsEnabled)"
expect_same "$CATEGORY_ID" 'form categoryId' "$(form_tool value "$WORK/page.html" categoryId)"
expect 200 'GET /blog' "$(request -L "$BASE/blog")"
grep -q 'Integration article' "$WORK/page.html" || { echo 'The article is not in the blog.'; exit 1; }
expect 200 'GET /blog/integration-article' "$(request -L "$BASE/blog/integration-article")"
grep -q 'Integration category' "$WORK/page.html" || { echo 'The article does not show its category.'; exit 1; }

if [[ $VARIANT == permission ]]; then
	expect 200 'GET /admin/permission/users' "$(request -L "$BASE/admin/permission/users")"
	echo "POST user roles -> $(submit userId userId="$ADMIN_ID" 'roleId[]=1' 'roleId[]=2')"
	expect_same '1,2' 'users_roles role_id' \
		"$(sql_value "SELECT GROUP_CONCAT(role_id ORDER BY role_id) FROM users_roles WHERE user_id = $ADMIN_ID")"
	expect 200 'GET edit user roles (AJAX)' "$(ajax "$BASE/admin/permission/users?do=users-edit&users-id=$ADMIN_ID")"
	expect_same "$ADMIN_ID" 'form userId' "$(form_tool value "$WORK/page.html" userId)"
	expect_same '1,2' 'form roleId' "$(form_tool value "$WORK/page.html" 'roleId[]')"
fi

echo
echo "OK Netis integration test passed ($MODE, $VARIANT)."
