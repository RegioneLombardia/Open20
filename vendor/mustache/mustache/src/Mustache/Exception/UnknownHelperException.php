<?php

/*
 *
 * (c) 2010-2017 Justin Hileman
 *
 */

/**
 * Unknown helper exception.
 */
class Mustache_Exception_UnknownHelperException extends InvalidArgumentException implements Mustache_Exception
{
    protected $helperName;

    /**
     * @param string    $helperName
     * @param Exception $previous
     */
    public function __construct($helperName, Exception $previous = null)
    {
        $this->helperName = $helperName;
        $message = sprintf('Unknown helper: %s', $helperName);
        if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
            parent::__construct($message, 0, $previous);
        } else {
            parent::__construct($message); // @codeCoverageIgnore
        }
    }

    public function getHelperName()
    {
        return $this->helperName;
    }
}
