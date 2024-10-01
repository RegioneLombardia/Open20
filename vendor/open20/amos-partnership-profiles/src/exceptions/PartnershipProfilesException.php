<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\exceptions
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\exceptions;

/**
 * Class PartnershipProfilesException
 * @package open20\amos\partnershipprofiles\exceptions
 */
class PartnershipProfilesException extends \Exception
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
