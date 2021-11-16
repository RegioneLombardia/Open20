<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter\Advanced;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

/**
 * The Grayscale filter calculates the gray-value based on RGB.
 */
class Grayscale extends OnPixelBased implements FilterInterface
{
    public function __construct()
    {
        parent::__construct(function (ImageInterface $image, Point $point) {
            $color = $image->getColorAt($point);
            $image->draw()->dot($point, $color->grayscale());
        });
    }
}
