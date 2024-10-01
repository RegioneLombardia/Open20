<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Contracts\Service;

/**
 * A ServiceProviderInterface that is also countable and iterable.
 *
 *
 * @template-covariant T of mixed
 *
 * @extends ServiceProviderInterface<T>
 * @extends \IteratorAggregate<string, T>
 */
interface ServiceCollectionInterface extends ServiceProviderInterface, \Countable, \IteratorAggregate
{
}
