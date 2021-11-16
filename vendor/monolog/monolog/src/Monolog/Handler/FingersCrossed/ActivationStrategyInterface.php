<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Handler\FingersCrossed;

/**
 * Interface for activation strategies for the FingersCrossedHandler.
 *
 */
interface ActivationStrategyInterface
{
    /**
     * Returns whether the given record activates the handler.
     *
     * @param  array   $record
     * @return bool
     */
    public function isHandlerActivated(array $record);
}
