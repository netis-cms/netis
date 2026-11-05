# Netis CMS

Installer of Netis CMS: a complete website with administration on top of
[Drago Project](https://github.com/drago-ex/project).

[![Integration](https://github.com/netis-cms/netis/actions/workflows/integration.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/integration.yml)
[![Coding Style](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml)
[![PHP version](https://badge.fury.io/ph/netis-cms%2Fnetis.svg)](https://badge.fury.io/ph/netis-cms%2Fnetis)

## Requirements

- PHP >= 8.3
- Composer
- Node.js
- Docker

## Installation

```bash
composer global require netis-cms/netis:dev-main
```

## What You Get

The project is composed by [drago-ex/project-preset](https://github.com/drago-ex/project-preset): the website and the
administration with sign-in and user accounts, settings, blog with comments, static pages, image gallery and the
administration of roles and permissions. All files of the packages are copied into the project, so you adapt them
as your own code.

## Usage

```bash
create-netis cms     # into the directory cms
create-netis         # into the directory netis
create-netis .       # into the current empty directory
```

The command creates a Drago Project, installs the preset, copies the files of the packages into the project
(`drago-install`, `drago-clean`) and runs the commands of the packages (`drago-commands`). It adds `drago-commands`
to the Composer scripts, so packages installed later are set up on `composer update` too.

## After Create

```bash
cd cms
npm install
npm run vite:build
npm run docker:dev
```

`docker:dev` starts the web server and the database and keeps copying your changes into the container, so continue
in another terminal. Run the migrations and set the administrator and the website name:

```bash
docker compose exec -u www-data server php bin/project-setup
```

The website runs on `http://127.0.0.1:8080`. All npm commands are listed in `package.json` of the project.
