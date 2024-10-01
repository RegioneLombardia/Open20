<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Finder\Iterator;

/**
 *
 * @internal
 */
class LazyIterator implements \IteratorAggregate
{
    private $iteratorFactory;

    public function __construct(callable $iteratorFactory)
    {
        $this->iteratorFactory = $iteratorFactory;
    }

    public function getIterator(): \Traversable
    {
        yield from ($this->iteratorFactory)();
    }
}
