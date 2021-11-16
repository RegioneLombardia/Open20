<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\notificationmanager\AmosNotify;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class NotificationConf
 *
 * This is the model class for table "notificationconf".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $notifications_enabled
 * @property integer $notify_content_pubblication
 * @property integer $notify_comments
 * @property integer $notify_newsletter
 * @property integer $notify_ticket_faq_referee
 * @property integer $email
 * @property integer $sms
 * @property integer $contatto_accettato_flag
 * @property integer $contatti_suggeriti_email
 * @property integer $periodo_inattivita_flag
 * @property integer $contenuti_successo_email
 * @property integer $profilo_successo_email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 *
 * @property \open20\amos\core\user\User $user
 *
 * @package open20\amos\notificationmanager\models
 */
class NotificationConf extends ActiveRecord
{
    /**
     * @var AmosNotify $notifyModule
     */
    protected $notifyModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->notifyModule = AmosNotify::instance();
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notificationconf';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [[
                'user_id',
                'email',
                'sms',
                'notifications_enabled',
                'notify_content_pubblication',
                'notify_comments',
                'notify_newsletter',
                'notify_ticket_faq_referee',
                'contatto_accettato_flag',
                'contatti_suggeriti_email',
                'periodo_inattivita_flag',
                'contenuti_successo_email',
                'profilo_successo_email',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [['user_id'], 'required'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosNotify::t('amosnotify', 'ID'),
            'user_id' => AmosNotify::t('amosnotify', 'User ID'),
            'email' => AmosNotify::t('amosnotify', 'Email'),
            'sms' => AmosNotify::t('amosnotify', 'Sms'),
            'created_at' => AmosNotify::t('amosnotify', 'Created At'),
            'updated_at' => AmosNotify::t('amosnotify', 'Updated At'),
            'deleted_at' => AmosNotify::t('amosnotify', 'Deleted At'),
            'created_by' => AmosNotify::t('amosnotify', 'Created By'),
            'updated_by' => AmosNotify::t('amosnotify', 'Updated By'),
            'deleted_by' => AmosNotify::t('amosnotify', 'Deleted By')
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            "TimestampBehavior" => [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AmosAdmin::instance()->model('User'), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationLanguagePreference()
    {
        return $this->hasMany($this->notifyModule->model('NotificationLanguagePreferences'), ['user_id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationConfContents()
    {
        return $this->hasMany($this->notifyModule->model('NotificationConfContent'), ['notification_conf_id' => 'id']);
    }
}
