<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Output;

/**
 */
class BufferedOutput extends Output
{
    private $buffer = '';

    /**
     * Empties buffer and returns its content.
     *
     * @return string
     */
    public function fetch()
    {
        $content = $this->buffer;
        $this->buffer = '';

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline)
    {
        $this->buffer .= $message;

        if ($newline) {
            $this->buffer .= \PHP_EOL;
        }
    }
}
