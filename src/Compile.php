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
        $script = 'node_modules/.bin/po2json';

        if (is_file($script)) {
            return $script;
        }

        $this->checkShellCommand('po2json');

        return 'po2json';
    }

    public function run(): int
    {
        $this->checkShellCommand('msgfmt');
        $this->validateDir($this->dir);
        $this->validateDomain($this->domain);

        if ($this->jsonDir) {
            $this->checkShellCommand('po2json');
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

            if ($jsonDir) {
                $outFile = "$jsonDir/$locale/$domain.json";
                $this->echo("Compile '$locale' json file for frontend\n");
                $this->echo("  $outFile\n");

                if (!is_dir("$jsonDir/$locale")) {
                    mkdir("$jsonDir/$locale", 0755, true);
                }

                $cmd = $this->getPo2Json() .
                    " $dir/$locale/LC_MESSAGES/$domain.po" .
                    " $outFile";

                system($cmd);
            }
        };

        return 0;
    }
}
