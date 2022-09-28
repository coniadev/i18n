---
title: Introduction
---
Conia `gettext` Tools
=====================

> :warning: **Note**: This i18n package is under active development, some of the listed features are still experimental and subject to change. Large parts of the documentation are missing. 

Provides `conia/cli` commands which help to setup gettext files in a PHP project. For example to extract strings marked for translation from source code and to initialize, update, and compile *.po/*.mo files.

## Requirements:

- **gettext** to add the commandline po/mo tools like xgettext, msginit, msgfmt etc.
- **po2json** (if you need json files created from the *.po files)

Install requirements on Debian/Ubuntu

    apt install gettext

If you additionally want to compile to json files, you need a node.js
installation and the npm package `gettext.js` either installed globally
or in the `node_modules` directory in the current working directory.

    npm install -g gettext.js

## Usage:

This helper provides four commands:

- **extract**, which extracts translation function calls to *.pot file. Internally it calls `xgettext`. **Note**: it always overwrites the *.pot file.
- **init** initializes a language/locale and uses `msginit`.
- **update** updates existing *.po files according to the *.pot file with `msgmerge`.
- **compile** compiles the *.po files to *.mo files using `msgmerge`.

It overrides the language specific options and keyword settings of `xgettext` to support
`phpmyadmin/motranslator` functions. The following function calls and their short forms will be extracted:

- _dgettext, _d
- _dngettext, _dn
- _dnpgettext, _dnp
- _dpgettext, _dp
- _gettext, __ (Note: a single underscore call will NOT be extracted)
- _ngettext, _n
- _npgettext, _np
- _pgettext, _p
Conia Cli
=========

A command line interface helper like [Laravel's Artisan](https://laravel.com/docs/9.x/artisan) 
with way less magic.

## Installation

    composer require conia/cli

## Quick Start

Create a Command:

```php
use Conia\Cli\Command;

class MyCommand extends Command {
    /**
     * The name by which the  MyCommand::run() method
     * is invoked from the command line.
     */
    protected string $name = 'mycommand';

    /**
     * A namespace used to distinguish equally named commands
     * from different package, e. g. `grp:mycommand`
     */
    protected string $prefix = 'grp'; // optional

    /**
     * The group name under which the command will be 
     * listed in the help. Also used as prefix (lowercased)
     * if the prefix is missing
     */
    protected string $group = 'MyGroup';

    /**
     * A short description displayed in the command list
     */
    protected string $description = 'This is my command description';


    /**
     * The entry point of the command.
     */
    public function run(): int
    {
        $this->echo("Run my command\n");

        return 0;
    }

    /**
     * Optional:
     * Used to add information to the commands help text
     * (e. g. `php run help <command>`)
     */
    public function help(): void
    {
        $this->helpHeader(withOptions: true);
        $this->helpOption('-s, --stuff <stuff>', 'Description of --stuff');
    }
}
```

Create a runner script, e. g. `run.php` or simply `run`:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Conia\Cli\{Runner, Commands};
use MyCommand;

$commands = new Commands([new MyCommand()]);
$runner = new Runner($commands);
$runner->run();
```

Run the command:

```console
$ php run mycommand
Run my command

$ php run mygroup:mycommand
Run my command

$ php run help
Available commands:

MyGroup
    grp:mycommand  This is my command description

$ php run help mycommand
Help entry for my command
```

