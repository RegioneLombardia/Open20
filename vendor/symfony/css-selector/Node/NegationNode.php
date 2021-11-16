<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Represents a "<selector>:not(<identifier>)" node.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class NegationNode extends AbstractNode
{
    /**
     * @var NodeInterface
     */
    private $selector;

    /**
     * @var NodeInterface
     */
    private $subSelector;

    /**
     * @param NodeInterface $selector
     * @param NodeInterface $subSelector
     */
    public function __construct(NodeInterface $selector, NodeInterface $subSelector)
    {
        $this->selector = $selector;
        $this->subSelector = $subSelector;
    }

    /**
     * @return NodeInterface
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return NodeInterface
     */
    public function getSubSelector()
    {
        return $this->subSelector;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecificity()
    {
        return $this->selector->getSpecificity()->plus($this->subSelector->getSpecificity());
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s[%s:not(%s)]', $this->getNodeName(), $this->selector, $this->subSelector);
    }
}
