<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\CssSelector\Node;

use Symfony\Component\CssSelector\Parser\Token;

/**
 * Represents a "<selector>:<name>(<arguments>)" node.
 *
 * This component is a port of the Python cssselect library,
 *
 *
 * @internal
 */
class FunctionNode extends AbstractNode
{
    /**
     * @var NodeInterface
     */
    private $selector;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Token[]
     */
    private $arguments;

    /**
     * @param NodeInterface $selector
     * @param string        $name
     * @param Token[]       $arguments
     */
    public function __construct(NodeInterface $selector, $name, array $arguments = array())
    {
        $this->selector = $selector;
        $this->name = strtolower($name);
        $this->arguments = $arguments;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Token[]
     */
    public function getArguments()
    {
        return $this->arguments;
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
        $arguments = implode(', ', array_map(function (Token $token) {
            return "'".$token->getValue()."'";
        }, $this->arguments));

        return sprintf('%s[%s:%s(%s)]', $this->getNodeName(), $this->selector, $this->name, $arguments ? '['.$arguments.']' : '');
    }
}
