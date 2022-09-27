<?php

declare(strict_types=1);

namespace Conia\I18n;

use Conia\Cli\Command;

class Extract extends Command
{
    use Validates;

    protected string $name = 'extract';
    protected string $group = 'Internationalization/gettext';
    protected string $prefix = 'i18n';
    protected string $description = 'Extracts translatable strings from source files';

    public function __construct(
        protected string $dir,
        protected string $domain,
        protected readonly array $sources,
        protected readonly array $params,
    ) {
    }

    protected function extract(
        string $srcdir,
        string $glob,
        string $language,
        string $domain,
        string $potfile,
        bool $join,
    ): void {
        $find =  " -type d \\( " .
            "-name node_modules -o -name Plugin " . // excluded
            "\\) -prune -false -o -name";

        $cmd = "find $srcdir" . $find . " '$glob' | xargs " .
            'xgettext' .
            ' --from-code=UTF-8' .
            " --language=$language" .
            ($join ? ' --join-existing' : '') .
            " --keyword=" . // disable default keyword spec
            " --keyword=_dgettext:2" .
            " --keyword=_d:2" .
            " --keyword=_dngettext:2,3" .
            " --keyword=_dn:2,3 " .
            " --keyword=_dnpgettext:2c,3,4" .
            " --keyword=_dnp:2c,3,4" .
            " --keyword=_dpgettext:2c,3" .
            " --keyword=_dp:2c,3 " .
            " --keyword=_gettext" .
            " --keyword=__" .
            " --keyword=_ngettext:1,2" .
            " --keyword=_n:1,2 " .
            " --keyword=_npgettext:1c,2,3" .
            " --keyword=_np:1c,2,3" .
            " --keyword=_pgettext:1c,2" .
            " --keyword=_p:1c,2" .
            " --package-name=$domain" .
            " --default-domain=$domain" .
            " --output=$potfile";

        foreach ($this->params as $param => $value) {
            if (str_starts_with('--', $param)) {
                $cmd .= ' ' . $param . ($value ? '=' . $value : '');
            } else {
                $cmd .= ' ' . trim($param . ' ' . ($value ?? ''));
            }
        }

        system($cmd);
    }

    public function run(): int
    {
        $this->checkShellCommand('xgettext');
        $this->validateDir($this->dir);
        $this->validateDomain($this->domain);

        $joinExisting = false;
        $potfile = $this->dir . '/' . $this->domain . '.pot';

        foreach ($this->sources as $source) {
            echo 'Extracting ' . $source->glob . ' from ' . $source->dir . "\n";
            $this->validateDir($source->dir);

            $this->extract(
                $source->dir,
                $source->glob,
                $source->language,
                $this->domain,
                $potfile,
                $joinExisting,
            );

            if (is_file($potfile)) {
                $joinExisting = true;
            }
        }

        return 0;
    }
}
