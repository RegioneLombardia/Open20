<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Comparator\NumberComparator;

/**
 * SizeRangeFilterIterator filters out files that are not in the given size range.
 *
 *
 * @extends \FilterIterator<string, \SplFileInfo>
 */
class SizeRangeFilterIterator extends \FilterIterator
{
    private $comparators = [];

    /**
     * @param \Iterator<string, \SplFileInfo> $iterator
     * @param NumberComparator[]              $comparators
     */
    public function __construct(\Iterator $iterator, array $comparators)
    {
        $this->comparators = $comparators;

        parent::__construct($iterator);
    }

    /**
     * Filters the iterator values.
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function accept()
    {
        $fileinfo = $this->current();
        if (!$fileinfo->isFile()) {
            return true;
        }

        $filesize = $fileinfo->getSize();
        foreach ($this->comparators as $compare) {
            if (!$compare->test($filesize)) {
                return false;
            }
        }

        return true;
    }
}
