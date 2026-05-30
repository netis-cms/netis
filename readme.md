# Drago Project

Basis for new modules projects on Drago Extension

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/project/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject.svg)](https://badge.fury.io/ph/drago-ex%2Fproject)
[![Coding Style](https://github.com/drago-ex/project/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project/actions/workflows/coding-style.yml)

## Requirements
- PHP >= 8.3
- Nette Framework
- Composer
- Docker
- Node.js
- Bootstrap
- Naja

## Installation
```bash
composer create-project drago-ex/project
```

## Basic information
Basic package for applications where the basis for Bootstrap, Vite, Docker, Naja is already prepared.
You can find all commands in `package.json` like running Docker or Vite.

## Basic Naja scripts
- **ErrorsHandler** - Handles Naja AJAX errors by displaying user-friendly alert messages based on HTTP status codes. Shows a dismissible Bootstrap alert in the page element with ID `snippet--message`.

- **HyperlinkDisable** - Temporarily disables links with the `data-link-disable` attribute during Naja requests to prevent multiple clicks. Re-enables the links once the request is complete.

- **Spinner** - Displays a full-page spinner during active Naja AJAX requests. Shows the spinner when a request starts and hides it once all requests are complete.

## Available Extensions
Expand the base package with these ready-to-use modules:
- [Docker Setup](https://github.com/drago-ex/project-docker)
- [Database Layer](https://github.com/drago-ex/project-docker-db)
- [User Management](https://github.com/drago-ex/project-user)
- [Authentication](https://github.com/drago-ex/project-auth)
- [Permissions (ACL)](https://github.com/drago-ex/project-permission)
- [Backend Admin](https://github.com/drago-ex/project-backend)

## Package Setup Orchestrator

The project includes a specialized tool to automate the initialization of all installed packages. It scans all packages in your `vendor` directory and collects custom setup commands defined in their `composer.json`.

### Usage
Run the orchestrator (inside your Docker container if applicable):
```bash
docker compose exec server php bin/package-setup
```

**Interactions:**
- Select specific tasks by number (e.g., `1,3`).
- Run everything sequentially by entering `a`.
- Quit by entering `q`.

### How to integrate your package
Add the `drago-project` section to the `extra` part of your package's `composer.json`:

```json
{
    "extra": {
        "drago-project": {
            "priority": 10,
            "commands": {
                "db:migrate-example": "php vendor/bin/migration migrations:migrate vendor/vendor-name/package-name/migrations",
                "gen:example-class": "php vendor/bin/create-example"
            }
        }
    }
}
```

### Command Configuration (`commands`)
A key-value list where the key is the display name and the value is the shell command.
- **`db:` prefix**: Used for database migrations. These are automatically grouped at the top of the list.
- **`gen:` prefix**: Used for code generation tasks.
- **`gen:*-permission`**: Specialized prefix for generating base AccessControl permission classes required by the `drago-ex/permission` component.

### Execution Priority (`priority`)
Controls the order in which commands are listed and executed when running all (`a`).
- **Lower numbers** run earlier (higher priority).
- **Priority 10**: Recommended for core packages (Auth, Users).
- **Priority 20+**: Recommended for dependent packages (Permissions, Commerce).
- **Default 100**: Used if no priority is specified.

### Features
- **Intelligent Sorting**: Database migrations are prioritized and grouped together.
- **Auto-Initialization**: Detects if the migration system needs initial setup and offers one-click initialization.
- **Robustness**: If the database is unreachable, the tool skips DB tasks but still allows other setup commands to run.

## Useful Development Tools
- [Database Migrations](https://github.com/drago-ex/migration)
- [Code Generator](https://github.com/drago-ex/generator)
