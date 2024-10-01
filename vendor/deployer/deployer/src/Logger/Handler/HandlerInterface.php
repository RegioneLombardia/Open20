<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Logger\Handler;

interface HandlerInterface
{
    public function log(string $message);
}
