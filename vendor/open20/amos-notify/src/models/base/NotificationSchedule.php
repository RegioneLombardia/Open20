<?php

namespace open20\amos\notificationmanager\models\base;

use open20\amos\core\user\User;
use Yii;

/**
 * This is the base-model class for table "notification_schedule".
 *
 * @property integer $id
 * @property string $status
 * @property integer $type
 * @property integer $max_user_id_to_notify
 * @property integer $last_notified_user_id
 * @property string $ended_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $start_user_id
 *
 * @property \open20\amos\notificationmanager\models\User $lastNotifiedUser
 * @property \open20\amos\notificationmanager\models\NotificationScheduleContent[] $notificationScheduleContents
 */
class  NotificationSchedule extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'last_notified_user_id', 'created_by', 'updated_by', 'deleted_by','max_user_id_to_notify'], 'integer'],
            [['start_user_id','ended_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['last_notified_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['last_notified_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosnotify', 'ID'),
            'status' => Yii::t('amosnotify', 'Status'),
            'type' => Yii::t('amosnotify', 'Type'),
            'last_notified_user_id' => Yii::t('amosnotify', 'Content classname'),
            'ended_at' => Yii::t('amosnotify', 'Ended at'),
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
    public function getLastNotifiedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'last_notified_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationScheduleContents()
    {
        return $this->hasMany(\open20\amos\notificationmanager\models\NotificationScheduleContent::className(), ['notification_schedule_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(\open20\amos\notificationmanager\models\Notification::className(), ['id' => 'notification_id'])->via('notificationScheduleContents');
    }
}
