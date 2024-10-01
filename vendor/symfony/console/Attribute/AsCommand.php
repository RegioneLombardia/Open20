<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\Attribute;

/**
 * Service tag to autoconfigure commands.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class AsCommand
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        array $aliases = [],
        bool $hidden = false,
    ) {
        if (!$hidden && !$aliases) {
            return;
        }

        $name = explode('|', $name);
        $name = array_merge($name, $aliases);

        if ($hidden && '' !== $name[0]) {
            array_unshift($name, '');
        }

        $this->name = implode('|', $name);
    }
}
