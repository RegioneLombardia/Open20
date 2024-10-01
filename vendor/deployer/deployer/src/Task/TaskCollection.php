<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Task;

use Deployer\Collection\Collection;

/**
 * @method Task get($name)
 */
class TaskCollection extends Collection
{
    protected function throwNotFound(string $name)
    {
        throw new \InvalidArgumentException("Task `$name` not found");
    }
}
