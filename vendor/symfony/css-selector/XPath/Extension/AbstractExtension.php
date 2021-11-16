<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\XPath\Extension;

/**
 * XPath expression translator abstract extension.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
abstract class AbstractExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNodeTranslators()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getCombinationTranslators()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctionTranslators()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getPseudoClassTranslators()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeMatchingTranslators()
    {
        return array();
    }
}
