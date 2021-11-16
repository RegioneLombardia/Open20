<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Parser;

use Symfony\Component\CssSelector\Node\SelectorNode;

/**
 * CSS selector parser interface.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
interface ParserInterface
{
    /**
     * Parses given selector source into an array of tokens.
     *
     * @param string $source
     *
     * @return SelectorNode[]
     */
    public function parse($source);
}
