Conia `gettext` Tools
=====================

> :warning: **Note**: This i18n package is under active development, some of the listed features are still experimental and subject to change. Large parts of the documentation are missing. 

Provides helpers setup gettext in a PHP project, to extract string marked for translation from source code and to extract, initialize, update, and compile *.po/*.mo files.

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

Requirements:

* gettext (only if you want to use the commandline po/mo tools like xgettext, msginit, msgfmt etc.)
* po2json (if you need json files)

Install requirements on Debian/Ubuntu

    apt install gettext

If you additionally want to compile to json files, you need a node.js
installation and the npm package `gettext.js` either installed globally
or in the `node_modules` directory in the current working directory.

    npm install -g gettext.js

