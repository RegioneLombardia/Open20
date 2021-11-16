<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\XPath\Extension;

/**
 * XPath expression translator extension interface.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
interface ExtensionInterface
{
    /**
     * Returns node translators.
     *
     * These callables will receive the node as first argument and the translator as second argument.
     *
     * @return callable[]
     */
    public function getNodeTranslators();

    /**
     * Returns combination translators.
     *
     * @return callable[]
     */
    public function getCombinationTranslators();

    /**
     * Returns function translators.
     *
     * @return callable[]
     */
    public function getFunctionTranslators();

    /**
     * Returns pseudo-class translators.
     *
     * @return callable[]
     */
    public function getPseudoClassTranslators();

    /**
     * Returns attribute operation translators.
     *
     * @return callable[]
     */
    public function getAttributeMatchingTranslators();

    /**
     * Returns extension name.
     *
     * @return string
     */
    public function getName();
}
