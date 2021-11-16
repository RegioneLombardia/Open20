<?php

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_seats".
 */
class EventSeats extends \open20\amos\events\models\base\EventSeats
{
    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'event_id',
                'label' => $labels['event_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'sector',
                'label' => $labels['sector'],
                'type' => 'string'
            ],
            [
                'slug' => 'row',
                'label' => $labels['row'],
                'type' => 'string'
            ],
            [
                'slug' => 'seat',
                'label' => $labels['seat'],
                'type' => 'string'
            ],
            [
                'slug' => 'type_of_assigned_participant',
                'label' => $labels['type_of_assigned_participant'],
                'type' => 'integer'
            ],
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'event_participant_companion_id',
                'label' => $labels['event_participant_companion_id'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    /**
     * @param $status
     * @return string
     */
    public function getLabelStatus($status = null){
        if(empty($status)){
            $status = $this->status;
        }
        switch ($status){
            case EventSeats::STATUS_EMPTY :
                return AmosEvents::t('amosevents', 'Libero');
                break;
            case EventSeats::STATUS_ASSIGNED :
                return AmosEvents::t('amosevents', 'Assegnato');
                break;
            case EventSeats::STATUS_TO_REASSIGN :
                return AmosEvents::t('amosevents', 'Da riassegnare');
                break;
            case EventSeats::STATUS_REASSIGNED :
                return AmosEvents::t('amosevents', 'Riassegnato');
                break;
            default:
              return AmosEvents::t('amosevents', 'Libero');
        }
    }

    /**
     * @param $sector
     * @param $event_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isEventSeatDeletable($sector, $event_id){
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $eventsModule->createModel('EventSeats');
        $count = $eventSeatsModel::find()
            ->andWhere(['sector' => $sector])
            ->andWhere(['event_id' => $event_id])
            ->andWhere(['!=', 'status', \open20\amos\events\models\EventSeats::STATUS_EMPTY])
            ->count();
        return $count == 0;

    }


    /**
     * @param $sector
     * @param $event_id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getSectorNSeatsForGroups(){
        $event_id = $this->event_id;
        $sector = $this->sector;
        /** @var AmosEvents $eventsModule */
        $eventsModule = AmosEvents::instance();
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $eventsModule->createModel('EventSeats');
        $count = $eventSeatsModel::find()
            ->andWhere(['event_id' =>$event_id])
            ->andWhere(['available_for_groups' => true])
            ->andWhere(['sector' => $sector])
            ->andWhere(['status' => EventSeats::STATUS_EMPTY])->count();
        return $count;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getSectorNamePlusSeatsForGroups(){
        return $this->sector . ' ('. $this->getSectorNSeatsForGroups().' posti)';
    }


    /**
     * @return string
     */
    public function getStringSeatAssignedTo(){
        $name = '';
        if($this->type_of_assigned_participant ==1 && !empty($this->user_id)){
            $name = $this->user->userProfile->nomeCognome;
        }else if(!empty($this->eventParticipantCompanion)) {
            $companion = $this->eventParticipantCompanion;
            $name = $companion->nome .' '. $companion->cognome;
        }
        return $name;
    }

    /**
     * @return string
     */
    public function getStringCoordinateSeat(){
        return AmosEvents::t('amosevents', 'Settore').': ' . $this->sector
            .' - '.  AmosEvents::t('amosevents', 'Fila') .': ' . $this->row
            .' - '.AmosEvents::t('amosevents', 'Posto').': ' . $this->seat;
    }


    /**
     * @return string
     */
    public function getTicketName(){
        return  $this->sector
            .'_'.  AmosEvents::t('amosevents', 'Fila') .'-' . $this->row
            .'_'.AmosEvents::t('amosevents', 'Posto').'_' . $this->seat;
    }

}
