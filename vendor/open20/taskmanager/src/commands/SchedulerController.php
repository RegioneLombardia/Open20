<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager\commands
 * @category   CategoryName
 */

namespace open20\amos\taskmanager\commands;

use open20\amos\taskmanager\models\TaskManagerJob;
use yii\helpers\BaseFileHelper;
use yii\helpers\Console;

/**
 * Class SchedulerController
 * SchedulerController extends \yii\console\Controller
 */
class SchedulerController extends \yii\console\Controller
{
    public $defaultAction = 'run';
    public $channel       = 1;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        set_time_limit(0);
    }

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'channel'
        ]);
    }

    /**
     * function that launches the command present in the table and changes the state of the record as long as it is active
     * if there is an active task, the function does nothing
     */
    public function actionRun()
    {
        $job = TaskManagerJob::find()->andWhere(['status' => TaskManagerJob::STATUS_STARTED, 'channel' => $this->channel])->exists();
        if ($job) {
            Console::stdout("There are jobs still running for channel ".$this->channel.".\n");
            return;
        }
        $job = TaskManagerJob::find()->andWhere(['status' => TaskManagerJob::STATUS_SCHEDULED, 'channel' => $this->channel])->orderBy('created_at asc')->one();
        try {
            if (!($job)) {
                Console::stdout("No scheduled jobs for channel ".$this->channel.".\n");
                return;
            }
            $job->status = TaskManagerJob::STATUS_STARTED;
            $job->start_execution_time = date('Y-m-d H:i:s');
            $job->save();
            $filename    = $job->filename ? ($job->filename) : null;
            $data        = $job->data ? json_decode($job->data, true) : null;
            Console::stdout("Job ID ".$job->id." launched.\n");

            $parameters             = explode(' ', $job->command);
            $cmd                    = array_shift($parameters);
            if (!empty($filename)) $parameters['filename'] = $filename;
            if (!empty($data)) $parameters['data']     = $data;

            $result = \Yii::$app->runAction(BaseFileHelper::normalizePath('/'.$cmd), $parameters);
            if ($result != 0) {
                throw new \Exception('ERROR: Job command exited with value: ' . $result . ' check error log.');
            }

            if ($filename) {
                if (!\Yii::$app->getModule('attachments'))
                        throw new \Exception("No attachments module configured and running.\n");

                if (!file_exists($filename))
                        throw new \Exception("The job returned an output path, `".$filename."`, but the file hasn't been generated.\n");

                \Yii::$app->getModule('attachments')->attachFile($filename, $job, 'output', true);
            }
            $job->status = TaskManagerJob::STATUS_COMPLETED;
            $job->end_execution_time = date('Y-m-d H:i:s');
            $job->save();
            Console::stdout("Job ID ".$job->id." on channel ".$this->channel." completed successfully.\n");

            return 0;
        } catch (\Exception $e) {
            \Yii::getLogger()->log($e->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
            $job->status = TaskManagerJob::STATUS_FAILED;
            $job->end_execution_time = date('Y-m-d H:i:s');
            $job->save();
            return Console::stdout("Error executing job ID ".$job->id." on channel ".$this->channel.":\n".$e);
        }
    }
}