#!/usr/bin/env bash
# Integration test of the Netis stack: creates a project with create-netis and checks that it installs,
# migrates, passes PHPStan, builds its assets and answers HTTP requests.
#
# MODE=released  packages from Packagist, as users get them
# MODE=main      main branches of all drago-ex packages (cloned, or taken from PACKAGES_DIR), the check before a release
#
# The database is given by DB_HOST, DB_PORT, DB_USER, DB_PASSWORD and DB_NAME.
set -euo pipefail

MODE=${MODE:-released}
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

	# A path repository with every tagged drago-ex package; version <major>.99.0 satisfies the constraints
	# between the packages, so the main branches are installed instead of the releases.
	mkdir -p "$WORK/composer-home"
	export COMPOSER_HOME=$(native "$WORK/composer-home")
	php -r '
		[, $packages, $configFile] = $argv;
		$versions = [];
		foreach (glob("$packages/*/composer.json") as $file) {
			$dir = dirname($file);
			$name = json_decode((string) file_get_contents($file), true)["name"] ?? "";
			$tag = trim((string) shell_exec("git -C " . escapeshellarg($dir) . " describe --tags --abbrev=0 2>" . (PHP_OS_FAMILY === "Windows" ? "NUL" : "/dev/null")));
			if (str_starts_with($name, "drago-ex/") && $tag !== "") {
				$versions[$name] = explode(".", ltrim($tag, "v"))[0] . ".99.0";
			}
		}
		$repository = ["type" => "path", "url" => "$packages/*", "options" => ["symlink" => false, "versions" => $versions]];
		file_put_contents($configFile, json_encode(["repositories" => [$repository]], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		echo count($versions), " packages\n";
	' "$(native "$PACKAGES")" "$COMPOSER_HOME/config.json"
fi

step "create-netis ($MODE)"
(cd "$WORK" && php "$NETIS/bin/create-netis" app)
check_report install
check_report permission

step "Migrations"
cat > "$APP/app/db.neon" <<NEON
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
	php -S "127.0.0.1:$HTTP_PORT" -t "$APP/www" "$APP/www/index.php" >> "$WORK/server.log" 2>&1 &
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
sql "INSERT INTO settings (name, value) VALUES ('website', 'Netis integration'), ('description', 'Integration test'), ('installed', '1')"
(cd "$APP" && php vendor/bin/create-user admin "$ADMIN_EMAIL" "$ADMIN_PASSWORD")
sql "INSERT INTO users_roles (user_id, role_id) SELECT id, 1 FROM users WHERE email = '$ADMIN_EMAIL'"
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
expect 200 'GET /admin/permission/roles' "$(request -L "$BASE/admin/permission/roles")"

echo
echo "OK Netis integration test passed ($MODE)."
