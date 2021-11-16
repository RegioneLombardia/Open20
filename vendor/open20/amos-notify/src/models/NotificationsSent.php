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
 * This is the model class for table "notificationsent".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $howmany
 * @property integer $created_at
 * @property integer $updated_at
 */

class NotificationsSent extends \yii\db\ActiveRecord{
    
    //const
    const SLEEPING_USER = 0x0001;           // dec. 1
    
    public static function tableName() {
        return 'notificationsent';
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
