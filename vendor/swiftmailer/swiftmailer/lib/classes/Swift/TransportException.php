<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * TransportException thrown when an error occurs in the Transport subsystem.
 *
 */
class Swift_TransportException extends Swift_IoException
{
    /**
     * Create a new TransportException with $message.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
