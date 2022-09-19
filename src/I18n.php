<?php

declare(strict_types=1);

namespace Conia\I18n;

use Conia\Cli\Commands;

class I18n
{
    public static function commands(
        string $dir,
        string $domain,
        array $sources,
        ?string $jsonDir = null,
    ): Commands {
        return new Commands([
            new Extract($dir, $domain, $sources),
            new Init($dir, $domain),
            new Update($dir, $domain),
            new Compile($dir, $domain, $jsonDir),
        ]);
    }

    public static function setupGettext(string $locale, array $domains, string $default): void
    {
        setlocale(LC_ALL, $locale);

        foreach ($domains as $domain => $dir) {
            if (!bindtextdomain($domain, $dir)) {
                throw new \ErrorException('gettext error: bindtextdomain failed');
            }
        }

        // Set the default domain
        textdomain($default);
    }
}
