<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Gd;

use Imagine\Exception\NotSupportedException;
use Imagine\Exception\RuntimeException;
use Imagine\Factory\ClassFactoryInterface;
use Imagine\Image\AbstractLayers;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Palette\PaletteInterface;

class Layers extends AbstractLayers
{
    /**
     * @var \Imagine\Gd\Image
     */
    private $image;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var resource|\GdImage
     */
    private $resource;

    /**
     * @var \Imagine\Image\Palette\PaletteInterface
     */
    private $palette;

    /**
     * @param \Imagine\Gd\Image $image
     * @param \Imagine\Image\Palette\PaletteInterface $palette
     * @param resource|\GdImage $resource
     * @param int $initialOffset
     *
     * @throws \Imagine\Exception\RuntimeException
     */
    public function __construct(Image $image, PaletteInterface $palette, $resource, $initialOffset = 0)
    {
        if (!$resource instanceof \GdImage && !is_resource($resource)) {
            throw new RuntimeException('Invalid Gd resource provided');
        }

        $this->image = $image;
        $this->resource = $resource;
        $this->offset = (int) $initialOffset;
        $this->palette = $palette;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function merge()
    {
    }

    /**
     * {@inheritdoc}
     *
     */
    public function coalesce()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function animate($format, $delay, $loops)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function current()
    {
        return $this->getClassFactory()->createImage(ClassFactoryInterface::HANDLE_GD, $this->resource, $this->palette, new MetadataBag());
    }

    /**
     * {@inheritdoc}
     *
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function next()
    {
        ++$this->offset;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function valid()
    {
        return $this->offset < 1;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function count()
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetExists($offset)
    {
        return 0 === $offset;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetGet($offset)
    {
        if (0 === $offset) {
            return $this->getClassFactory()->createImage(ClassFactoryInterface::HANDLE_GD, $this->resource, $this->palette, new MetadataBag());
        }

        throw new RuntimeException('GD only supports one layer at offset 0');
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetSet($offset, $value)
    {
        throw new NotSupportedException('GD does not support layer set');
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetUnset($offset)
    {
        throw new NotSupportedException('GD does not support layer unset');
    }
}
