<?php

/**
 * Writes a Composer config.json whose path repositories install the local working trees of the drago-ex packages
 * instead of their releases (MODE=main of run.sh, and tools/test-project.sh).
 *
 *   php composer-config.php <packages-dir> <config.json>
 *
 * Only git repositories of drago-ex packages are taken (test projects next to them are left out). Each package gets
 * the version <major>.99.0, where major is the highest of its latest tag and of the majors the other packages
 * require, so the unreleased majors of the main branches (e.g. a dependent already on ^2.0) are satisfied too.
 * A package without a tag gets 1.99.0 unless something requires a higher major.
 */

declare(strict_types=1);

[, $packagesDir, $configFile] = $argv + [null, null, null];
if ($packagesDir === null || $configFile === null) {
	fwrite(STDERR, "Usage: php composer-config.php <packages-dir> <config.json>\n");
	exit(1);
}

$packages = [];
foreach (glob(rtrim($packagesDir, '/\\') . '/*/composer.json') ?: [] as $file) {
	$dir = dirname($file);
	$name = json_decode((string) file_get_contents($file), true)['name'] ?? '';
	if (str_starts_with($name, 'drago-ex/') && is_dir("$dir/.git")) {
		$packages[$name] = $dir;
	}
}

// the highest major each package is required with by the others: ^3.0 || ^4.0 counts as 4
$required = [];
foreach ($packages as $dir) {
	$composer = json_decode((string) file_get_contents("$dir/composer.json"), true);
	foreach ([...($composer['require'] ?? []), ...($composer['require-dev'] ?? [])] as $name => $constraint) {
		if (isset($packages[$name]) && preg_match_all('~\^(\d+)~', (string) $constraint, $m)) {
			$required[$name] = max($required[$name] ?? 0, ...array_map('intval', $m[1]));
		}
	}
}

$repositories = [];
foreach ($packages as $name => $dir) {
	exec('git -C ' . escapeshellarg($dir) . ' tag --list "v*" --sort=-v:refname', $tags);
	$tagMajor = isset($tags[0]) ? (int) ltrim($tags[0], 'v') : 1;
	$tags = [];
	$version = max($tagMajor, $required[$name] ?? 1) . '.99.0';
	$repositories[] = ['type' => 'path', 'url' => $dir, 'options' => ['symlink' => false, 'versions' => [$name => $version]]];
}

file_put_contents($configFile, json_encode(['repositories' => $repositories], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo count($repositories), " packages\n";
