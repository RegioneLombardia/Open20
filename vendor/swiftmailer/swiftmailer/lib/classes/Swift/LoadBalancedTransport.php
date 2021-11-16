<?php

/*
 * (c) 2004-2009 Chris Corbyn
 *
 */

/**
 * Redundantly and rotationally uses several Transport implementations when sending.
 *
 */
class Swift_LoadBalancedTransport extends Swift_Transport_LoadBalancedTransport
{
    /**
     * Creates a new LoadBalancedTransport with $transports.
     *
     * @param array $transports
     */
    public function __construct($transports = [])
    {
        \call_user_func_array(
            [$this, 'Swift_Transport_LoadBalancedTransport::__construct'],
            Swift_DependencyContainer::getInstance()
                ->createDependenciesFor('transport.loadbalanced')
            );

        $this->setTransports($transports);
    }
}
