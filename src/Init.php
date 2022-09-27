<?php

declare(strict_types=1);

namespace Conia\I18n;

use Conia\Cli\Command;
use Conia\Cli\Opts;
use RuntimeException;

class Init extends Command
{
    use Validates;

    protected string $name = 'init';
    protected string $group = 'Internationalization/gettext';
    protected string $prefix = 'i18n';
    protected string $description = 'Initializes a new translation catalog based on a POT template';

    public function __construct(
        protected readonly string $dir,
        protected readonly string $domain,
        protected readonly array $params,
    ) {
    }

    protected function getPotFile(): string
    {
        $potfile = $this->dir . '/' . $this->domain . '.pot';

        if (is_file($potfile)) {
            return $potfile;
        }

        throw new RuntimeException(
            'The *.pot file is missing. You need to run the i18n:extract ' .
                'command before initializing a catalog'
        );
    }

    public function run(): int
    {
        $this->checkShellCommand('msginit');
        $this->validateDir($this->dir);
        $this->validateDomain($this->domain);

        $dir = $this->dir;
        $domain = $this->domain;
        $potfile = $this->getPotFile();

        $opts = new Opts();
        $locale = $opts->get('-l', $opts->get('--locale', ''));

        if (empty($locale)) {
            // Would stop the test suit and wait for input
            // @codeCoverageIgnoreStart
            $locale = readline('Enter new locale (e. g. en, de_DE): ');
            // @codeCoverageIgnoreEnd
        }


        $messages = "$dir/$locale/LC_MESSAGES";
        $cmd = "msginit " .
            " --locale=$locale" .
            " --input=$potfile" .
            " --output-file=$messages/$domain.po";

        foreach ($this->params as $param => $value) {
            if (str_starts_with('--', $param)) {
                $cmd .= ' ' . $param . ($value ? '=' . $value : '');
            } else {
                $cmd .= ' ' . trim($param . ' ' . ($value ?? ''));
            }
        }

        system("mkdir -p $messages");
        system($cmd);

        return 1;
    }

    public function help(): void
    {
        $this->helpHeader(withOptions: true);
        $this->helpOption('-l <locale>, --locale <locale>', 'Sets the target locale');
    }
}
