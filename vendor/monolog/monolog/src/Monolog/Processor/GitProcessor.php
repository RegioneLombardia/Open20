<?php

/*
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Monolog\Processor;

use Monolog\Logger;

/**
 * Injects Git branch and Git commit SHA in all records
 *
 */
class GitProcessor implements ProcessorInterface
{
    private $level;
    private static $cache;

    public function __construct($level = Logger::DEBUG)
    {
        $this->level = Logger::toMonologLevel($level);
    }

    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }

        $record['extra']['git'] = self::getGitInfo();

        return $record;
    }

    private static function getGitInfo()
    {
        if (self::$cache) {
            return self::$cache;
        }

        $branches = `git branch -v --no-abbrev`;
        if ($branches && preg_match('{^\* (.+?)\s+([a-f0-9]{40})(?:\s|$)}m', $branches, $matches)) {
            return self::$cache = array(
                'branch' => $matches[1],
                'commit' => $matches[2],
            );
        }

        return self::$cache = array();
    }
}
