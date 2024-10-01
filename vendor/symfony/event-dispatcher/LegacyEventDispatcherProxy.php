<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\EventDispatcher;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

trigger_deprecation('symfony/event-dispatcher', '5.1', '%s is deprecated, use the event dispatcher without the proxy.', LegacyEventDispatcherProxy::class);

/**
 * A helper class to provide BC/FC with the legacy signature of EventDispatcherInterface::dispatch().
 *
 *
 * @deprecated since Symfony 5.1
 */
final class LegacyEventDispatcherProxy
{
    public static function decorate(?EventDispatcherInterface $dispatcher): ?EventDispatcherInterface
    {
        return $dispatcher;
    }
}
