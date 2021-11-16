<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Represents a "<namespace>|<element>" node.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class ElementNode extends AbstractNode
{
    /**
     * @var string|null
     */
    private $namespace;

    /**
     * @var string|null
     */
    private $element;

    /**
     * @param string|null $namespace
     * @param string|null $element
     */
    public function __construct($namespace = null, $element = null)
    {
        $this->namespace = $namespace;
        $this->element = $element;
    }

    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return null|string
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * {@inheritdoc}
     */
    public function getSpecificity()
    {
        return new Specificity(0, 0, $this->element ? 1 : 0);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $element = $this->element ?: '*';

        return sprintf('%s[%s]', $this->getNodeName(), $this->namespace ? $this->namespace.'|'.$element : $element);
    }
}
