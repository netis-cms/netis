## Netis, Little CMS

Little CMS.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://raw.githubusercontent.com/netis-cms/netis/master/license.md)
[![PHP version](https://badge.fury.io/ph/netis-cms%2Fnetis.svg)](https://badge.fury.io/ph/netis-cms%2Fnetis)
[![Coding Style](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/coding-style.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/netis-cms/netis/badge)](https://www.codefactor.io/repository/github/netis-cms/netis)
[![Qodana](https://github.com/netis-cms/netis/actions/workflows/code_quality.yml/badge.svg)](https://github.com/netis-cms/netis/actions/workflows/code_quality.yml)

## Technology
- PHP 8.1 or higher
- composer
- docker
- node.js

Based on:
- [Nette Framework](https://github.com/nette/nette)
- [Drago Extension Nette Framework](https://github.com/drago-ex)
- [dibi - smart database abstraction layer](https://github.com/dg/dibi)
- [Bootstrap](https://github.com/twbs/bootstrap)
- [Compostrap](https://github.com/compostrap)

## Installation

```
composer create-project netis-cms/netis
```

## npm
```
npm i
```

## docker build
```
docker-compose build
```

## docker dev up
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up
```

## parcel build
```
parcel build public/js/*.js --public-url ./ --dist-dir www/files
```
