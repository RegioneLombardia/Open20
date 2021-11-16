<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image;

/**
 * A point class that allows negative values of coordinates.
 */
final class PointSigned implements PointInterface
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * Constructs a point of coordinates.
     *
     * @param int $x
     * @param int $y
     *
     * @throws \Imagine\Exception\InvalidArgumentException
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function in(BoxInterface $box)
    {
        return 0 <= $this->x && $this->x < $box->getWidth() && 0 <= $this->y && $this->y < $box->getHeight();
    }

    /**
     * {@inheritdoc}
     *
     */
    public function move($amount)
    {
        return new self($this->x + $amount, $this->y + $amount);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function __toString()
    {
        return sprintf('(%d, %d)', $this->x, $this->y);
    }
}
