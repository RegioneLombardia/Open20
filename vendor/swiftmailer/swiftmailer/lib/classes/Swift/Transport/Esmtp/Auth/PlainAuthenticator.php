<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Handles PLAIN authentication.
 *
 */
class Swift_Transport_Esmtp_Auth_PlainAuthenticator implements Swift_Transport_Esmtp_Authenticator
{
    /**
     * Get the name of the AUTH mechanism this Authenticator handles.
     *
     * @return string
     */
    public function getAuthKeyword()
    {
        return 'PLAIN';
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password)
    {
        try {
            $message = base64_encode($username.\chr(0).$username.\chr(0).$password);
            $agent->executeCommand(sprintf("AUTH PLAIN %s\r\n", $message), [235]);

            return true;
        } catch (Swift_TransportException $e) {
            $agent->executeCommand("RSET\r\n", [250]);

            throw $e;
        }
    }
}
