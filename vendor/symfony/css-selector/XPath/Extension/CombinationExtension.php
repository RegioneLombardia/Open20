<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\XPath\Extension;

use Symfony\Component\CssSelector\XPath\XPathExpr;

/**
 * XPath expression translator combination extension.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class CombinationExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getCombinationTranslators()
    {
        return array(
            ' ' => array($this, 'translateDescendant'),
            '>' => array($this, 'translateChild'),
            '+' => array($this, 'translateDirectAdjacent'),
            '~' => array($this, 'translateIndirectAdjacent'),
        );
    }

    /**
     * @param XPathExpr $xpath
     * @param XPathExpr $combinedXpath
     *
     * @return XPathExpr
     */
    public function translateDescendant(XPathExpr $xpath, XPathExpr $combinedXpath)
    {
        return $xpath->join('/descendant-or-self::*/', $combinedXpath);
    }

    /**
     * @param XPathExpr $xpath
     * @param XPathExpr $combinedXpath
     *
     * @return XPathExpr
     */
    public function translateChild(XPathExpr $xpath, XPathExpr $combinedXpath)
    {
        return $xpath->join('/', $combinedXpath);
    }

    /**
     * @param XPathExpr $xpath
     * @param XPathExpr $combinedXpath
     *
     * @return XPathExpr
     */
    public function translateDirectAdjacent(XPathExpr $xpath, XPathExpr $combinedXpath)
    {
        return $xpath
            ->join('/following-sibling::', $combinedXpath)
            ->addNameTest()
            ->addCondition('position() = 1');
    }

    /**
     * @param XPathExpr $xpath
     * @param XPathExpr $combinedXpath
     *
     * @return XPathExpr
     */
    public function translateIndirectAdjacent(XPathExpr $xpath, XPathExpr $combinedXpath)
    {
        return $xpath->join('/following-sibling::', $combinedXpath);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'combination';
    }
}
