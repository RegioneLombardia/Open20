<?php

namespace open20\amos\events\models\base;

use Yii;

/**
 * This is the base-model class for table "event_email_templates".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $save_the_date
 * @property string $save_the_date_subject
 * @property string $registration_email
 * @property string $registration_email_subject
 * @property string $confirm_registration
 * @property string $confirm_registration_subject
 * @property string $info_waiting_list
 * @property string $info_waiting_list_subject
 * @property string $send_tickets
 * @property string $send_tickets_subject
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 */
class  EventEmailTemplates extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_email_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['save_the_date', 'save_the_date_subject', 'registration_email', 'registration_email_subject', 'confirm_registration', 'confirm_registration_subject', 'info_waiting_list', 'info_waiting_list_subject', 'send_tickets', 'send_tickets_subject'], 'string'],
            [['test_email_1', 'test_email_2', 'test_email_3', 'test_email_4', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
            'event_id' => Yii::t('amosevents', 'Event'),
            'save_the_date' => Yii::t('amosevents', 'Save the date'),
            'save_the_date_subject' => Yii::t('amosevents', 'Save the date subject'),
            'registration_email' => Yii::t('amosevents', 'Registration email'),
            'registration_email_subject' => Yii::t('amosevents', 'Registration email subject'),
            'confirm_registration' => Yii::t('amosevents', 'Confirm registration'),
            'confirm_registration_subject' => Yii::t('amosevents', 'Confirm registration subject'),
            'info_waiting_list' => Yii::t('amosevents', 'Info waiting list'),
            'info_waiting_list_subject' => Yii::t('amosevents', 'Info waiting list subject'),
            'send_tickets' => Yii::t('amosevents', 'Send tickets'),
            'send_tickets_subject' => Yii::t('amosevents', 'Send tickets subject'),
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
}
