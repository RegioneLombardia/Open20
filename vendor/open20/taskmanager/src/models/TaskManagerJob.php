<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\taskmanager\models
 * @category   CategoryName
 */

namespace open20\amos\taskmanager\models;

use yii\helpers\ArrayHelper;
use yii\base\ErrorException;

/**
 * Description of TaskManagerJob
 *
 */
class TaskManagerJob extends \open20\amos\taskmanager\models\base\TaskManagerJob {

	// File output (if available).
	public $output;

	public static function getBulletCount()
	{
		$query = self::find()->where(['status' => TaskManagerJob::STATUS_COMPLETED])
			->andWhere(['created_by' => \Yii::$app->getUser()->getId()]);

		// If output is filled, check the number of downloads
		if ($output)
			$query->joinWith(['output' => function(\yii\db\ActiveQuery $query) {
				$query->andWhere(['numDownloads', 0]);
			}]);

		return $query->count();
	}

	/**
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(),
		[
			[['output'], 'file'],
		]);
	}


	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		parent::afterFind();
		if (\Yii::$app->getModule('attachments'))
			$this->output = $this->hasOneFile('output')->one();
	}

	/**
	 * @inheritdoc
	 */
	public function delete()
	{
			if ($this->status != self::STATUS_SCHEDULED) {
				$this->addError('status', 'Cannot delete job.');
				return;
			}
			return parent::delete();
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		if (\Yii::$app->getModule('attachments'))
			$behaviors = ArrayHelper::merge(parent::behaviors(),
				['fileBehavior' => [
					'class' => \open20\amos\attachments\behaviors\FileBehavior::className()
				],
			]);

		else $behaviors = parent::behaviors();

		return $behaviors;
	}
}
