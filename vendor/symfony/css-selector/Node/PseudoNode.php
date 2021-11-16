<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Represents a "<selector>:<identifier>" node.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class PseudoNode extends AbstractNode
{
    /**
     * @var NodeInterface
     */
    private $selector;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @param NodeInterface $selector
     * @param string        $identifier
     */
    public function __construct(NodeInterface $selector, $identifier)
    {
        $this->selector = $selector;
        $this->identifier = strtolower($identifier);
    }

    /**
     * @return NodeInterface
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecificity()
    {
        return $this->selector->getSpecificity()->plus(new Specificity(0, 1, 0));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s[%s:%s]', $this->getNodeName(), $this->selector, $this->identifier);
    }
}
