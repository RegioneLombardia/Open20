<?php

/*
 *
 * (c) 2010-2017 Justin Hileman
 *
 */

/**
 * Mustache syntax exception.
 */
class Mustache_Exception_SyntaxException extends LogicException implements Mustache_Exception
{
    protected $token;

    /**
     * @param string    $msg
     * @param array     $token
     * @param Exception $previous
     */
    public function __construct($msg, array $token, Exception $previous = null)
    {
        $this->token = $token;
        if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
            parent::__construct($msg, 0, $previous);
        } else {
            parent::__construct($msg); // @codeCoverageIgnore
        }
    }

    /**
     * @return array
     */
    public function getToken()
    {
        return $this->token;
    }
}
