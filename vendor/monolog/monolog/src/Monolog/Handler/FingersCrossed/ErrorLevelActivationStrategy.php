<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Handler\FingersCrossed;

use Monolog\Logger;

/**
 * Error level based activation strategy.
 *
 */
class ErrorLevelActivationStrategy implements ActivationStrategyInterface
{
    private $actionLevel;

    public function __construct($actionLevel)
    {
        $this->actionLevel = Logger::toMonologLevel($actionLevel);
    }

    public function isHandlerActivated(array $record)
    {
        return $record['level'] >= $this->actionLevel;
    }
}
