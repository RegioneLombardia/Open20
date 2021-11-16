<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter;

use Imagine\Image\ImageInterface;

/**
 * Interface for imagine filters.
 */
interface FilterInterface
{
    /**
     * Applies scheduled transformation to an ImageInterface instance.
     *
     * @param \Imagine\Image\ImageInterface $image
     *
     * @return \Imagine\Image\ImageInterface returns the processed ImageInterface instance
     */
    public function apply(ImageInterface $image);
}
