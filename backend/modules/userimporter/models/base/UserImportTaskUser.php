<?php

namespace backend\modules\userimporter\models\base;

use Yii;

/**
 * This is the base-model class for table "user_import_task_user".
 *
 * @property integer $id
 * @property integer $user_import_task_id
 * @property integer $name
 * @property string $surname
 * @property string $email
 * @property string $company
 * @property string $token
 * @property string $expire_date
 * @property integer $user_id
 * @property integer $mailup_import_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\modules\userimporter\models\UserImportTask $userImportTask
 * @property int $to_send [int(11)]
 */
class  UserImportTaskUser extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_import_task_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['to_send','user_import_task_id', 'created_by', 'updated_by', 'deleted_by','user_id','mailup_import_id'], 'integer'],
            [['name','surname', 'created_at', 'updated_at', 'deleted_at','expire_date'], 'safe'],
            [[ 'email', 'company', 'token'], 'string', 'max' => 255],
            [[ 'email'], 'email'],
            [['user_import_task_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserImportTask::className(), 'targetAttribute' => ['user_import_task_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosuserimporter', 'ID'),
            'user_import_task_id' => Yii::t('amosuserimporter', 'Task'),
            'name' => Yii::t('amosuserimporter', 'Name'),
            'surname' => Yii::t('amosuserimporter', 'Surname'),
            'Email' => Yii::t('amosuserimporter', 'Email'),
            'Company' => Yii::t('amosuserimporter', 'Company'),
            'token' => Yii::t('amosuserimporter', 'Token'),
            'created_at' => Yii::t('amosuserimporter', 'Created at'),
            'updated_at' => Yii::t('amosuserimporter', 'Updated at'),
            'deleted_at' => Yii::t('amosuserimporter', 'Deleted at'),
            'created_by' => Yii::t('amosuserimporter', 'Created by'),
            'updated_by' => Yii::t('amosuserimporter', 'Updated at'),
            'deleted_by' => Yii::t('amosuserimporter', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserImportTask()
    {
        return $this->hasOne(\backend\modules\userimporter\models\UserImportTask::className(), ['id' => 'user_import_task_id']);
    }
}
