<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * An abstract means of reading data.
 *
 * Classes implementing this interface may use a subsystem which requires less
 * memory than working with large strings of data.
 *
 */
interface Swift_OutputByteStream
{
    /**
     * Reads $length bytes from the stream into a string and moves the pointer
     * through the stream by $length.
     *
     * If less bytes exist than are requested the remaining bytes are given instead.
     * If no bytes are remaining at all, boolean false is returned.
     *
     * @param int $length
     *
     * @throws Swift_IoException
     *
     * @return string|bool
     */
    public function read($length);

    /**
     * Move the internal read pointer to $byteOffset in the stream.
     *
     * @param int $byteOffset
     *
     * @throws Swift_IoException
     *
     * @return bool
     */
    public function setReadPointer($byteOffset);
}
