<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

namespace open20\amos\emailmanager\interfaces;

interface TransportInterface
{
    /**
     * Sends email message
     *
     * @param string $from format accepted:
     *
     *   1) 'example@example.com'
     *   2) 'example@example.com alias' the method considers the email address up to the first space, everything that follows is considered alias.
     *
     * @param array $to
     * @param string $subject
     * @param string $text
     * @param array $files
     * @param array $bcc
     * @return bool
     */
    public function send($from, $to, $subject, $text, $files = [], $bcc = null);


}