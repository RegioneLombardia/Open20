<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Processor;

/**
 * Adds a tags array into record
 *
 */
class TagProcessor implements ProcessorInterface
{
    private $tags;

    public function __construct(array $tags = array())
    {
        $this->setTags($tags);
    }

    public function addTags(array $tags = array())
    {
        $this->tags = array_merge($this->tags, $tags);
    }

    public function setTags(array $tags = array())
    {
        $this->tags = $tags;
    }

    public function __invoke(array $record)
    {
        $record['extra']['tags'] = $this->tags;

        return $record;
    }
}
