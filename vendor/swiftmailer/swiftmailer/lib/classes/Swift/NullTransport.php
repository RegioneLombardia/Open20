<?php

/*
 * (c) 2009 Fabien Potencier <fabien.potencier@gmail.com>
 *
 */

/**
 * Pretends messages have been sent, but just ignores them.
 *
 */
class Swift_NullTransport extends Swift_Transport_NullTransport
{
    public function __construct()
    {
        \call_user_func_array(
            [$this, 'Swift_Transport_NullTransport::__construct'],
            Swift_DependencyContainer::getInstance()
                ->createDependenciesFor('transport.null')
        );
    }
}
