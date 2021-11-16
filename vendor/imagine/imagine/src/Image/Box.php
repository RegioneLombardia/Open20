<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image;

use Imagine\Exception\InvalidArgumentException;

/**
 * A box implementation.
 */
final class Box implements BoxInterface
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Constructs the Size with given width and height.
     *
     * @param int $width
     * @param int $height
     *
     * @throws \Imagine\Exception\InvalidArgumentException
     */
    public function __construct($width, $height)
    {
        if (!\is_int($width)) {
            $width = (int) round($width);
        }
        if (!\is_int($height)) {
            $height = (int) round($height);
        }
        $this->width = $width;
        $this->height = $height;
        if ($this->width < 1 || $this->height < 1) {
            throw new InvalidArgumentException(sprintf('Length of either side cannot be 0 or negative, current size is %sx%s', $width, $height));
        }
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function scale($ratio)
    {
        $width = max(1, round($ratio * $this->width));
        $height = max(1, round($ratio * $this->height));

        return new self($width, $height);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function increase($size)
    {
        return new self((int) $size + $this->width, (int) $size + $this->height);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function contains(BoxInterface $box, PointInterface $start = null)
    {
        $start = $start ? $start : new Point(0, 0);

        return $start->in($this) && $this->width >= $box->getWidth() + $start->getX() && $this->height >= $box->getHeight() + $start->getY();
    }

    /**
     * {@inheritdoc}
     *
     */
    public function square()
    {
        return $this->width * $this->height;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function __toString()
    {
        return sprintf('%dx%d px', $this->width, $this->height);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function widen($width)
    {
        return $this->scale($width / $this->width);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function heighten($height)
    {
        return $this->scale($height / $this->height);
    }
}
