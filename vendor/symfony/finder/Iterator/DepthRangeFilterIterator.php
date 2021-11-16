<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Iterator;

/**
 * DepthRangeFilterIterator limits the directory depth.
 *
 */
class DepthRangeFilterIterator extends FilterIterator
{
    private $minDepth = 0;

    /**
     * @param \RecursiveIteratorIterator $iterator The Iterator to filter
     * @param int                        $minDepth The min depth
     * @param int                        $maxDepth The max depth
     */
    public function __construct(\RecursiveIteratorIterator $iterator, $minDepth = 0, $maxDepth = \PHP_INT_MAX)
    {
        $this->minDepth = $minDepth;
        $iterator->setMaxDepth(\PHP_INT_MAX === $maxDepth ? -1 : $maxDepth);

        parent::__construct($iterator);
    }

    /**
     * Filters the iterator values.
     *
     * @return bool true if the value should be kept, false otherwise
     */
    public function accept()
    {
        return $this->getInnerIterator()->getDepth() >= $this->minDepth;
    }
}
