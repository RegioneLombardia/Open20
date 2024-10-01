<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Listens for Transports to send commands to the server.
 *
 */
interface Swift_Events_CommandListener extends Swift_Events_EventListener
{
    /**
     * Invoked immediately following a command being sent.
     */
    public function commandSent(Swift_Events_CommandEvent $evt);
}
