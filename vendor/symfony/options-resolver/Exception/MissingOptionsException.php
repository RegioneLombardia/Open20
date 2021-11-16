<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\OptionsResolver\Exception;

/**
 * Exception thrown when a required option is missing.
 *
 * Add the option to the passed options array.
 *
 */
class MissingOptionsException extends InvalidArgumentException
{
}
