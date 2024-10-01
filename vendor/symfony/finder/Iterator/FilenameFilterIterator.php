<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Glob;

/**
 * FilenameFilterIterator filters files by patterns (a regexp, a glob, or a string).
 *
 *
 * @extends MultiplePcreFilterIterator<string, \SplFileInfo>
 */
class FilenameFilterIterator extends MultiplePcreFilterIterator
{
    /**
     * Filters the iterator values.
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function accept()
    {
        return $this->isAccepted($this->current()->getFilename());
    }

    /**
     * Converts glob to regexp.
     *
     * PCRE patterns are left unchanged.
     * Glob strings are transformed with Glob::toRegex().
     *
     * @param string $str Pattern: glob or regexp
     *
     * @return string
     */
    protected function toRegex(string $str)
    {
        return $this->isRegex($str) ? $str : Glob::toRegex($str);
    }
}
