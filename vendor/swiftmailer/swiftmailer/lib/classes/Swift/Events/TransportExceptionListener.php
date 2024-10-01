<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Listens for Exceptions thrown from within the Transport system.
 *
 */
interface Swift_Events_TransportExceptionListener extends Swift_Events_EventListener
{
    /**
     * Invoked as a TransportException is thrown in the Transport system.
     */
    public function exceptionThrown(Swift_Events_TransportExceptionEvent $evt);
}
