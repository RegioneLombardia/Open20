<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Parser\Shortcut;

use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\CssSelector\Node\SelectorNode;
use Symfony\Component\CssSelector\Parser\ParserInterface;

/**
 * CSS selector element parser shortcut.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class ElementParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($source)
    {
        // Matches an optional namespace, required element or `*`
        // $source = 'testns|testel';
        // $matches = array (size=3)
        //     0 => string 'testns|testel' (length=13)
        //     1 => string 'testns' (length=6)
        //     2 => string 'testel' (length=6)
        if (preg_match('/^(?:([a-z]++)\|)?([\w-]++|\*)$/i', trim($source), $matches)) {
            return array(new SelectorNode(new ElementNode($matches[1] ?: null, $matches[2])));
        }

        return array();
    }
}
