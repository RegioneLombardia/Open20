<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager
 * @category   CategoryName
 */

namespace open20\amos\taskmanager\rules;

use open20\amos\taskmanager\models\TaskManagerJob;
use Yii;
use yii\rbac\Rule;

class DeleteOwnTaskManagerJobRule extends Rule
{
	public $name = 'deleteOwnTaskManagerJob';

	/**
	 * @inheritdoc
	 */
	public function execute($user, $item, $model)
	{
		if ($model->status != TaskManagerJob::STATUS_SCHEDULED) return false;
		if (\Yii::$app->getUser()->can('TASKMANAGER_MANAGER')) return true;
		if ($model->created_by == \Yii::$app->getUser()->getId()) return true;
		return false;
	}
}
