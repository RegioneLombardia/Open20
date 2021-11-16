<?php
/**
 */

namespace Zend\Http\Client\Adapter\Exception;

class TimeoutException extends RuntimeException implements ExceptionInterface
{
    const READ_TIMEOUT = 1000;
}
