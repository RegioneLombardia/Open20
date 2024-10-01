<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Formatter;

/**
 */
final class NullOutputFormatter implements OutputFormatterInterface
{
    private $style;

    /**
     * {@inheritdoc}
     */
    public function format(?string $message): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getStyle(string $name): OutputFormatterStyleInterface
    {
        // to comply with the interface we must return a OutputFormatterStyleInterface
        return $this->style ?? $this->style = new NullOutputFormatterStyle();
    }

    /**
     * {@inheritdoc}
     */
    public function hasStyle(string $name): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isDecorated(): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated(bool $decorated): void
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function setStyle(string $name, OutputFormatterStyleInterface $style): void
    {
        // do nothing
    }
}
