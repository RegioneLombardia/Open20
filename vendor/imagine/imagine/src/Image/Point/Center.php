<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Point;

use Imagine\Image\BoxInterface;
use Imagine\Image\Point as OriginalPoint;
use Imagine\Image\PointInterface;

/**
 * Center point of a box.
 */
final class Center implements PointInterface
{
    /**
     * @var \Imagine\Image\BoxInterface
     */
    private $box;

    /**
     * Constructs coordinate with size instance, it needs to be relative to.
     *
     * @param \Imagine\Image\BoxInterface $box
     */
    public function __construct(BoxInterface $box)
    {
        $this->box = $box;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getX()
    {
        return ceil($this->box->getWidth() / 2);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getY()
    {
        return ceil($this->box->getHeight() / 2);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function in(BoxInterface $box)
    {
        return $this->getX() < $box->getWidth() && $this->getY() < $box->getHeight();
    }

    /**
     * {@inheritdoc}
     *
     */
    public function move($amount)
    {
        return new OriginalPoint($this->getX() + $amount, $this->getY() + $amount);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function __toString()
    {
        return sprintf('(%d, %d)', $this->getX(), $this->getY());
    }
}
