<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Filesystem\Exception;

/**
 * Exception class thrown when a filesystem operation failure happens.
 *
 */
class IOException extends \RuntimeException implements IOExceptionInterface
{
    private $path;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null, ?string $path = null)
    {
        $this->path = $path;

        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }
}
