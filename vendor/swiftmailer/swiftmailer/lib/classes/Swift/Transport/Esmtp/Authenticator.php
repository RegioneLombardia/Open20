<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * An Authentication mechanism.
 *
 */
interface Swift_Transport_Esmtp_Authenticator
{
    /**
     * Get the name of the AUTH mechanism this Authenticator handles.
     *
     * @return string
     */
    public function getAuthKeyword();

    /**
     * Try to authenticate the user with $username and $password.
     *
     * @param string $username
     * @param string $password
     *
     * @return bool true if authentication worked (returning false is deprecated, throw a Swift_TransportException instead)
     *
     * @throws Swift_TransportException Allows the message to bubble up when authentication was not successful
     */
    public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password);
}
