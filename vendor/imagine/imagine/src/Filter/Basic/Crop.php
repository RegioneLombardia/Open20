<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter\Basic;

use Imagine\Filter\FilterInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\PointInterface;

/**
 * A crop filter.
 */
class Crop implements FilterInterface
{
    /**
     * @var \Imagine\Image\PointInterface
     */
    private $start;

    /**
     * @var \Imagine\Image\BoxInterface
     */
    private $size;

    /**
     * Constructs a Crop filter with given x, y, coordinates and crop width and height values.
     *
     * @param \Imagine\Image\PointInterface $start
     * @param \Imagine\Image\BoxInterface $size
     */
    public function __construct(PointInterface $start, BoxInterface $size)
    {
        $this->start = $start;
        $this->size = $size;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        return $image->crop($this->start, $this->size);
    }
}
