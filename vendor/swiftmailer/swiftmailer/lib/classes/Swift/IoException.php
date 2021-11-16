<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * I/O Exception class.
 *
 */
class Swift_IoException extends Swift_SwiftException
{
    /**
     * Create a new IoException with $message.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
