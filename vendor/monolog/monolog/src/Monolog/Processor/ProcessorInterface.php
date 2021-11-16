<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Processor;

/**
 * An optional interface to allow labelling Monolog processors.
 *
 */
interface ProcessorInterface
{
    /**
     * @return array The processed records
     */
    public function __invoke(array $records);
}
