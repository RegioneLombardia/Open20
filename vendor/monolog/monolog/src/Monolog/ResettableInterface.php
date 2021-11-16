<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog;

/**
 * Handler or Processor implementing this interface will be reset when Logger::reset() is called.
 *
 * Resetting ends a log cycle gets them back to their initial state.
 *
 * Resetting a Handler or a Processor means flushing/cleaning all buffers, resetting internal
 * state, and getting it back to a state in which it can receive log records again.
 *
 * This is useful in case you want to avoid logs leaking between two requests or jobs when you
 * have a long running process like a worker or an application server serving multiple requests
 * in one process.
 *
 */
interface ResettableInterface
{
    public function reset();
}
