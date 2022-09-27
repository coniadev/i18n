<?php

declare(strict_types=1);

use PhpMyAdmin\MoTranslator\Loader;

function _n(string $msgid, string $msgidPlural, int $number): string
{
    return Loader::getInstance()->getTranslator()->ngettext($msgid, $msgidPlural, $number);
}

function _p(string $msgctxt, string $msgid): string
{
    return Loader::getInstance()->getTranslator()->pgettext($msgctxt, $msgid);
}

function _np(string $msgctxt, string $msgid, string $msgidPlural, int $number): string
{
    return Loader::getInstance()->getTranslator()->npgettext($msgctxt, $msgid, $msgidPlural, $number);
}

function _d(string $domain, string $msgid): string
{
    return Loader::getInstance()->getTranslator($domain)->gettext($msgid);
}

function _dn(string $domain, string $msgid, string $msgidPlural, int $number): string
{
    return Loader::getInstance()->getTranslator($domain)->ngettext($msgid, $msgidPlural, $number);
}

function _dp(string $domain, string $msgctxt, string $msgid): string
{
    return Loader::getInstance()->getTranslator($domain)->pgettext($msgctxt, $msgid);
}

function _dnp(string $domain, string $msgctxt, string $msgid, string $msgidPlural, int $number): string
{
    return Loader::getInstance()->getTranslator($domain)->npgettext($msgctxt, $msgid, $msgidPlural, $number);
}
