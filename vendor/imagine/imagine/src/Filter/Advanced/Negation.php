<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter\Advanced;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;

/**
 * This filter negates every color of every pixel of an image.
 */
class Negation implements FilterInterface
{
    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        $image->effects()->negative();

        return $image;
    }
}
