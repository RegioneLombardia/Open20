<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Comparator\DateComparator;

/**
 * DateRangeFilterIterator filters out files that are not in the given date range (last modified dates).
 *
 *
 * @extends \FilterIterator<string, \SplFileInfo>
 */
class DateRangeFilterIterator extends \FilterIterator
{
    private $comparators = [];

    /**
     * @param \Iterator<string, \SplFileInfo> $iterator
     * @param DateComparator[]                $comparators
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

        if (!file_exists($fileinfo->getPathname())) {
            return false;
        }

        $filedate = $fileinfo->getMTime();
        foreach ($this->comparators as $compare) {
            if (!$compare->test($filedate)) {
                return false;
            }
        }

        return true;
    }
}
