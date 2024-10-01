<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Listens for responses from a remote SMTP server.
 *
 */
interface Swift_Events_ResponseListener extends Swift_Events_EventListener
{
    /**
     * Invoked immediately following a response coming back.
     */
    public function responseReceived(Swift_Events_ResponseEvent $evt);
}
