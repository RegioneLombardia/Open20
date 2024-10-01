<?php declare(strict_types=1);
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Collection;

interface CollectionInterface extends \IteratorAggregate, \ArrayAccess
{
    /**
     * @return mixed
     */
    public function get(string $name);

    /**
     * @param mixed $object
     * @return void
     */
    public function set(string $name, $object);

    public function has(string $name): bool;
}
