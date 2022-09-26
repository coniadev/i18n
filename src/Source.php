<?php

declare(strict_types=1);

namespace Conia\I18n;

class Source
{
    public function __construct(
        public readonly string $dir,
        public readonly string $glob,
        public readonly string $language,
    ) {
    }
}
