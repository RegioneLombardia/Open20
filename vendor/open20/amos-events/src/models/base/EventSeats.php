<?php

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;

/**
 * Class EventSeats
 *
 * This is the base-model class for table "event_seats".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $sector
 * @property string $row
 * @property string $seat
 * @property integer $type_of_assigned_participant
 * @property integer $user_id
 * @property integer $event_participant_companion_id
 * @property integer $available_for_groups
 * @property integer $automatic
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\events\models\EventParticipantCompanion $eventParticipantCompanion
 * @property \open20\amos\events\models\Event $event
 * @property \open20\amos\core\user\User $user
 *
 * @package open20\amos\events\models\base
 */
class EventSeats extends \open20\amos\core\record\Record
{
    const STATUS_EMPTY = 1;
    const STATUS_ASSIGNED = 2;
    const STATUS_TO_REASSIGN = 3;
    const STATUS_REASSIGNED = 4;

    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_seats';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->eventsModule = AmosEvents::instance();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'sector', 'row', 'seat'], 'required'],
            [['available_for_groups', 'automatic', 'event_id', 'type_of_assigned_participant', 'user_id', 'event_participant_companion_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['sector', 'row', 'seat', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['event_participant_companion_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->eventsModule->model('EventParticipantCompanion'), 'targetAttribute' => ['event_participant_companion_id' => 'id']],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->eventsModule->model('Event'), 'targetAttribute' => ['event_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['automatic', 'checkFlags'],
            [['automatic', 'available_for_groups'], 'boolean']
        ];
    }

    /**
     * @param $attribute
     */
    public function checkFlags($attribute)
    {
        if ($this->automatic === $this->available_for_groups && ($this->automatic == 1 && $this->available_for_groups == 1)) {
            $this->addError('automatic', AmosEvents::t('amosevents', "I flag 'automatici' e 'fruibile per i gruppi' devono essere mutualmente esclusivi"));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEvents::t('amosevents', 'ID'),
            'event_id' => AmosEvents::t('amosevents', 'Event'),
            'sector' => AmosEvents::t('amosevents', 'Sector'),
            'row' => AmosEvents::t('amosevents', 'Row'),
            'seat' => AmosEvents::t('amosevents', 'Seat'),
            'type_of_assigned_participant' => AmosEvents::t('amosevents', 'Type'),
            'user_id' => AmosEvents::t('amosevents', 'User'),
            'available_for_groups' => AmosEvents::t('amosevents', 'Fruibile ai gruppi'),
            'automatic' => AmosEvents::t('amosevents', 'Automatico'),
            'event_participant_companion_id' => AmosEvents::t('amosevents', 'Companion/Member of a group'),
            'created_at' => AmosEvents::t('amosevents', 'Created at'),
            'updated_at' => AmosEvents::t('amosevents', 'Updated at'),
            'deleted_at' => AmosEvents::t('amosevents', 'Deleted at'),
            'created_by' => AmosEvents::t('amosevents', 'Created by'),
            'updated_by' => AmosEvents::t('amosevents', 'Updated by'),
            'deleted_by' => AmosEvents::t('amosevents', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventParticipantCompanion()
    {
        return $this->hasOne($this->eventsModule->model('EventParticipantCompanion'), ['id' => 'event_participant_companion_id']);
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
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
}
