<?php
/**
 */

namespace yii\queue;

/**
 * Retryable Job Interface.
 *
 */
interface RetryableJobInterface extends JobInterface
{
    /**
     * @return int time to reserve in seconds
     */
    public function getTtr();

    /**
     * @param int $attempt number
     * @param \Exception|\Throwable $error from last execute of the job
     * @return bool
     */
    public function canRetry($attempt, $error);
}
