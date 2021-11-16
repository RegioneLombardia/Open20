<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\CommandLoader;

use Symfony\Component\Console\Exception\CommandNotFoundException;

/**
 * A simple command loader using factories to instantiate commands lazily.
 *
 */
class FactoryCommandLoader implements CommandLoaderInterface
{
    private $factories;

    /**
     * @param callable[] $factories Indexed by command names
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->factories[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!isset($this->factories[$name])) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        $factory = $this->factories[$name];

        return $factory();
    }

    /**
     * {@inheritdoc}
     */
    public function getNames()
    {
        return array_keys($this->factories);
    }
}
