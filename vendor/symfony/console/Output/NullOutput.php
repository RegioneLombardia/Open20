<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Output;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * NullOutput suppresses all output.
 *
 *     $output = new NullOutput();
 *
 */
class NullOutput implements OutputInterface
{
    /**
     * {@inheritdoc}
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatter()
    {
        // to comply with the interface we must return a OutputFormatterInterface
        return new OutputFormatter();
    }

    /**
     * {@inheritdoc}
     */
    public function setDecorated($decorated)
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function isDecorated()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setVerbosity($level)
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function getVerbosity()
    {
        return self::VERBOSITY_QUIET;
    }

    /**
     * {@inheritdoc}
     */
    public function isQuiet()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isVerbose()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isVeryVerbose()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isDebug()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($messages, $options = self::OUTPUT_NORMAL)
    {
        // do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
    {
        // do nothing
    }
}
