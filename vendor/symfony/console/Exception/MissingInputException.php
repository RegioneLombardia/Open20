<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Exception;

/**
 * Represents failure to read input from stdin.
 *
 */
class MissingInputException extends RuntimeException implements ExceptionInterface
{
}
