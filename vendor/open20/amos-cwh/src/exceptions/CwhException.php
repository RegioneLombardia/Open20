<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh\exceptions
 * @category   CategoryName
 */

namespace open20\amos\cwh\exceptions;

class CwhException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n{$this->getFile()}:{$this->getLine()}\n";
    }
}