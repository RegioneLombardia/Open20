<?php

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;
use Yii;

/**
 * This is the base-model class for table "event_communication".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $title
 * @property integer $communication_type
 * @property integer $n_users
 * @property string $text_email
 * @property string $subject_email
 * @property string $status
 * @property integer $is_automatic
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 * @property \open20\amos\events\models\EventCommunicationSent[] $eventCommunicationSents
 */
class  EventCommunication extends \open20\amos\core\record\Record
{
    const STATUS_USERS_TO_IMPORT = 'user_to_import';
    const STATUS_IMPORTING_USERS = 'importing_users';
    const STATUS_IMPORTED = 'imported';
    const STATUS_TO_SEND = 'to_send';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_NO_USER_TO_IMPORT = 'no_user_to_import';


    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_communication';
    }

    /**
     * @return array
     */
    public static function getLabelStatus()
    {
        return [
            self::STATUS_USERS_TO_IMPORT => AmosEvents::t('amosevents',"Da processare"),
            self::STATUS_IMPORTING_USERS =>  AmosEvents::t('amosevents','In preparazione'),
            self::STATUS_IMPORTED => AmosEvents::t('amosevents','In Coda'),
            self::STATUS_TO_SEND => AmosEvents::t('amosevents','Da inviare'),
            self::STATUS_SENDING => AmosEvents::t('amosevents','In invio'),
            self::STATUS_SENT => AmosEvents::t('amosevents','Invio completato'),
            self::STATUS_NO_USER_TO_IMPORT => AmosEvents::t('amosevents','Nessun utente trovato')
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','text_email', 'subject_email','communication_type'],'required'],
            [['mailup_sending_id', 'mailup_message_id', 'mailup_import_id', 'mailup_group_id', 'mailup_list_id'],'integer'],
            [['event_id', 'communication_type', 'n_users', 'created_by', 'updated_by', 'deleted_by','n_sent','is_automatic'], 'integer'],
            [['title','text_email', 'subject_email'], 'string'],
            [['sent_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['status'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\events\models\Event::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_id' => Yii::t('amosevents', 'event'),
            'communication_type' => Yii::t('amosevents', 'Destinatari  comunicazione'),
            'n_users' => Yii::t('amosevents', 'n user'),
            'text_email' => Yii::t('amosevents', 'Text'),
            'subject_email' => Yii::t('amosevents', 'Subject'),
            'status' => Yii::t('amosevents', 'Status'),
            'title' => Yii::t('amosevents', 'Title'),
            'sent_at' => Yii::t('amosevents', 'Send at'),
            'created_at' => Yii::t('amosevents', 'Created at'),
            'updated_at' => Yii::t('amosevents', 'Updated at'),
            'deleted_at' => Yii::t('amosevents', 'Deleted at'),
            'created_by' => Yii::t('amosevents', 'Created by'),
            'updated_by' => Yii::t('amosevents', 'Updated at'),
            'deleted_by' => Yii::t('amosevents', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\open20\amos\events\models\Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCommunicationSents()
    {
        return $this->hasMany(\open20\amos\events\models\EventCommunicationSent::className(), ['event_communication_id' => 'id']);
    }
}
