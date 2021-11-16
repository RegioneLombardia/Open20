<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Represents a "<selector>(::|:)<pseudoElement>" node.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class SelectorNode extends AbstractNode
{
    /**
     * @var NodeInterface
     */
    private $tree;

    /**
     * @var null|string
     */
    private $pseudoElement;

    /**
     * @param NodeInterface $tree
     * @param null|string   $pseudoElement
     */
    public function __construct(NodeInterface $tree, $pseudoElement = null)
    {
        $this->tree = $tree;
        $this->pseudoElement = $pseudoElement ? strtolower($pseudoElement) : null;
    }

    /**
     * @return NodeInterface
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @return null|string
     */
    public function getPseudoElement()
    {
        return $this->pseudoElement;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecificity()
    {
        return $this->tree->getSpecificity()->plus(new Specificity(0, 0, $this->pseudoElement ? 1 : 0));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s[%s%s]', $this->getNodeName(), $this->tree, $this->pseudoElement ? '::'.$this->pseudoElement : '');
    }
}
