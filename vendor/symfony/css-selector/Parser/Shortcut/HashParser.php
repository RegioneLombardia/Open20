<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Parser\Shortcut;

use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\CssSelector\Node\HashNode;
use Symfony\Component\CssSelector\Node\SelectorNode;
use Symfony\Component\CssSelector\Parser\ParserInterface;

/**
 * CSS selector hash parser shortcut.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class HashParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($source)
    {
        // Matches an optional namespace, optional element, and required id
        // $source = 'test|input#ab6bd_field';
        // $matches = array (size=4)
        //     0 => string 'test|input#ab6bd_field' (length=22)
        //     1 => string 'test' (length=4)
        //     2 => string 'input' (length=5)
        //     3 => string 'ab6bd_field' (length=11)
        if (preg_match('/^(?:([a-z]++)\|)?+([\w-]++|\*)?+#([\w-]++)$/i', trim($source), $matches)) {
            return array(
                new SelectorNode(new HashNode(new ElementNode($matches[1] ?: null, $matches[2] ?: null), $matches[3])),
            );
        }

        return array();
    }
}
