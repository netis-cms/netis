# Netis CMS

Installer for creating a Netis CMS project on top of Drago Project.

[![Coding Style](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml)
[![PHP version](https://badge.fury.io/ph/netis-cms%2Fnetis.svg)](https://badge.fury.io/ph/netis-cms%2Fnetis)

## Requirements
- PHP >= 8.3
- Composer

## Installation
```shell
composer global require netis-cms/netis:dev-master
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
