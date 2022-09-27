<?php

declare(strict_types=1);

class Cls
{
    public function __construct()
    {
        $s = _d('testdomain', 'dgettext short');
        $s = _dgettext('testdomain', 'dgettext long');
        $s = _dn('testdomain', 'dngettext short', 'dngettext short plural', 2);
        $s = _dngettext('testdomain', 'dngettext long', 'dngettext long plural', 2);
        $s = _dnp('testdomain', 'dropdown', 'dnpgettext short', 'dnpgettext short plural', 2);
        $s = _dnpgettext('testdomain', 'dropdown', 'dnpgettext long', 'dnpgettext long plural', 2);
        $s = _dp('testdomain', 'dropdown', 'dpgettext short');
        $s = _dpgettext('testdomain', 'dropdown', 'dpgettext long');
        $s = __('gettext short');
        $s = _gettext('gettext long');
        $s = _n('ngettext short', 'ngettext short plural', 2);
        $s = _ngettext('ngettext long', 'ngettext long plural', 2);
        $s = _np('dropdown', 'npgettext short', 'npgettext short plural', 2);
        $s = _npgettext('dropdown', 'npgettext long', 'npgettext long plural', 2);
        $s = _p('dropdown', 'pgettext short');
        $s = _pgettext('dropdown', 'pgettext long');
    }
}
