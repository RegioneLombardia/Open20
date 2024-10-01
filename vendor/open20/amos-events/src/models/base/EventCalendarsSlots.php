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
 * Class EventCalendarsSlots
 * This is the base-model class for table "event_calendars_slots".
 *
 * @property integer $id
 * @property integer $event_calendars_id
 * @property string $date
 * @property string $hour_start
 * @property string $hour_end
 * @property integer $user_id
 * @property string $cellphone
 * @property string $affiliation
 * @property string $booked_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\core\user\User $user
 * @package open20\amos\events\models\base
 */
class  EventCalendarsSlots extends \open20\amos\core\record\Record
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
        return 'event_calendars_slots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_calendars_id', 'date', 'hour_start', 'hour_end'], 'required'],
            [['event_calendars_id', 'user_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['cellphone', 'affiliation', 'booked_at', 'hour_start', 'hour_end', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_calendars_id' => Yii::t('amosevents', 'Event'),
            'hour_end' => Yii::t('amosevents', 'Time end'),
            'date' => Yii::t('amosevents', 'Date'),
            'hour_start' => Yii::t('amosevents', 'Time start'),
            'user_id' => Yii::t('amosevents', 'User'),
            'affiliation' => Yii::t('amosevents', 'Affiliation'),
            'cellphone' => Yii::t('amosevents', 'Cellphone'),
            'booked_at' => Yii::t('amosevents', 'Booked at'),
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
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCalendars()
    {
        return $this->hasOne($this->eventsModule->model('EventCalendars'), ['id' => 'event_calendars_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventCalendarsSlotsBooked()
    {
        return $this->hasMany(\open20\amos\events\models\EventCalendarsSlotsBooked::className(), [ 'event_calendars_slots_id' => 'id']);
    }

}
