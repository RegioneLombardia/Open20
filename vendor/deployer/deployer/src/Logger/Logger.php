<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Logger;

use Deployer\Logger\Handler\HandlerInterface;

class Logger
{
    /**
     * @var HandlerInterface
     */
    private $handler;

    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function log(string $message)
    {
        $this->handler->log("$message\n");
    }
}
