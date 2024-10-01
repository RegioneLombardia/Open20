<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models\base
 * @category   CategoryName
 */

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;
use Yii;

/**
 * Class EventCalendars
 * This is the base-model class for table "event_calendars".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $title
 * @property string $description
 * @property string $short_description
 * @property string $ecosystem
 * @property string $group
 * @property integer $partner_user_id
 * @property string $date_start
 * @property string $date_end
 * @property string $hour_start
 * @property string $hour_end
 * @property integer $slot_duration
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 * @package open20\amos\events\models\base
 */
class EventCalendars extends \open20\amos\core\record\Record
{
    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_calendars';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'title', 'date_start', 'hour_start', 'hour_end', 'slot_duration'], 'required'],
            [['max_participant', 'record_id','break_time','event_id', 'slot_duration', 'created_by', 'updated_by', 'deleted_by','partner_user_id'], 'integer'],
            [['classname','description','short_description','group','ecosystem'], 'string'],
            [['date_start', 'date_end', 'hour_start', 'hour_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->eventsModule->model('Event'), 'targetAttribute' => ['event_id' => 'id']],
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
            'title' => Yii::t('amosevents', 'Title'),
            'description' => Yii::t('amosevents', 'Description'),
            'ecosystem' => Yii::t('amosevents', 'Ecosystem'),
            'group' => Yii::t('amosevents', 'Group'),
            'short_description' => Yii::t('amosevents', 'Elenco Partner'),
            'partner_user_id' => Yii::t('amosevents', 'User'),
            'date_start' => Yii::t('amosevents', 'Date start'),
            'date_end' => Yii::t('amosevents', 'Date end'),
            'hour_start' => Yii::t('amosevents', 'Hour start'),
            'hour_end' => Yii::t('amosevents', 'Hour end'),
            'slot_duration' => Yii::t('amosevents', 'Slot duration'),
            'created_at' => Yii::t('amosevents', 'Created at'),
            'updated_at' => Yii::t('amosevents', 'Updated at'),
            'deleted_at' => Yii::t('amosevents', 'Deleted at'),
            'created_by' => Yii::t('amosevents', 'Created by'),
            'updated_by' => Yii::t('amosevents', 'Updated by'),
            'deleted_by' => Yii::t('amosevents', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne($this->eventsModule->model('Event'), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCalendarsSlots()
    {
        return $this->hasMany($this->eventsModule->model('EventCalendarsSlots'), ['event_calendars_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'partner_user_id']);
    }
}
