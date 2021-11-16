<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Processor;

/**
 * Adds value of getmypid into records
 *
 */
class ProcessIdProcessor implements ProcessorInterface
{
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra']['process_id'] = getmypid();

        return $record;
    }
}
