<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\String\Inflector;

interface InflectorInterface
{
    /**
     * Returns the singular forms of a string.
     *
     * If the method can't determine the form with certainty, several possible singulars are returned.
     *
     * @return string[]
     */
    public function singularize(string $plural): array;

    /**
     * Returns the plural forms of a string.
     *
     * If the method can't determine the form with certainty, several possible plurals are returned.
     *
     * @return string[]
     */
    public function pluralize(string $singular): array;
}
