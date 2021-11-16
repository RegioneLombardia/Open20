<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 */

namespace Zend\Feed\Writer\Exception;

use Zend\Feed\Exception;

/**
 * Feed exceptions
 *
 * Class to represent exceptions that occur during Feed operations.
 */
class InvalidArgumentException extends Exception\InvalidArgumentException implements ExceptionInterface
{
}
