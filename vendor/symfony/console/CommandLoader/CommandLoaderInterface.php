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
     * @return Command
     *
     * @throws CommandNotFoundException
     */
    public function get(string $name);

    /**
     * Checks if a command exists.
     *
     * @return bool
     */
    public function has(string $name);

    /**
     * @return string[]
     */
    public function getNames();
}
