<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

if (\PHP_VERSION_ID < 70300) {
    class JsonException extends Exception
    {
    }
}
