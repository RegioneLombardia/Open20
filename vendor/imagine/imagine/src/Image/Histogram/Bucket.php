<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Histogram;

/**
 * Bucket histogram.
 */
final class Bucket implements \Countable
{
    /**
     * @var \Imagine\Image\Histogram\Range
     */
    private $range;

    /**
     * @var int
     */
    private $count;

    /**
     * @param \Imagine\Image\Histogram\Range $range
     * @param int $count
     */
    public function __construct(Range $range, $count = 0)
    {
        $this->range = $range;
        $this->count = $count;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function add($value)
    {
        if ($this->range->contains($value)) {
            $this->count++;
        }

        return $this;
    }

    /**
     * Get the number of elements in the bucket.
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return $this->count;
    }
}
