# Netis cms

Little CMS installer.

[![Coding Style](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml)
[![PHP version](https://badge.fury.io/ph/netis-cms%2Fnetis.svg)](https://badge.fury.io/ph/netis-cms%2Fnetis)

## Requirements
- PHP >= 8.3
- Composer

## Installation
Install the creator command globally:
```bash
composer global require netis-cms/netis
```

Create a new Netis project:
```bash
create-netis cms
```

The target directory is optional. If it is not provided, `netis` is used:
```bash
create-netis
```

Use `.` only inside an empty directory:
```bash
mkdir cms
cd cms
create-netis .
```

For local testing from this repository, run:
```bash
php bin/create-netis cms
```

The command creates a base Drago project, installs the Netis package preset and exports SQL migrations:
```bash
composer create-project drago-ex/project <target-dir>
cd <target-dir>
composer require drago-ex/project-install:dev-main
php vendor/bin/sql-export migrations
```

Default packages installed by the creator are defined in `bin/create-netis`:
```php
$defaultInstallPackages = [
    'drago-ex/project-install:dev-main',
];
```

You can also override them for one run:
```bash
NETIS_INSTALL_PACKAGES="drago-ex/project-install:dev-main vendor/package:^1.0" create-netis cms
```

## It uses these packages
- [Drago project](https://github.com/drago-ex/project)
- [Docker Setup](https://github.com/drago-ex/project-docker)
- [Database Layer](https://github.com/drago-ex/project-docker-db)
- [User Management](https://github.com/drago-ex/project-user)
- [Authentication](https://github.com/drago-ex/project-auth)
- [Permissions (ACL)](https://github.com/drago-ex/project-permission)
- [Backend Admin](https://github.com/drago-ex/project-backend)
- [Backend UI](https://github.com/drago-ex/project-backend-ui)
- [Application Settings](https://github.com/drago-ex/project-settings)
- [Installation process](https://github.com/drago-ex/project-install)
