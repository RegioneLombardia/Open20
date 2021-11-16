<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify
 * @category   CategoryName
 */


namespace  open20\amos\notificationmanager\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "notificationread".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer notification_id
 * @property integer $created_at
 * @property integer $updated_at
 */

class NotificationsRead extends \yii\db\ActiveRecord{
    
    
    public static function tableName() {
        return 'notificationread';
    }
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'class' =>  TimestampBehavior::className(),
        ]);
    }
    
}
