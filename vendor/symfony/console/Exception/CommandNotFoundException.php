<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Exception;

/**
 * Represents an incorrect command name typed in the console.
 *
 */
class CommandNotFoundException extends \InvalidArgumentException implements ExceptionInterface
{
    private $alternatives;

    /**
     * @param string          $message      Exception message to throw
     * @param string[]        $alternatives List of similar defined names
     * @param int             $code         Exception code
     * @param \Throwable|null $previous     Previous exception used for the exception chaining
     */
    public function __construct(string $message, array $alternatives = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->alternatives = $alternatives;
    }

    /**
     * @return string[]
     */
    public function getAlternatives()
    {
        return $this->alternatives;
    }
}
