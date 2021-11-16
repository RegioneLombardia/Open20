<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Fill;

use Imagine\Image\PointInterface;

/**
 * Interface for the fill.
 */
interface FillInterface
{
    /**
     * Gets color of the fill for the given position.
     *
     * @param \Imagine\Image\PointInterface $position
     *
     * @return \Imagine\Image\Palette\Color\ColorInterface
     */
    public function getColor(PointInterface $position);
}
