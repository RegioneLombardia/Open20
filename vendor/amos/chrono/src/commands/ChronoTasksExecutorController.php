<?php

namespace amos\chrono\commands;

use amos\chrono\models\ChronoTask;
use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\log\Logger;
use const PHP_EOL;

class ChronoTasksExecutorController extends Controller
{

    public function init()
    {
        parent::init();
    }

    /**
     * 
     */
    public function actionTaskRunner()
    {
        try {

            $models = ChronoTask::find()->all();

            echo $this->ansiFormat('Scheduled Tasks', Console::UNDERLINE).PHP_EOL;

            foreach ($models as $model) { /* @var ChronoTask $model */
                $row = sprintf(
                "%s\t%s\t%s\t%s\t%s",
                $model->name,
                $model->schedule,
                is_null($model->last_run) ? 'NULL' : $model->last_run,
                $model->next_run,
                $model->getStatusLabel()
                );
                if($model->shouldRun()){
                    ob_start();
                    $model->start();
                    $model->run();
                    $output = ob_get_contents();
                    ob_end_clean();
                    $model->stop();
                    echo $output;
                }
                echo $this->ansiFormat($row) .PHP_EOL;
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }
}