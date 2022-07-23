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
    ) {
    }

    protected function extract(
        string $srcdir,
        string $glob,
        string $type,
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
            ($join ? ' --join-existing' : '') .
            " --language=$type" .
            " --package-name=$domain" .
            " --default-domain=$domain" .
            " --output=$potfile";

        system($cmd);
    }

    public function run(): int
    {
        $this->checkShellCommand('xgettext');
        $this->validateDir($this->dir);
        $this->validateDomain($this->domain);

        $joinExisting = false;
        $potfile = $this->dir . '/' . $this->domain . '.pot';

        if (is_file($potfile)) {
            $joinExisting = true;
        }

        foreach ($this->sources as $source) {
            echo 'Extracting ' . $source->glob . ' from ' . $source->dir . "\n";
            $this->validateDir($source->dir);

            $this->extract(
                $source->dir,
                $source->glob,
                $source->type,
                $this->domain,
                $potfile,
                $joinExisting,
            );

            $joinExisting = true;
        }

        return 0;
    }
}
