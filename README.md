# Db changelog handles changes in db structure.

[![Build Status](https://img.shields.io/travis/lovec/DbChangelog/tests.svg?style=flat-square)](https://travis-ci.org/lovec/DbChangelog)
[![Quality Score](https://img.shields.io/scrutinizer/g/lovec/DbChangelog.svg?style=flat-square)](https://scrutinizer-ci.com/g/lovec/DbChangelog)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/lovec/DbChangelog.svg?style=flat-square)](https://scrutinizer-ci.com/g/lovec/DbChangelog)
[![Downloads this Month](https://img.shields.io/packagist/dm/lovec/db-changelog.svg?style=flat-square)](https://packagist.org/packages/lovec/db-changelog)
[![Latest stable](https://img.shields.io/packagist/v/lovec/db-changelog.svg?style=flat-square)](https://packagist.org/packages/lovec/db-changelog)


It is a module Changelog. You can access it from browser by http://*yourProject*/db-changelog/
If you make changes in structure, access the url above and insert your sql code.
It will show up in your git changes to commit.

In development mode, changelog automatically detect pulled changes in database and show them to execute in
your local database.
In production. You need to manually go to http://*yourProject*/db-changelog/ and execute changes


## Requirements

- PHP 5.5
- Nette\Database


## Install

Via Composer:

```sh
composer require lovec/db-changelog
```

Register extension in your `config.neon`:

```yaml
extensions:
	changelog: Lovec\DbChangelog\DI\ChangelogExtension

changelog: # you can change these defaults
    dir: '%appDir%/changelog'
    table: changelog
```
