<?php
/**
 */

namespace Zend\Http\Client\Adapter\Exception;

use Zend\Http\Client\Exception;

class OutOfRangeException extends Exception\OutOfRangeException implements
    ExceptionInterface
{
}
