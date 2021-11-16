<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Interface for nodes.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
interface NodeInterface
{
    /**
     * Returns node's name.
     *
     * @return string
     */
    public function getNodeName();

    /**
     * Returns node's specificity.
     *
     * @return Specificity
     */
    public function getSpecificity();

    /**
     * Returns node's string representation.
     *
     * @return string
     */
    public function __toString();
}
