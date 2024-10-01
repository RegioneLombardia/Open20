<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Gd;

use Imagine\Driver\InfoProvider;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Exception\NotSupportedException;
use Imagine\Exception\RuntimeException;
use Imagine\Factory\ClassFactoryInterface;
use Imagine\Image\AbstractLayers;
use Imagine\Image\Metadata\MetadataBag;
use Imagine\Image\Palette\PaletteInterface;

class Layers extends AbstractLayers implements InfoProvider
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
     * @since 1.3.0
     */
    public static function getDriverInfo($required = true)
    {
        return DriverInfo::get($required);
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
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->getClassFactory()->createImage(ClassFactoryInterface::HANDLE_GD, $this->resource, $this->palette, new MetadataBag());
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->offset;
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->offset < 1;
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $offset === 0;
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if ($offset === 0) {
            return $this->getClassFactory()->createImage(ClassFactoryInterface::HANDLE_GD, $this->resource, $this->palette, new MetadataBag());
        }

        throw new InvalidArgumentException('GD only supports one layer at offset 0');
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new NotSupportedException('GD does not support layer set');
    }

    /**
     * {@inheritdoc}
     *
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new NotSupportedException('GD does not support layer unset');
    }
}
