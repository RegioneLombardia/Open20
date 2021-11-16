<?php
/**
 */

namespace Zend\Http\Client\Adapter;

/**
 * An interface description for Zend\Http\Client\Adapter\Stream classes.
 *
 * This interface describes Zend\Http\Client\Adapter which supports streaming.
 */
interface StreamInterface
{
    /**
     * Set output stream
     *
     * This function sets output stream where the result will be stored.
     *
     * @param resource $stream Stream to write the output to
     *
     */
    public function setOutputStream($stream);
}
