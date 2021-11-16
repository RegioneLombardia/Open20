<?php

/*
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 */

namespace Imagine\Image\Metadata;

/**
 * The container of the data extracted from metadata.
 */
class MetadataBag implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Returns the metadata key, default value if it does not exist.
     *
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Returns metadata as an associative array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}
