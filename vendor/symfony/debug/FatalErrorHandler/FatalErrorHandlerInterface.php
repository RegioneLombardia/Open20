<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Debug\FatalErrorHandler;

use Symfony\Component\Debug\Exception\FatalErrorException;

/**
 * Attempts to convert fatal errors to exceptions.
 *
 */
interface FatalErrorHandlerInterface
{
    /**
     * Attempts to convert an error into an exception.
     *
     * @param array               $error     An array as returned by error_get_last()
     * @param FatalErrorException $exception A FatalErrorException instance
     *
     * @return FatalErrorException|null A FatalErrorException instance if the class is able to convert the error, null otherwise
     */
    public function handleError(array $error, FatalErrorException $exception);
}
