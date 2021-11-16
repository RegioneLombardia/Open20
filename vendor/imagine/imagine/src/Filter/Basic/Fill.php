<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Filter\Basic;

use Imagine\Filter\FilterInterface;
use Imagine\Image\Fill\FillInterface;
use Imagine\Image\ImageInterface;

/**
 * A fill filter.
 */
class Fill implements FilterInterface
{
    /**
     * @var \Imagine\Image\Fill\FillInterface
     */
    private $fill;

    /**
     * @param \Imagine\Image\Fill\FillInterface $fill
     */
    public function __construct(FillInterface $fill)
    {
        $this->fill = $fill;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        return $image->fill($this->fill);
    }
}
