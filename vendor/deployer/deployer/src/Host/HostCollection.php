<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Host;

use Deployer\Collection\Collection;

/**
 * @method Host get($name)
 */
class HostCollection extends Collection
{
    protected function throwNotFound(string $name)
    {
        throw new \InvalidArgumentException("Host `$name` not found");
    }
}
