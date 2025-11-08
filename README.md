Spreadsheet Translator Local File Provider
=========================================

This package provides a Spreadsheet Translator provider that reads spreadsheet documents from the local filesystem (local disks, mounted drives, or shared resources). It copies the configured source file to a temporary local path and returns a `Resource` object that the core library can consume.  
The project now targets PHP 8.4 and includes a PHPUnit test suite and Makefile-powered workflows.

Features
--------

* Safely copies the source spreadsheet to a local temporary file before processing.
* Keeps the provider’s output format configurable while defaulting to `xlsx`.
* Fully compatible with PHP 8.4 and the `samuelvi/spreadsheet-translator-core` ^8.4 series.
* Ships with PHPUnit coverage to prevent regressions in file handling.

Requirements
------------

* PHP >= 8.4
* Composer 2
* Filesystem access to both the source file and the configured temporary directory

Installation
------------

```bash
composer require samuelvi/spreadsheet-translator-provider-localfile
```

Usage
-----

```php
<?php

use Atico\SpreadsheetTranslator\Core\Configuration\Configuration;
use Atico\SpreadsheetTranslator\Provider\LocalFile\LocalFileProvider;

$configuration = new Configuration([
    'providers' => [
        'local_file' => [
            'source_resource' => '/srv/shared/source.xlsx',
            'temp_local_source_file' => sys_get_temp_dir() . '/sheet-copy.xlsx',
            'format' => 'csv',
        ],
    ],
], 'local_file');

$provider = new LocalFileProvider($configuration);
$resource = $provider->handleSourceResource();

printf(
    'Copied "%s" with format "%s"%s',
    $resource->getValue(),
    $resource->getFormat(),
    PHP_EOL
);
```

Testing & Tooling
-----------------

* `composer install` – install dependencies (PHP 8.4 runtime required).
* `composer test` / `./vendor/bin/phpunit` – run the PHPUnit 11 suite.
* `make test` – execute the same PHPUnit suite via the Makefile.
* `make check` – install dependencies and run tests in one step.
* `make rector` – apply Rector rules defined in `rector.php`.
* `make clean` – remove local build caches such as `.phpunit.cache`.

Related
-------

  * <a href="https://github.com/samuelvi/spreadsheet-translator-core">Core Bundle</a>
  * <a href="https://github.com/samuelvi/spreadsheet-translator-symfony-bundle">Symfony Bundle</a>

Contributing
------------

We welcome contributions to this project, including pull requests and issues (and discussions on existing issues).

If you'd like to contribute code but aren't sure what, the issues list is a good place to start. If you're a first-time code contributor, you may find Github's guide to <a href="https://guides.github.com/activities/forking/">forking projects</a> helpful.

All contributors (whether contributing code, involved in issue discussions, or involved in any other way) must abide by our code of conduct.

License
-------

Spreadsheet Translator Symfony Bundle is licensed under the MIT License. See the LICENSE file for full details.
