<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * An OutputByteStream which specifically reads from a file.
 *
 */
interface Swift_FileStream extends Swift_OutputByteStream
{
    /**
     * Get the complete path to the file.
     *
     * @return string
     */
    public function getPath();
}
