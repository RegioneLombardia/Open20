<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * DependencyException gets thrown when a requested dependency is missing.
 *
 */
class Swift_DependencyException extends Swift_SwiftException
{
    /**
     * Create a new DependencyException with $message.
     *
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
