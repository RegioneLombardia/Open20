<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Listens for Messages being sent from within the Transport system.
 *
 */
interface Swift_Events_SendListener extends Swift_Events_EventListener
{
    /**
     * Invoked immediately before the Message is sent.
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt);

    /**
     * Invoked immediately after the Message is sent.
     */
    public function sendPerformed(Swift_Events_SendEvent $evt);
}
