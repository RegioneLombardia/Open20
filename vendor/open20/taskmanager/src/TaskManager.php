<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager
 * @category   CategoryName
 */

namespace open20\amos\taskmanager;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\taskmanager\models\TaskManagerJob;
use yii\helpers\BaseFileHelper;
use yii\helpers\ArrayHelper;
use yii\base\ErrorException;
use yii\web\UnauthorizedHttpException;
use yii\log\Logger;

/** 
 * Class TaskManager
 * Task manager module definition class
 * @package open20\amos\taskmanager
 */
class TaskManager extends AmosModule implements \yii\base\BootstrapInterface, ModuleInterface
{

		public $layout = 'main';

		public $controllerNamespace = 'open20\amos\taskmanager\controllers';

		/**
		 * @var string $tmpDir
		 */
		public $tmpDir = null;

		/**
		 * @inheritdoc
		 */
		public function init()
		{
				parent::init();
				\Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');

				// Checks whether tmpDir is set; in case it is, it checks if it's an existing path;
				// otherwise, it uses the default sys_get_temp_dir().
				$tmpDir = sys_get_temp_dir();
				if (!empty($this->tmpDir) && !is_string($this->tmpDir))
					throw new ErrorException('Temporary directory should be passed as a string.');

				if (!empty($this->tmpDir))
					if (is_string($this->tmpDir) &&
						file_exists(BaseFileHelper::normalizePath($this->tmpDir))
					) {
						$tmpDir = BaseFileHelper::normalizePath($this->tmpDir);
					} else {
						throw new ErrorException('The specified temporary directory does not exist.');
					}
				$this->tmpDir = $tmpDir;
		}

		/**
		 * @param \yii\console\Application $app
		 */
		public function bootstrap($app)
		{
			if ($app instanceof \yii\console\Application) {
				$this->controllerNamespace = 'open20\amos\taskmanager\commands';
			}
		}

		/**
		 * Schedules a new job in the indicated task queue.
		 *
		 * @param string $command The command to run. Required.
		 * @param string $module The caller module name. Defaults to 'amos_task_manager'.
		 * @param string|bool $output True if the Task Manager should create an output path with a random name for the command; string to specify the file full path; null if no output is expected from the command. Defaults to null.
		 * @param array $data Additional data passed to the command, in JSON format. Defaults to null.
		 * @param int $channel Channel for concurrent tasks. Defaults to 1.
		 *
		 * @return int Job ID.
		 */
		public function scheduleJob(string $command, string $module = 'amos_task_manager', $output = null, array $data = null, int $channel = 1): int
		{

			if (!\Yii::$app->getUser()->can('TASKMANAGER_CREATE')) throw new UnauthorizedHttpException('Not authorized to create job.');

			// if output is boolean and true, this means the caller
			// delegated this method to generate a random filename.
			if (is_bool($output) && $output == true)
				$output = $this->tmpDir . DIRECTORY_SEPARATOR . uniqid();

			$job = new TaskManagerJob();
			$job->module = $module;
			$job->command = $command;
			$job->channel = $channel;
			$job->status = TaskManagerJob::STATUS_SCHEDULED;
			$job->data = $data ? json_encode($data) : null;
			$job->filename = is_string($output) ? $output : null;

			if ($job->save())
				return $job->id;

			throw new ErrorException('Cannot save scheduled job.');
		}

		/**
		 * Returns job details as an array.
		 *
		 * @param int $id Job ID, returned initially from scheduleJob.
		 *
		 * @return array Job, if found.
		 */
		public function getJob($id) {
			$job = TaskManagerJob::findOne($id);
			if ($job) return $job->toArray([]);
			return;
		}

        /**
         * Returns job details as an array.
         *
         * @param int $channel Channel.
         *
         * @return array Job, if found.
         */
        public function getActiveJob($channel)
        {
            $job = TaskManagerJob::find()->andWhere(['channel' => $channel, 'status' => [0, 1]])->select('id');
            if ($job) {
                return $job->asArray()->all();
            }
            return [];
        }

		/**
		 * @inheritdoc
		 */
		public function getWidgetGraphics()
		{
				return null;
		}

		/**
		 * @inheritdoc
		 */
		protected function getDefaultModels()
		{
				return [
						'TaskManagerJob' => __NAMESPACE__ . '\\' . 'models\TaskManagerJob',
				];
		}

		public static function getModuleName()
		{
				return 'taskmanager';
		}

		public static function getModuleIconName()
		{
				return 'groups';
		}

		/**
		 * @inheritdoc
		 */
		public static function getModelClassName()
		{
				return self::instance()->model('TaskManagerJob');
		}


		/**
		 * @inheritdoc
		 */
		public function getWidgetIcons()
		{
				return [];
		}
}
