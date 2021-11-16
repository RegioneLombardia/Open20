<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\OptionsResolver\Exception;

/**
 * Thrown when trying to read an option outside of or write it inside of
 * {@link \Symfony\Component\OptionsResolver\Options::resolve()}.
 *
 */
class AccessException extends \LogicException implements ExceptionInterface
{
}
