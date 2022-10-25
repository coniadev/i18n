<?php

declare(strict_types=1);

namespace Conia\I18n;

use UnexpectedValueException;

class Locale
{
    public readonly string $urlPrefix;
    public readonly array $domains;

    public function __construct(
        protected readonly Locales $locales,
        public readonly string $id,
        public readonly string $title,
        public readonly ?string $fallback = null,
        ?array $domains = null,
        ?string $urlPrefix = null,
    ) {
        if (!preg_match('/^[a-z]{2}-[A-Z]{2}$/', $id)) {
            throw new UnexpectedValueException(
                'Use a combination of ISO 639-1 language code and ' .
                    ' ISO 3166 country code separated by a dash as locale identifier, ' .
                    'e. g. en-GB or de-DE'
            );
        }

        if ($domains) {
            $this->domains = array_map(fn ($d) => strtolower($d), $domains);
        } else {
            $this->domains = [];
        }

        $this->urlPrefix = $urlPrefix ?: $id;
    }

    /**
     * The fallback locale is only used for content translations
     * stored in the database. Translations provided by gettext
     * e. g. in templates or source code do not work with fallback.
     */
    public function fallback(): ?Locale
    {
        return $this->fallback ? $this->locales->get($this->fallback) : null;
    }
}
