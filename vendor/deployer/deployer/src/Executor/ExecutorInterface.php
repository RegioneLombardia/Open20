<?php declare(strict_types=1);
/* (c) Anton Medvedev <anton@medv.io>
 *
 */

namespace Deployer\Executor;

use Deployer\Host\Host;
use Deployer\Task\Task;

interface ExecutorInterface
{
    /**
     * @param Task[] $tasks
     * @param Host[] $hosts
     */
    public function run(array $tasks, array $hosts);
}
