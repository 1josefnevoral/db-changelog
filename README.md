# Db changelog handles changes in db structure.

[![Build Status](https://travis-ci.org/lovec/db-changelog.svg?branch=master)](https://travis-ci.org/lovec/db-changelog)
[![Downloads this Month](https://img.shields.io/packagist/dm/lovec/db-changelog.svg)](https://packagist.org/packages/lovec/db-changelog)
[![Latest stable](https://img.shields.io/packagist/v/lovec/db-changelog.svg)](https://packagist.org/packages/lovec/db-changelog)


It is a module Changelog. You can access it from browser by http://*yourProject*/changelog/
If you make changes in structure, access the url above and insert your sql code.
It will show up in your git changes to commit.

In development mode, changelog automatically detect pulled changes in database and show them to execute in
your local database.
In production. You need to manually go to http://*yourProject*/changelog/ and execute changes


## Installation

Register extension in your `config.neon`:

```yaml
extensions:
	changelog: Lovec\DbChangelog\DI\ChangelogExtension

changelog: # you can change these defaults
    dir: "%appDir%/../changelog"
    table: changelog
```

