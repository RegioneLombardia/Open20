<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter\Advanced;

use Imagine\Exception\InvalidArgumentException;
use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

/**
 * The OnPixelBased takes a callable, and for each pixel, this callable is called with the
 * image (\Imagine\Image\ImageInterface) and the current point (\Imagine\Image\Point).
 */
class OnPixelBased implements FilterInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * Initialize the instance.
     *
     * @param callable $callback
     *
     * @throws \Imagine\Exception\InvalidArgumentException
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('$callback has to be callable');
        }

        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        $size = $image->getSize();
        $w = $size->getWidth();
        $h = $size->getHeight();
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                call_user_func($this->callback, $image, new Point($x, $y));
            }
        }

        return $image;
    }
}
