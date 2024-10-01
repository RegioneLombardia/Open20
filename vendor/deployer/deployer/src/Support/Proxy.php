<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Support;

class Proxy
{
    private $objects;

    public function __construct(array $objects)
    {
        $this->objects = $objects;
    }

    public function __call($name, $arguments)
    {
        foreach ($this->objects as $object) {
            $object->$name(...$arguments);
        }
        return $this;
    }
}
