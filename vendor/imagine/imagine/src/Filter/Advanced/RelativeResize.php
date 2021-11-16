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

/**
 * The RelativeResize filter allows images to be resized relative to their existing dimensions.
 */
class RelativeResize implements FilterInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var mixed
     */
    private $parameter;

    /**
     * Constructs a RelativeResize filter with the given method and argument.
     *
     * @param string $method BoxInterface method
     * @param mixed $parameter Parameter for BoxInterface method
     */
    public function __construct($method, $parameter)
    {
        if (!in_array($method, array('heighten', 'increase', 'scale', 'widen'))) {
            throw new InvalidArgumentException(sprintf('Unsupported method: ', $method));
        }

        $this->method = $method;
        $this->parameter = $parameter;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function apply(ImageInterface $image)
    {
        return $image->resize(call_user_func(array($image->getSize(), $this->method), $this->parameter));
    }
}
