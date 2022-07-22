<?php

declare(strict_types=1);

namespace Conia\I18n;

use RuntimeException;
use ValueError;

trait Validates
{
    public function validateDir(string $dir): void
    {
        if (!is_dir($dir)) {
            throw new ValueError("Directory does not exist:\n  $dir");
        }
    }

    public function validateDomain(string $domain): void
    {
        if (!preg_match('/^[\w-]+$/', $domain)) {
            throw new ValueError('Invalid domain format');
        }
    }

    public function checkShellCommand(string $command): void
    {
        if (shell_exec("which $command") === null) {
            throw new RuntimeException("Command not found: $command");
        }
    }
}
