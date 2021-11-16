<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\OptionsResolver\Exception;

/**
 * Thrown when two lazy options have a cyclic dependency.
 *
 */
class OptionDefinitionException extends \LogicException implements ExceptionInterface
{
}
