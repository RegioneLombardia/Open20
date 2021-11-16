<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Exception;

/**
 * Represents an incorrect option name typed in the console.
 *
 */
class InvalidOptionException extends \InvalidArgumentException implements ExceptionInterface
{
}
