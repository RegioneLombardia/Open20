<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\exceptions
 * @category   CategoryName
 */

namespace amos\sitemanagement\exceptions;

/**
 * Class SiteManagementException
 * @package amos\sitemanagement\exceptions
 */
class SiteManagementException extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n{$this->getFile()}:{$this->getLine()}\n";
    }
}
