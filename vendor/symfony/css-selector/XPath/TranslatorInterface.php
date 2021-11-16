<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\XPath;

use Symfony\Component\CssSelector\Node\SelectorNode;

/**
 * XPath expression translator interface.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
interface TranslatorInterface
{
    /**
     * Translates a CSS selector to an XPath expression.
     *
     * @param string $cssExpr
     * @param string $prefix
     *
     * @return string
     */
    public function cssToXPath($cssExpr, $prefix = 'descendant-or-self::');

    /**
     * Translates a parsed selector node to an XPath expression.
     *
     * @param SelectorNode $selector
     * @param string       $prefix
     *
     * @return string
     */
    public function selectorToXPath(SelectorNode $selector, $prefix = 'descendant-or-self::');
}
