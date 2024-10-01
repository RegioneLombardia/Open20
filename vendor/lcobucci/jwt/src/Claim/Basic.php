<?php
/**
 *
 */

namespace Lcobucci\JWT\Claim;

use Lcobucci\JWT\Claim;

/**
 * The default claim
 *
 * @since 2.0.0
 */
class Basic implements Claim
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Initializes the claim
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
