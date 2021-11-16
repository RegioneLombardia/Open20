<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\OptionsResolver\Exception;

/**
 * Exception thrown when an undefined option is passed.
 *
 * You should remove the options in question from your code or define them
 * beforehand.
 *
 */
class UndefinedOptionsException extends InvalidArgumentException
{
}
