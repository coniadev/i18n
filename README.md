Conia `gettext` Tools
=====================

> :warning: **Note**: This i18n package is under active development, some of the listed features are still experimental and subject to change. Large parts of the documentation are missing. 

Provides helpers setup gettext in a PHP project, to extract string marked for translation from source code and to initialize, update, and compile *.po/*.mo files.

Requirements:

* gettext
* po2json (if you need json files)

Install requirements on Debian/Ubuntu

    apt install gettext

If you additionally want to compile to json files, you need a node.js
installation and the npm package `gettext.js` either installed globally
or in the `node_modules` directory in the current working directory.

    npm install -g gettext.js

