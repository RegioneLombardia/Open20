<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Formatter;

/**
 */
final class NullOutputFormatterStyle implements OutputFormatterStyleInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(string $text): string
    {
        return $text;
    }

    /**
     * {@inheritdoc}
     */
    public function setBackground(?string $color = null): void
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function setForeground(?string $color = null): void
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function setOption(string $option): void
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options): void
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function unsetOption(string $option): void
    {
        // do nothing
    }
}
