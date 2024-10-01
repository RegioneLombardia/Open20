<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Contracts\Service\Attribute;

use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * For use as the return value for {@see ServiceSubscriberInterface}.
 *
 * @example new SubscribedService('http_client', HttpClientInterface::class, false, new Target('githubApi'))
 *
 * Use with {@see ServiceMethodsSubscriberTrait} to mark a method's return type
 * as a subscribed service.
 *
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class SubscribedService
{
    /** @var object[] */
    public array $attributes;

    /**
     * @param string|null       $key        The key to use for the service
     * @param class-string|null $type       The service class
     * @param bool              $nullable   Whether the service is optional
     * @param object|object[]   $attributes One or more dependency injection attributes to use
     */
    public function __construct(
        public ?string $key = null,
        public ?string $type = null,
        public bool $nullable = false,
        array|object $attributes = [],
    ) {
        $this->attributes = \is_array($attributes) ? $attributes : [$attributes];
    }
}
