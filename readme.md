# Netis CMS

Installer for creating a Netis CMS project on top of Drago Project.

[![Integration](https://github.com/netis-cms/netis/actions/workflows/integration.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/integration.yml)
[![Coding Style](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml)
[![PHP version](https://badge.fury.io/ph/netis-cms%2Fnetis.svg)](https://badge.fury.io/ph/netis-cms%2Fnetis)

## Requirements
- PHP >= 8.3
- Composer

## Installation
```shell
composer global require netis-cms/netis:dev-main
```

## Usage
Create a new project:
```shell
create-netis cms
```

The project has permissions: the ACL of every package (drago-ex/permission) and the administration of roles
and privileges ([project-permission](https://github.com/drago-ex/project-permission)).

Without an argument, the default target directory is `netis`:
```shell
create-netis
```

You can also install into the current empty directory:
```shell
create-netis .
```

For local testing from this repository:
```shell
php bin/create-netis cms
```

## What It Does
The command creates a base Drago project, installs the Netis preset packages (with the assets composed for all
packages), copies project resources, cleans vendor resource folders and runs the package commands:
```shell
composer create-project drago-ex/project <target-dir> --no-scripts
composer require --no-scripts <netis-packages>   # project-preset
php vendor/bin/drago-install
php vendor/bin/drago-clean
php vendor/bin/drago-commands
```

It also adds `drago-commands` to the Composer scripts of the project, so the commands of packages installed later
run on `composer update`.

## Integration Test
The [Integration](.github/workflows/integration.yml) workflow creates a project with `create-netis`, runs the
migrations by `bin/project-setup`, PHPStan and the asset build, then adds the website settings and an administrator
and checks the front page,
the sign-in of an administrator and the administration. It saves a static page, a blog article and the roles of a
user and a custom role through their forms and checks their columns in the database, the edit form and the front pages, because the
camelCase properties of entities and forms are mapped to snake_case columns. It runs on every push, daily and on demand,
with the released packages and with the main branches of all drago-ex packages (the check before a release).

Locally, with a MySQL or MariaDB database:
```shell
MODE=main DB_PORT=3306 bash .github/integration/run.sh
```
With `PACKAGES_DIR` pointing to a directory with the drago-ex packages cloned, `MODE=main` takes them from there
instead of cloning the main branches, so changes that are not pushed yet are checked too.

## Releasing Packages
For maintainers with the drago-ex packages cloned next to this repository:
```shell
node netis/tools/release.mjs              # what needs a release, in which order and version
node netis/tools/release.mjs --publish    # release it
node netis/tools/release.mjs --publish --wave   # release only the first wave
```

Choose a version with `package=major|minor|patch|X.Y.Z`, and leave out packages whose changes need no release
yet (e.g. only doc comments) with `--skip=package,other`; repeat it with `--publish`. Release notes are drafted from the commits into
`temp/release-notes/`, where you can edit them before publishing. Publishing checks that each package is committed,
pushed and green in CI, and waits for Packagist between dependent packages.

CI of a package is red while it requires versions of other packages that are not released yet, so such a plan is
released in waves (the plan shows the wave of each package). `--wave` releases only the packages whose drago-ex
dependencies are released already, waits for Packagist and runs CI of the next wave again; once it is green, run the
same command again.

## After Create
Go to the created project directory:
```shell
cd cms
```

Install frontend dependencies:
```shell
npm install
```

Build frontend assets:
```shell
npm run vite:build
```

Build Docker images:
```shell
npm run docker:build
```

Start the development Docker environment (it keeps watching the changes, so continue in another terminal):
```shell
npm run docker:dev
```

Run the migrations and set the administrator and the website name with `bin/project-setup` of
[drago-ex/project](https://github.com/drago-ex/project), in the container:
```shell
docker compose exec -u www-data server php bin/project-setup
```

All available npm commands are listed in the created project's `package.json`.
