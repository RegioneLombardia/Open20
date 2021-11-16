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

/**
 * A resize filter.
 */
class Resize implements FilterInterface
{
    /**
     * @var \Imagine\Image\BoxInterface
     */
    private $size;

    /**
     * @var string
     */
    private $filter;

    /**
     * Constructs Resize filter with given width and height.
     *
     * @param \Imagine\Image\BoxInterface $size
     * @param string $filter
     */
    public function __construct(BoxInterface $size, $filter = ImageInterface::FILTER_UNDEFINED)
    {
        $this->size = $size;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        return $image->resize($this->size, $this->filter);
    }
}
