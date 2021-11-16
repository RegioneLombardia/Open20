<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * RFC Compliance Exception class.
 *
 */
class Swift_RfcComplianceException extends Swift_SwiftException
{
    /**
     * Create a new RfcComplianceException with $message.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
