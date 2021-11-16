<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\EventDispatcher\Debug;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 *
 * @method reset() Resets the trace.
 */
interface TraceableEventDispatcherInterface extends EventDispatcherInterface
{
    /**
     * Gets the called listeners.
     *
     * @return array An array of called listeners
     */
    public function getCalledListeners();

    /**
     * Gets the not called listeners.
     *
     * @return array An array of not called listeners
     */
    public function getNotCalledListeners();
}
