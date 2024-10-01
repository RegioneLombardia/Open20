<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Process\Exception;

use Symfony\Component\Process\Process;

/**
 * Exception that is thrown when a process has been signaled.
 *
 */
final class ProcessSignaledException extends RuntimeException
{
    private $process;

    public function __construct(Process $process)
    {
        $this->process = $process;

        parent::__construct(sprintf('The process has been signaled with signal "%s".', $process->getTermSignal()));
    }

    public function getProcess(): Process
    {
        return $this->process;
    }

    public function getSignal(): int
    {
        return $this->getProcess()->getTermSignal();
    }
}
