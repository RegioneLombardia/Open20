<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Console\CommandLoader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;

/**
 */
interface CommandLoaderInterface
{
    /**
     * Loads a command.
     *
     * @param string $name
     *
     * @return Command
     *
     * @throws CommandNotFoundException
     */
    public function get($name);

    /**
     * Checks if a command exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * @return string[] All registered command names
     */
    public function getNames();
}
