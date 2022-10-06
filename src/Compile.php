<?php

declare(strict_types=1);

namespace Conia\I18n;

use Conia\Cli\Command;

class Compile extends Command
{
    use Validates;

    protected string $name = 'compile';
    protected string $group = 'Internationalization/gettext';
    protected string $prefix = 'i18n';
    protected string $description = 'Takes a message catalog from a PO file and compiles it to a binary MO file';

    public function __construct(
        protected readonly string $dir,
        protected readonly string $domain,
        protected readonly ?string $jsonDir = null,
        protected readonly array $params,
    ) {
    }

    protected function getPo2Json(): string
    {
        $script = $this->params['po2json']['path'] ?? 'node_modules/.bin/po2json-gettextjs';

        if (is_file($script)) {
            return $script;
        }

        $this->checkShellCommand('po2json-gettextjs');

        return 'po2json-gettextjs';
    }

    public function run(): int
    {
        $this->checkShellCommand('msgfmt');
        $this->validateDir($this->dir);
        $this->validateDomain($this->domain);

        $po2json = null;

        if ($this->jsonDir) {
            $po2json = $this->getPo2Json();
            $this->validateDir($this->jsonDir);
        }

        $dir = $this->dir;
        $jsonDir = $this->jsonDir;
        $domain = $this->domain;

        $localeDirs = array_filter(glob($this->dir . '/*'), 'is_dir');
        $locales = array_map(fn ($locale) => basename($locale), $localeDirs);

        foreach ($locales as $locale) {
            $inputFile = "$dir/$locale/LC_MESSAGES/$domain.po";
            $this->echo("Compile locale '$locale'\n");
            $this->echo("  $inputFile\n");
            $cmd = "msgfmt --output-file=$dir/$locale/LC_MESSAGES/$domain.mo";

            foreach ($this->params as $param => $value) {
                if (str_starts_with('--', $param)) {
                    $cmd .= ' ' . $param . ($value ? '=' . $value : '');
                } else {
                    $cmd .= ' ' . trim($param . ' ' . ($value ?? ''));
                }
            }

            system($cmd . " $inputFile");

            if ($jsonDir && $po2json) {
                $outFile = "$jsonDir/$locale/$domain.json";
                $this->echo("Compile '$locale' json file for frontend\n");
                $this->echo("  $outFile\n");

                if (!is_dir("$jsonDir/$locale")) {
                    mkdir("$jsonDir/$locale", 0755, true);
                }

                $cmd = $po2json .
                    " $dir/$locale/LC_MESSAGES/$domain.po" .
                    " $outFile";

                system($cmd);
            }
        };

        return 0;
    }
}
