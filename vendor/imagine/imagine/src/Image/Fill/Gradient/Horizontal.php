<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Fill\Gradient;

use Imagine\Image\PointInterface;

/**
 * Horizontal gradient fill.
 */
final class Horizontal extends Linear
{
    /**
     * {@inheritdoc}
     *
     */
    public function getDistance(PointInterface $position)
    {
        return $position->getX();
    }
}
