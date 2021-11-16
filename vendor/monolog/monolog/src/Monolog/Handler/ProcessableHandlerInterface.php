<?php declare(strict_types=1);

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Handler;

use Monolog\Processor\ProcessorInterface;

/**
 * Interface to describe loggers that have processors
 *
 * This interface is present in monolog 1.x to ease forward compatibility.
 *
 */
interface ProcessableHandlerInterface
{
    /**
     * Adds a processor in the stack.
     *
     * @param  ProcessorInterface|callable $callback
     * @return HandlerInterface            self
     */
    public function pushProcessor($callback): HandlerInterface;

    /**
     * Removes the processor on top of the stack and returns it.
     *
     * @throws \LogicException In case the processor stack is empty
     * @return callable
     */
    public function popProcessor(): callable;
}
