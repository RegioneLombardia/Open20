<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Interface for all Encoder schemes.
 *
 */
interface Swift_Encoder extends Swift_Mime_CharsetObserver
{
    /**
     * Encode a given string to produce an encoded string.
     *
     * @param string $string
     * @param int    $firstLineOffset if first line needs to be shorter
     * @param int    $maxLineLength   - 0 indicates the default length for this encoding
     *
     * @return string
     */
    public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0);
}
