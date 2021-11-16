<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Base Exception class.
 *
 */
class Swift_SwiftException extends Exception
{
    /**
     * Create a new SwiftException with $message.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
