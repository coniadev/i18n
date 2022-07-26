---
title: Introduction
---
Conia `gettext` Tools
=====================

!!! warning "Note"
    This i18n package is under active development, some of the listed features are still experimental and subject to change. Large parts of the documentation are missing. 

Provides `conia/cli` commands which help to setup gettext files in a PHP project which uses `phpmyadmin/motranslator`. It extracts strings marked for translation from source code and  initializes, updates, and compiles *.po/*.mo files.

## Requirements:

- **gettext** to add the commandline po/mo tools like xgettext, msginit, msgfmt etc.
- **po2json** (if you need json files created from the \*.po files)

Install requirements on Debian/Ubuntu

    apt install gettext

If you additionally want to compile to json files, you need a node.js
installation and the npm package `gettext.js` either installed globally
or in the `node_modules` directory in the current working directory.

    npm install -g gettext.js

## Usage:

This helper provides four commands:

- **extract**, which extracts translation function calls to \*.pot file. Internally it calls `xgettext`. **Note**: it always overwrites the *.pot file.
- **init** initializes a language/locale and uses `msginit`.
- **update** updates existing \*.po files according to the \*.pot file with `msgmerge`.
- **compile** compiles the \*.po files to \*.mo files using `msgmerge`.

### Add the commands to your runner script

If you don't know what a runner script is, see [conia/cli](https://conia.dev/cli/).

```php
<?php 

require __DIR__ . '/vendor/autoload.php';

use Conia\Cli\Runner;
use Conia\I18n\I18n;
use Conia\I18n\Source;

$commands = I18n::commands(
    // The directory where the *.pot/*.po/*.mo files are created in
    dir: __DIR__ . '/locale',

    // The gettext domain
    domain: 'mydomain',

    // The sources from which the translation string are extracted
    // The last argument of the Source() constructor is the language
    // name used by the xgettext --language parameter. See:
    // https://www.gnu.org/software/gettext/manual/html_node/xgettext-Invocation.html
    sources: [
        new Source(__DIR__ . '/src', '*.php', 'PHP'),
        new Source(__DIR__ . '/frontend', '*.js', 'Javascript'),
    ],

    // If you want to create json files, add a path to an existing dir
    jsonDir: __DIR__ . '/public/locale',

    // Optional: add additional parameters for the gettext commands
    // Examples:
    params: [
        // php run i18n:extract
        'xgettext' => [
            '--force-po' => null,
            '--copyright-holder' => '"ebene f├╝nf GmbH"',
        ],
        // php run i18n:init
        'msginit' => [
            '--no-translator' => null,
        ],
        // php run i18n:update
        'msgmerge' => [
            '--no-fuzzy-matching' => null,
        ],
        // php run i18n:compile
        'msgfmt' => [
            '--check-header' => null,
        ],
        'po2json' => [
            'path' => '/path/to/po2json',
        ]
    ]
);

$runner = new Runner($commands);

exit($runner->run());
```

### How to use

Initialize languages:

    php run i18n:extract
    php run i18n:init --locale en
    php run i18n:init --locale de

This creates the file `./locale/mydomain.pot` and the po files

- `./locale/en/LC_MESSAGES/mydomain.po`
- `./locale/de/LC_MESSAGES/mydomain.po`

Now you can translate the string by adding translations to the \*.po files. When you
are done, you have to compile the \*.po files to \*.mo files:

    php run i18n:compile

After you add new strings to your source code, you have to extract again and then 
update the \*.po files

    php run i18n:extract
    php run i18n:update
    # now translate the added strings by editing the updated po files
    php run i18n:compile


It overrides the language specific options and keyword settings of `xgettext` to support
`phpmyadmin/motranslator` functions. The following function calls and their short forms will be extracted:

    _dgettext, _d
    _dngettext, _dn
    _dnpgettext, _dnp
    _dpgettext, _dp
    _gettext, __ (Note: a single underscore call will NOT be extracted)
    _ngettext, _n
    _npgettext, _np
    _pgettext, _p
