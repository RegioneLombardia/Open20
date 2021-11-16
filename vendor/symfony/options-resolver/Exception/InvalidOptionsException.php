<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\OptionsResolver\Exception;

/**
 * Thrown when the value of an option does not match its validation rules.
 *
 * You should make sure a valid value is passed to the option.
 *
 */
class InvalidOptionsException extends InvalidArgumentException
{
}
