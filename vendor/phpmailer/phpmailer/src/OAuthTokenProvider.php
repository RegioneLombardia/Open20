<?php

/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5.
 *
 *
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace PHPMailer\PHPMailer;

/**
 * OAuthTokenProvider - OAuth2 token provider interface.
 * Provides base64 encoded OAuth2 auth strings for SMTP authentication.
 *
 *
 */
interface OAuthTokenProvider
{
    /**
     * Generate a base64-encoded OAuth token ensuring that the access token has not expired.
     * The string to be base 64 encoded should be in the form:
     * "user=<user_email_address>\001auth=Bearer <access_token>\001\001"
     *
     * @return string
     */
    public function getOauth64();
}
