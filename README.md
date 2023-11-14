<p align="center">
    <h1 align="center">Knowledge Modeling System</h1>
    <br />
</p>

**Knowledge Modeling System (KMS)** is a web-based application for building different visual diagrams. KMS includes a set of visual editors for modeling domain knowledge in various notations.

KMS is based on [PHP 8](https://www.php.net/releases/8.0/ru.php) and [Yii 2 Framework](http://www.yiiframework.com/).

KMS editors use [jsPlumb Toolkit](https://jsplumbtoolkit.com/), version 2.12.9 for visualization of diagrams.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

### Version

1.0

DIRECTORY STRUCTURE
-------------------

      commands/           contains console commands (controllers) for creation of lang, user and diargams by default
      components/         contains the XML importer and generator for event tree and state transition diagrams, as well as the OWL ontology importer and lang components
      config/             contains main configurations for this application and database
      messages/           contains internationalization files for English and Russian languages
      migrations/         contains migrations for creation of all tables of a new database
      modules/            contains following software modules:
          api/            contains controller for accessing this application via REST API
          eete/           contains main controllers, models and views for the Extended Event Tree Editor (EETE)
          stde/           contains main controllers, models and views for the State Transition Diagram Editor (STDE)
          main/           contains views for main information on diagrams, users and virtual assistants
      web/                contains js-scripts, css-scripts and other web resources


REQUIREMENTS
------------

The minimum requirement by this project that your Web server supports PHP 8.0, jsPlumb 2.12, PostgreSQL 9.0 or MySQL 8.0.

We recommend you to use a combination of [PHP 8.2](https://www.php.net/downloads) with DBMS [PostgreSQL 15](https://www.postgresql.org/download/) or more.

INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you can install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install KMS using the following command:

~~~
composer create-project lab42-team/kms
~~~

### Install via Git
If you do not have [Git](https://git-scm.com/), you can install [it](https://git-scm.com/downloads) depending on your OS.

You can clone this project into your directory (recommended installation):

~~~
git clone https://github.com/Lab42-Team/kms.git
~~~

### Update dependencies
After installation, you need to update project's dependencies. In particular, this includes installing Yii2 framework itself into the vendor folder.

The following commands are entered sequentially into the console (located in the project folder):
- `composer self-update` — Composer update;
- `composer global update` — Composer global update;
- `composer clear-cache` — clearing Composer's internal package cache;
- `composer update` — update all dependencies to the latest versions.

**NOTES:**
If for some reason the dependencies could not be installed (there is no vendor folder), then you need to manually add the vendor folder to this project.

CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

##### PostgreSQL:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;port=5432;dbname=kms_db;',
    'username' => 'postgres',
    'password' => 'admin',
    'charset' => 'utf8',
    'tablePrefix' => 'kms_',
    'schemaMap' => [
        'pgsql'=> [
            'class'=>'yii\db\pgsql\Schema',
            'defaultSchema' => 'public'
        ]
    ],
];
```

##### MySQL:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=kms_db;',
    'username' => 'admin',
    'password' => 'root',
    'charset' => 'utf8',
    'tablePrefix' => 'kms_',
];

```

**NOTES:**
- KMS won't create a new database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.

##### Database commands:
KMS contains commands for filling a new database with the initial data necessary for the application to run.
This set of commands is entered sequentially into the console (located in the project folder):
- `php yii migrate/up` — applying migrations (creating all tables in a new database);
- `php yii lang/create` — creating default locales for `RU` and `EN` in a new database;
- `php yii user/create-default-user` — creating a default user (administrator with login: `admin` and password: `admin`);
- `php yii event-trees/create` — creating event tree diagrams (element “part” from the reliability block; consequences as a result of tank destruction), including levels, events and default mechanisms.
- `php yii state-transition-diagram/create` — creating a default state transition diagram.

AUTHORS
-------------

* [Dmitry A. Shpachenko](mailto:demix357@mail.ru)
* [Nikita O. Dorodnykh](mailto:tualatin32@mail.ru)
* [Aleksandr Yu. Yurin](mailto:iskander@icc.ru)