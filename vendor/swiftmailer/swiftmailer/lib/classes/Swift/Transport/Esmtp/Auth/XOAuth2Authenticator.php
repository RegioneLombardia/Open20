<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Handles XOAUTH2 authentication.
 *
 * Example:
 * <code>
 * $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
 *   ->setAuthMode('XOAUTH2')
 *   ->setUsername('YOUR_EMAIL_ADDRESS')
 *   ->setPassword('YOUR_ACCESS_TOKEN');
 * </code>
 *
 *
 */
class Swift_Transport_Esmtp_Auth_XOAuth2Authenticator implements Swift_Transport_Esmtp_Authenticator
{
    /**
     * Get the name of the AUTH mechanism this Authenticator handles.
     *
     * @return string
     */
    public function getAuthKeyword()
    {
        return 'XOAUTH2';
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(Swift_Transport_SmtpAgent $agent, $email, $token)
    {
        try {
            $param = $this->constructXOAuth2Params($email, $token);
            $agent->executeCommand('AUTH XOAUTH2 '.$param."\r\n", [235]);

            return true;
        } catch (Swift_TransportException $e) {
            $agent->executeCommand("RSET\r\n", [250]);

            throw $e;
        }
    }

    /**
     * Construct the auth parameter.
     *
     */
    protected function constructXOAuth2Params($email, $token)
    {
        return base64_encode("user=$email\1auth=Bearer $token\1\1");
    }
}
