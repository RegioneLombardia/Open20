<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager\models
 * @category   CategoryName
 */

/**
 * This is the base-model class for table "task_manager_jobs".
 *
 * @property integer $id
 * @property string $command
 * @property string $module
 * @property integer $channel
 * @property string $status
 * @property string $start_execution_time
 * @property string $end_execution_time
 * @property string $data
 * @property string $filename
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $deleted_at
 * @property integer $deleted_by
 */

namespace open20\amos\taskmanager\models\base;

/**
 * Description of TaskManagerJob
 *
 */
class TaskManagerJob extends \open20\amos\core\record\Record {

	const STATUS_SCHEDULED = 0;
	const STATUS_STARTED = 1;
	const STATUS_COMPLETED = 2;
	const STATUS_FAILED = 3;
	//const STATUS_DOWNLOADED = 99; // Probably not needed for the wider scope of this module

      const STATUSES = [
            0 => 'STATUS_SCHEDULED',
            1 => 'STATUS_STARTED',
            2 => 'STATUS_COMPLETED',
            3 => 'STATUS_FAILED'
      ];

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
			return 'task_manager_jobs';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['command'], 'string', 'max' => 255],
			[['module'], 'string', 'max' => 255],
			[['channel'], 'integer'],
			[['status'], 'integer'],
			[['data'], 'string'],
      [['filename'], 'string', 'max' => 255],
			[['created_at', 'updated_at', 'deleted_at', 'start_execution_time', 'end_execution_time'], 'safe'],
			[['created_by', 'updated_by', 'deleted_by'], 'safe']
		];
	}

}
