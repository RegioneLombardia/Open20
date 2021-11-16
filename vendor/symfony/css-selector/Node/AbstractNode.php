<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

/**
 * Abstract base node class.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
abstract class AbstractNode implements NodeInterface
{
    /**
     * @var string
     */
    private $nodeName;

    /**
     * @return string
     */
    public function getNodeName()
    {
        if (null === $this->nodeName) {
            $this->nodeName = preg_replace('~.*\\\\([^\\\\]+)Node$~', '$1', get_called_class());
        }

        return $this->nodeName;
    }
}
