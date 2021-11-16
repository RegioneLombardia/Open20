<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Processes bytes as they pass through a stream and performs filtering.
 *
 */
interface Swift_StreamFilter
{
    /**
     * Based on the buffer given, this returns true if more buffering is needed.
     *
     * @param mixed $buffer
     *
     * @return bool
     */
    public function shouldBuffer($buffer);

    /**
     * Filters $buffer and returns the changes.
     *
     * @param mixed $buffer
     *
     * @return mixed
     */
    public function filter($buffer);
}
