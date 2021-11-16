<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Represents a "<selector>#<id>" node.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class HashNode extends AbstractNode
{
    /**
     * @var NodeInterface
     */
    private $selector;

    /**
     * @var string
     */
    private $id;

    /**
     * @param NodeInterface $selector
     * @param string        $id
     */
    public function __construct(NodeInterface $selector, $id)
    {
        $this->selector = $selector;
        $this->id = $id;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecificity()
    {
        return $this->selector->getSpecificity()->plus(new Specificity(1, 0, 0));
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s[%s#%s]', $this->getNodeName(), $this->selector, $this->id);
    }
}
