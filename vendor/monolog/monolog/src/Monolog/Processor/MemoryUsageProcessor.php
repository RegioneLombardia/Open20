<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Processor;

/**
 * Injects memory_get_usage in all records
 *
 */
class MemoryUsageProcessor extends MemoryProcessor
{
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $bytes = memory_get_usage($this->realUsage);
        $formatted = $this->formatBytes($bytes);

        $record['extra']['memory_usage'] = $formatted;

        return $record;
    }
}
