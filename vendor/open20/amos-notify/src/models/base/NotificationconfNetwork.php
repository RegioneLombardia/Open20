<?php

namespace open20\amos\notificationmanager\models\base;

use Yii;

/**
 * This is the base-model class for table "notificationconf_network".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $models_classname_id
 * @property integer $record_id
 * @property integer $email
 * @property integer $sms
 * @property integer $notifications_enabled
 * @property integer $notify_content_pubblication
 * @property integer $notify_comments
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\core\user\User $user
 * @property \open20\amos\core\models\ModelsClassname $modelsClassname
 */
class  NotificationconfNetwork extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notificationconf_network';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'models_classname_id'], 'required'],
            [['user_id', 'models_classname_id', 'record_id', 'email', 'sms', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['models_classname_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\models\ModelsClassname::className(), 'targetAttribute' => ['models_classname_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnotify', 'ID'),
            'user_id' => Yii::t('amosnotify', 'User'),
            'models_classname_id' => Yii::t('amosnotify', 'Network'),
            'record_id' => Yii::t('amosnotify', 'Record id'),
            'email' => Yii::t('amosnotify', 'Email'),
            'sms' => Yii::t('amosnotify', 'Sms'),
            'created_at' => Yii::t('amosnotify', 'Created at'),
            'updated_at' => Yii::t('amosnotify', 'Updated at'),
            'deleted_at' => Yii::t('amosnotify', 'Deleted at'),
            'created_by' => Yii::t('amosnotify', 'Created by'),
            'updated_by' => Yii::t('amosnotify', 'Updated at'),
            'deleted_by' => Yii::t('amosnotify', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelsClassname()
    {
        return $this->hasOne(\open20\amos\core\models\ModelsClassname::className(), ['id' => 'models_classname_id']);
    }


}
