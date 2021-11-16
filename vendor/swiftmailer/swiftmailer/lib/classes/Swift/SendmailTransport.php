<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * SendmailTransport for sending mail through a Sendmail/Postfix (etc..) binary.
 *
 */
class Swift_SendmailTransport extends Swift_Transport_SendmailTransport
{
    /**
     * Create a new SendmailTransport, optionally using $command for sending.
     *
     * @param string $command
     */
    public function __construct($command = '/usr/sbin/sendmail -bs')
    {
        \call_user_func_array(
            [$this, 'Swift_Transport_SendmailTransport::__construct'],
            Swift_DependencyContainer::getInstance()
                ->createDependenciesFor('transport.sendmail')
            );

        $this->setCommand($command);
    }
}
