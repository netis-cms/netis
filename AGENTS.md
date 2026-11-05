# Agent Guide

## Package Overview
- **Package**: `netis-cms/netis` (not a drago-ex package, so `PACKAGE_MAP.md` leaves it out)
- **Purpose**: `bin/create-netis` composes a complete Netis CMS project from the drago-ex packages.
- **PHP**: >=8.3 <9, no dependencies; the script runs Composer, it does not load the packages.

## How create-netis works
- `composer create-project drago-ex/project <dir> --no-scripts`, then `composer require --no-scripts` of the
  packages (`drago-ex/project-preset`; `NETIS_INSTALL_PACKAGES` or `NETIS_INSTALL_PACKAGE` replace the list),
  then `vendor/bin/drago-install`, `drago-clean`, `drago-commands` of drago-ex/project-tools.
- It adds `drago-commands` to `post-install-cmd`/`post-update-cmd` of the project and removes
  `@php bin/project-assets`: project-preset already ships `assets/admin.js` and `admin.scss` composed for all
  packages, so a Netis project needs no `.drago-project/assets.md`. Do not document assets.md in the README.
- The database, the administrator and the website name are set afterwards by `bin/project-setup` of
  drago-ex/project in the Docker container (`docker compose exec -u www-data server php bin/project-setup`).
- Which features the project gets is decided by project-preset, not here; permissions are always included.

## README
The README is for users: what Netis is, what the project contains, how to create it and start it. Maintainer
tools (integration test, `tools/release.mjs`) are described here, not in the README.

## Integration test
`.github/integration/run.sh` creates a project with create-netis, runs `bin/project-setup`, PHPStan and the asset
build, sets the website settings and an administrator, checks the front page, the sign-in and the administration,
saves a static page, a blog article, user roles and a custom role through their forms and checks the columns
(camelCase properties mapped to snake_case columns) and the edit forms.
- `MODE=released` uses Packagist, `MODE=main` the main branches of all drago-ex packages (the check before a release).
- `PACKAGES_DIR` points to local clones, so unpushed changes are tested: from the workspace root
  `MODE=main PACKAGES_DIR=$PWD DB_PORT=3306 bash netis/.github/integration/run.sh`.
- The database comes from `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`.
- When a form or a page of a package changes, `run.sh` (and `form.php`) must follow it.
- CI runs it on push, daily and on demand, in both modes, with MariaDB.

## Releases
`node netis/tools/release.mjs` (run from the workspace with the drago-ex packages cloned next to netis):
- without arguments it plans which packages need a release (shipped changes since the last tag), in dependency
  order and in waves, drafts release notes into `temp/release-notes/` and lists the constraints that would keep
  the new versions out;
- `name=major|minor|patch|X.Y.Z` chooses a version, `--skip=a,b` leaves packages out;
- `--publish` releases after checking that each package is committed, pushed and green in CI, and waits for
  Packagist between dependent packages; `--wave` releases only the first wave.
- Always ask the user before `--publish`; never reuse a version number.

## Commands
```bash
php bin/create-netis cms        # local run of the installer
```
