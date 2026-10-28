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

With permissions (the administration menu by privileges, write buttons, roles from the database,
[project-preset-permission](https://github.com/drago-ex/project-preset-permission)):
```shell
create-netis cms --permission
```

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
The command creates a base Drago project, installs the Netis preset packages, copies project resources,
cleans vendor resource folders and runs the package commands (e.g. the permission providers of the installed packages):
```shell
composer create-project drago-ex/project <target-dir> --no-scripts
composer require --no-scripts <netis-packages>   # project-preset, project-install (+ project-preset-permission with --permission)
php vendor/bin/drago-install
php vendor/bin/drago-clean
php vendor/bin/drago-commands
```

It also adds `drago-commands` to the Composer scripts of the project, so packages installed later get
their permission providers on `composer update`. The migrations are run by the web installer.

## Integration Test
The [Integration](.github/workflows/integration.yml) workflow creates a project with `create-netis`, runs the
migrations, PHPStan and the asset build, then sets the project up as the web installer does and checks the front page,
the sign-in of an administrator and the administration. It runs on every push, daily and on demand,
with the released packages and with the main branches of all drago-ex packages (the check before a release),
each without and with `--permission` (`VARIANT=base|permission`, default `permission`).

Locally, with a MySQL or MariaDB database:
```shell
MODE=main DB_PORT=3306 bash .github/integration/run.sh
```

## Releasing Packages
For maintainers with the drago-ex packages cloned next to this repository:
```shell
node netis/tools/release.mjs              # what needs a release, in which order and version
node netis/tools/release.mjs --publish    # release it
```

Choose a version with `package=major|minor|patch|X.Y.Z`. Release notes are drafted from the commits into
`temp/release-notes/`, where you can edit them before publishing. Publishing checks that each package is committed,
pushed and green in CI, and waits for Packagist between dependent packages.

project-preset-permission ships copies of the blog, static-page and gallery administration components with their
permission steps done. After such a file changes in its package, copy them again:
```shell
php netis/tools/preset-permission.php
```

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

Start the development Docker environment:
```shell
npm run docker:dev
```

All available npm commands are listed in the created project's `package.json`.
