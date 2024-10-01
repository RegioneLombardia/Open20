<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Formatter;

/**
 * Formatter style interface for defining styles.
 *
 */
interface OutputFormatterStyleInterface
{
    /**
     * Sets style foreground color.
     */
    public function setForeground(?string $color = null);

    /**
     * Sets style background color.
     */
    public function setBackground(?string $color = null);

    /**
     * Sets some specific style option.
     */
    public function setOption(string $option);

    /**
     * Unsets some specific style option.
     */
    public function unsetOption(string $option);

    /**
     * Sets multiple style options at once.
     */
    public function setOptions(array $options);

    /**
     * Applies the style to a given text.
     *
     * @return string
     */
    public function apply(string $text);
}
