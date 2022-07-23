<?php

declare(strict_types=1);

namespace Conia\I18n;

use Conia\Cli\Command;


class Update extends Command
{
    use Validates;

    protected string $name = 'update';
    protected string $group = 'Internationalization/gettext';
    protected string $prefix = 'i18n';
    protected string $description = 'Updates all existing translations catalogs based on a POT template';

    public function __construct(
        protected readonly string $dir,
        protected readonly string $domain
    ) {
    }

    public function run(): int
    {
        $this->validateDir($this->dir);
        $this->validateDomain($this->domain);

        $dir = $this->dir;
        $domain = $this->domain;

        $localeDirs = array_filter(glob($this->dir . '/*'), 'is_dir');
        $locales = array_map(fn ($locale) => basename($locale), $localeDirs);

        foreach ($locales as $locale) {
            system(
                "msgmerge " .
                    "  --no-fuzzy-matching" .
                    "  --update $dir/$locale/LC_MESSAGES/$domain.po" .
                    "  $dir/$domain.pot"
            );
        };

        return 0;
    }
}
