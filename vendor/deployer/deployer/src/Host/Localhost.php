<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Host;

class Localhost extends Host
{
    /**
     * @param string $hostname
     */
    public function __construct(string $hostname = 'localhost')
    {
        parent::__construct($hostname);
    }
}
