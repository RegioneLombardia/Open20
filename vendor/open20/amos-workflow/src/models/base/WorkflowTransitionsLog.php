<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\workflow
 * @category   CategoryName
 */

namespace open20\amos\workflow\models\base;

use Yii;
use yii\helpers\Json;

/**
 * This is the base-model class for table "amos_workflow_transitions_log".
 *
 * @property integer $id
 * @property string $classname
 * @property string $owner_primary_key
 * @property string $start_status
 * @property string $end_status
 * @property string $comment
 *
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class WorkflowTransitionsLog extends \open20\amos\core\record\Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'amos_workflow_transitions_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classname', 'owner_primary_key', 'start_status', 'end_status', 'comment'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'classname' => Yii::t('app', 'Name of the class'),
            'owner_primary_key' => Yii::t('app', 'Primary key'),
            'start_status' => Yii::t('app', 'Start status'),
            'end_status' => Yii::t('app', 'End status'),
            'comment' => Yii::t('app', 'Comment'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
    }

    public function getOwnerPrimaryKey($asArray = false)
    {
        return Json::decode($this->owner_primary_key);
    }

}
