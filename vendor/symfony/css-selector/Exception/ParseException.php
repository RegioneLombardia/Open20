<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Exception;

/**
 * ParseException is thrown when a CSS selector syntax is not valid.
 *
 * This component is a port of the Python cssselect library,
 *
 */
class ParseException extends \Exception implements ExceptionInterface
{
}
