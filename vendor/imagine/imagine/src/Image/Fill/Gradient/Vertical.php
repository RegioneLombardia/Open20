<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Fill\Gradient;

use Imagine\Image\PointInterface;

/**
 * Vertical gradient fill.
 */
final class Vertical extends Linear
{
    /**
     * {@inheritdoc}
     *
     */
    public function getDistance(PointInterface $position)
    {
        return $position->getY();
    }
}
