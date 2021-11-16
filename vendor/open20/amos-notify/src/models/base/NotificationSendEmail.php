<?php

namespace open20\amos\notificationmanager\models\base;

use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "notification_send_email".
 *
 * @property integer $id
 * @property string $classname
 * @property integer $content_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  NotificationSendEmail extends Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_send_email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classname', 'content_id'], 'required'],
            [['content_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['classname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnotification', 'ID'),
            'classname' => Yii::t('amosnotification', 'Classname'),
            'content_id' => Yii::t('amosnotification', 'Content ID'),
            'created_at' => Yii::t('amosnotification', 'Creato il'),
            'updated_at' => Yii::t('amosnotification', 'Aggiornato il'),
            'deleted_at' => Yii::t('amosnotification', 'Cancellato il'),
            'created_by' => Yii::t('amosnotification', 'Creato da'),
            'updated_by' => Yii::t('amosnotification', 'Aggiornato da'),
            'deleted_by' => Yii::t('amosnotification', 'Cancellato da'),
        ];
    }
}
