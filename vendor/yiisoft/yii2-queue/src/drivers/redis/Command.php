<?php
/**
 */

namespace yii\queue\redis;

use yii\console\Exception;
use yii\queue\cli\Command as CliCommand;
use yii\queue\cli\InfoAction;

/**
 * Manages application redis-queue.
 *
 */
class Command extends CliCommand
{
    /**
     * @var Queue
     */
    public $queue;
    /**
     * @var string
     */
    public $defaultAction = 'info';


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'info' => InfoAction::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function isWorkerAction($actionID)
    {
        return in_array($actionID, ['run', 'listen'], true);
    }

    /**
     * Runs all jobs from redis-queue.
     * It can be used as cron job.
     *
     * @return null|int exit code.
     */
    public function actionRun()
    {
        return $this->queue->run(false);
    }

    /**
     * Listens redis-queue and runs new jobs.
     * It can be used as daemon process.
     *
     * @param int $timeout number of seconds to wait a job.
     * @throws Exception when params are invalid.
     * @return null|int exit code.
     */
    public function actionListen($timeout = 3)
    {
        if (!is_numeric($timeout)) {
            throw new Exception('Timeout must be numeric.');
        }
        if ($timeout < 1) {
            throw new Exception('Timeout must be greater than zero.');
        }

        return $this->queue->run(true, $timeout);
    }

    /**
     * Clears the queue.
     *
     * @since 2.0.1
     */
    public function actionClear()
    {
        if ($this->confirm('Are you sure?')) {
            $this->queue->clear();
        }
    }

    /**
     * Removes a job by id.
     *
     * @param int $id
     * @throws Exception when the job is not found.
     * @since 2.0.1
     */
    public function actionRemove($id)
    {
        if (!$this->queue->remove($id)) {
            throw new Exception('The job is not found.');
        }
    }
}
