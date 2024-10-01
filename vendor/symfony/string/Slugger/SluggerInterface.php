<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\String\Slugger;

use Symfony\Component\String\AbstractUnicodeString;

/**
 * Creates a URL-friendly slug from a given string.
 *
 */
interface SluggerInterface
{
    /**
     * Creates a slug for the given string and locale, using appropriate transliteration when needed.
     */
    public function slug(string $string, string $separator = '-', ?string $locale = null): AbstractUnicodeString;
}
