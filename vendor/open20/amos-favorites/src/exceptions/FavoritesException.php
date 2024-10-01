<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\favorites\exceptions
 * @category   CategoryName
 */

namespace open20\amos\favorites\exceptions;

/**
 * Class FavoritesException
 * @package open20\amos\favorites\exceptions
 */
class FavoritesException extends \Exception
{
    /**
     * 
     * @param type $message
     * @param type $code
     * @param \Exception $previous
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * 
     * @return type
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n{$this->getFile()}:{$this->getLine()}\n";
    }
}
