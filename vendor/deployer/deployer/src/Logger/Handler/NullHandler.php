<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Logger\Handler;

class NullHandler implements HandlerInterface
{
    public function log(string $message)
    {
    }
}
