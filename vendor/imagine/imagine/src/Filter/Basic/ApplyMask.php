<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter\Basic;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;

/**
 * An apply mask filter.
 */
class ApplyMask implements FilterInterface
{
    /**
     * @var \Imagine\Image\ImageInterface
     */
    private $mask;

    /**
     * Initialize the instance.
     *
     * @param \Imagine\Image\ImageInterface $mask
     */
    public function __construct(ImageInterface $mask)
    {
        $this->mask = $mask;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        return $image->applyMask($this->mask);
    }
}
