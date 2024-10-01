<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\EventDispatcher\Attribute;

/**
 * Service tag to autoconfigure event listeners.
 *
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class AsEventListener
{
    public function __construct(
        public ?string $event = null,
        public ?string $method = null,
        public int $priority = 0,
        public ?string $dispatcher = null,
    ) {
    }
}
