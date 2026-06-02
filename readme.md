# Netis CMS

Installer for creating a Netis CMS project on top of Drago Project.

[![Coding Style](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml)
[![PHP version](https://badge.fury.io/ph/netis-cms%2Fnetis.svg)](https://badge.fury.io/ph/netis-cms%2Fnetis)

## Requirements
- PHP >= 8.3
- Composer

## Installation
```shell
composer global require netis-cms/netis
```

## Usage
Create a new project:
```shell
create-netis cms
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
The command creates a base Drago project, installs the Netis preset packages and exports SQL migrations:
```shell
composer create-project drago-ex/project <target-dir>
composer require <netis-packages>
php vendor/bin/sql-export migrations
```

Default packages are configured in `bin/create-netis` using `$defaultInstallPackages`.

For one-off testing, you can override them:
```shell
NETIS_INSTALL_PACKAGES="drago-ex/project-install:dev-main vendor/package:^1.0" create-netis cms
```
