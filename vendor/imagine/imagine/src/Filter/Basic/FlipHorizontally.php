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
 * A "flip horizontally" filter.
 */
class FlipHorizontally implements FilterInterface
{
    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        return $image->flipHorizontally();
    }
}
