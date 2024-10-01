<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models;

use open20\amos\notificationmanager\AmosNotify;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Notification
 *
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $channels
 * @property integer $content_id
 * @property string $class_name
 * @property integer $processed
 * @property integer $models_classname_id
 * @property integer $record_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @package open20\amos\notificationmanager\models
 */
class Notification extends \open20\amos\core\record\Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'channels', 'content_id', 'processed', 'created_at', 'updated_at', 'models_content_id', 'record_id'], 'integer'],
            [['class_name'], 'string'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'channels' => 'Channels',
            'content_id' => 'Content ID',
            'class_name' => 'Class Name',
            'processed' => 'Processed',
            'record_id' => 'Record Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'class' => TimestampBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationsRead()
    {
        return $this->hasMany(AmosNotify::instance()->model('NotificationsRead'), ['notification_id' => 'id']);
    }
}
