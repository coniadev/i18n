<?php

declare(strict_types=1);

namespace Conia\I18n;

use Conia\Cli\Commands;
use PhpMyAdmin\MoTranslator;

class I18n
{
    public static function commands(
        string $dir,
        string $domain,
        array $sources,
        ?string $jsonDir = null,
        array $params = [],
    ): Commands {
        return new Commands([
            new Extract($dir, $domain, $sources, $params['xgettext'] ?? []),
            new Init($dir, $domain, $params['msginit'] ?? []),
            new Update($dir, $domain, $params['msgmerge'] ?? []),
            new Compile($dir, $domain, $jsonDir, $params['msgfmt'] ?? []),
        ]);
    }

    public static function setupGettext(string $locale, array $domains, string $default): void
    {
        MoTranslator\Loader::loadFunctions();
        require_once __DIR__ . '/functions.php';

        _setlocale(LC_MESSAGES, $locale);

        foreach ($domains as $domain => $dir) {
            _bindtextdomain($domain, $dir);
            _bind_textdomain_codeset($domain, 'UTF-8');
        }

        _textdomain($default);
    }
}
