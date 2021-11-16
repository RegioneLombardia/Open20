<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Parser\Handler;

use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\CssSelector\Parser\TokenStream;

/**
 * CSS selector handler interface.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
interface HandlerInterface
{
    /**
     * @param Reader      $reader
     * @param TokenStream $stream
     *
     * @return bool
     */
    public function handle(Reader $reader, TokenStream $stream);
}
