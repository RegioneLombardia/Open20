<?php
/**
 */

namespace yii\queue;

/**
 * Job Interface.
 *
 */
interface JobInterface
{
    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     */
    public function execute($queue);
}
