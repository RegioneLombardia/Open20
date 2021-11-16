<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Output;

/**
 * ConsoleOutputInterface is the interface implemented by ConsoleOutput class.
 * This adds information about stderr output stream.
 *
 */
interface ConsoleOutputInterface extends OutputInterface
{
    /**
     * Gets the OutputInterface for errors.
     *
     * @return OutputInterface
     */
    public function getErrorOutput();

    public function setErrorOutput(OutputInterface $error);
}
