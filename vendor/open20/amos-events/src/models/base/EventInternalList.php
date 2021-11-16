<?php

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;
use Yii;

/**
 * This is the base-model class for table "event_internal_list".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $name
 * @property string $description
 * @property string $template
 * @property string $status
 * @property string $query_sql
 * @property string $active_query
 * @property string $search_params
 * @property integer $n_user_found
 * @property integer $mailup_sending_id
 * @property integer $mailup_message_id
 * @property integer $mailup_import_id
 * @property integer $mailup_group_id
 * @property integer $mailup_list_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  EventInternalList extends \open20\amos\core\record\Record
{

    public $isSearch = false;

    const STATUS_USERS_TO_IMPORT = 'user_to_import';
    const STATUS_IMPORTING_USERS = 'importing_users';
    const STATUS_IMPORTED = 'imported';
    const STATUS_TO_SEND = 'to_send';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_NO_USER_TO_IMPORT = 'no_user_to_import';

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
    public static function tableName()
    {
        return 'event_internal_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'name'], 'required'],
            [['mailup_sending_id', 'mailup_message_id', 'mailup_import_id', 'mailup_group_id', 'mailup_list_id',
                'event_id', 'n_user_found', 'created_by', 'updated_by', 'deleted_by', 'n_sent'], 'integer'],
            [['description', 'query_sql','active_query', 'search_params'], 'string'],
            [['status', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['template', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_id' => Yii::t('amosevents', 'Event'),
            'name' => Yii::t('amosevents', 'Title'),
            'description' => Yii::t('amosevents', 'Description'),
            'query_sql' => Yii::t('amosevents', 'Query Sql'),
            'search_params' => Yii::t('amosevents', 'Search params'),
            'n_user_found' => Yii::t('amosevents', 'N users found'),
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
    public function getEventInvitationSent()
    {
        return $this->hasMany(\open20\amos\events\models\EventInvitationSent::className(), ['event_internal_list_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\open20\amos\events\models\Event::className(), ['id' => 'event_id']);
    }
}
