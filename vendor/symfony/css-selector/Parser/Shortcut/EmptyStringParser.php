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
 * CSS selector class parser shortcut.
 *
 * This shortcut ensure compatibility with previous version.
 * - The parser fails to parse an empty string.
 * - In the previous version, an empty string matches each tags.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class EmptyStringParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($source)
    {
        // Matches an empty string
        if ($source == '') {
            return array(new SelectorNode(new ElementNode(null, '*')));
        }

        return array();
    }
}
