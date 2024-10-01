<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Type;

/**
 * Class Csv
 * @package Deployer\Type
 */
class Csv
{
    /**
     * @param string $input
     * @return array
     */
    public static function parse($input)
    {
        return array_map('str_getcsv', explode("\n", $input));
    }
}
