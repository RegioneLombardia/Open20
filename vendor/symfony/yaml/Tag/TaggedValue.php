<?php

/*
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 */

namespace Symfony\Component\Yaml\Tag;

/**
 */
final class TaggedValue
{
    private $tag;
    private $value;

    public function __construct(string $tag, $value)
    {
        $this->tag = $tag;
        $this->value = $value;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getValue()
    {
        return $this->value;
    }
}
