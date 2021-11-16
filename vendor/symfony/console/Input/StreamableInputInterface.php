<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Input;

/**
 * StreamableInputInterface is the interface implemented by all input classes
 * that have an input stream.
 *
 */
interface StreamableInputInterface extends InputInterface
{
    /**
     * Sets the input stream to read from when interacting with the user.
     *
     * This is mainly useful for testing purpose.
     *
     * @param resource $stream The input stream
     */
    public function setStream($stream);

    /**
     * Returns the input stream.
     *
     * @return resource|null
     */
    public function getStream();
}
