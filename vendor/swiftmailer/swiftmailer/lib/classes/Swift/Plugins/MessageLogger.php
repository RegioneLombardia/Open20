<?php

/*
 * (c) 2011 Fabien Potencier
 *
 */

/**
 * Stores all sent emails for further usage.
 *
 */
class Swift_Plugins_MessageLogger implements Swift_Events_SendListener
{
    /**
     * @var Swift_Mime_SimpleMessage[]
     */
    private $messages;

    public function __construct()
    {
        $this->messages = [];
    }

    /**
     * Get the message list.
     *
     * @return Swift_Mime_SimpleMessage[]
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get the message count.
     *
     * @return int count
     */
    public function countMessages()
    {
        return \count($this->messages);
    }

    /**
     * Empty the message list.
     */
    public function clear()
    {
        $this->messages = [];
    }

    /**
     * Invoked immediately before the Message is sent.
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        $this->messages[] = clone $evt->getMessage();
    }

    /**
     * Invoked immediately after the Message is sent.
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
    }
}
