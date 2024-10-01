<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Iterator;

/**
 * FilecontentFilterIterator filters files by their contents using patterns (regexps or strings).
 *
 *
 * @extends MultiplePcreFilterIterator<string, \SplFileInfo>
 */
class FilecontentFilterIterator extends MultiplePcreFilterIterator
{
    /**
     * Filters the iterator values.
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function accept()
    {
        if (!$this->matchRegexps && !$this->noMatchRegexps) {
            return true;
        }

        $fileinfo = $this->current();

        if ($fileinfo->isDir() || !$fileinfo->isReadable()) {
            return false;
        }

        $content = $fileinfo->getContents();
        if (!$content) {
            return false;
        }

        return $this->isAccepted($content);
    }

    /**
     * Converts string to regexp if necessary.
     *
     * @param string $str Pattern: string or regexp
     *
     * @return string
     */
    protected function toRegex(string $str)
    {
        return $this->isRegex($str) ? $str : '/'.preg_quote($str, '/').'/';
    }
}
