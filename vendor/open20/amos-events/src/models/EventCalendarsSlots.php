<?php

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_calendars_slots".
 */
class EventCalendarsSlots extends \open20\amos\events\models\base\EventCalendarsSlots
{
    public $date_start_from;
    public $date_start_to;
    public $date_end_from;
    public $date_end_to;

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
                    'date_start_from' => 'Da date_start',
                    'date_start_to' => 'A  date_start',
                    'date_end_from' => 'Da date_end',
                    'date_end_to' => 'A  date_end',
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'event_calendars_id',
                'label' => $labels['event_calendars_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'date_start',
                'label' => $labels['date_start'],
                'type' => 'date'
            ],
            [
                'slug' => 'date_end',
                'label' => $labels['date_end'],
                'type' => 'date'
            ],
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
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
     * @return string
     * @throws \Exception
     */
    public function getEndHourWithPause(){
        $end = new \DateTime($this->hour_end);
        if ($this->eventCalendars->break_time) {
            $interval = new \DateInterval("PT{$this->eventCalendars->break_time}M");
            $newDate = $end->sub($interval);
            return $newDate->format('H:i');
        }
        else {
            return $end->format('H:i');
        }
    }

    /**
     * @return bool
     */
    public function isSlotFull(){
        $count = $this->getEventCalendarsSlotsBooked()->count();
        if($count < $this->eventCalendars->max_participant){
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function canBook(){
        if($this->eventCalendars->hasUserBookedSlot(Yii::$app->user->id) == true){
            return false;
        };
        if($this->hasBookedFasciaOraria(\Yii::$app->user->id)){
            return false;
        }
        $bookedSlot = $this->getEventCalendarsSlotsBooked()->andWhere(['user_id' => \Yii::$app->user->id ])->count();
        if($bookedSlot){
            return false;
        }
        if(!$this->isSlotFull()){
            return true;
        }


        return false;
    }

    public function canPrenote(){
        if(\Yii::$app->user->isGuest){
            return false;
        }
        $isParticipant = \open20\amos\events\utility\EventsUtility::isEventParticipant($this->eventCalendars->event->id, \Yii::$app->user->id);
        if (!$isParticipant) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getStatusSlot(){
        $status = AmosEvents::t('amosevents', 'Posti disponibili');
        $booked = $this->getEventCalendarsSlotsBooked()->andWhere(['user_id' => \Yii::$app->user->id])->one();
//        $canBook = $this->canBook();
        if(!empty($booked)){
            $status = AmosEvents::t('amosevents', 'Prenotazione effettuata');
        }else if($this->isSlotFull()) {
            $status = AmosEvents::t('amosevents', 'Posti disponibili esauriti');
        }
        return $status;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public function isBookedByUser($user_id){
        $booked = $this->getEventCalendarsSlotsBooked()->andWhere(['user_id' => $user_id])->count();
        return ($booked > 0);
    }

    /**
     * @return int|string
     */
    public function getSlotsAvailable(){
        $available = $this->eventCalendars->max_participant - $this->getEventCalendarsSlotsBooked()->count();
        return $available;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function hasBookedFasciaOraria($user_id){
        $calendar = $this->eventCalendars;
        $count = EventCalendarsSlotsBooked::find()
            ->innerJoinWith('eventCalendarsSlots.eventCalendars')
            ->andWhere(['event_calendars.event_id' => $calendar->event_id])
            ->andWhere(['event_calendars_slots.hour_start' => $this->hour_start])
            ->andWhere(['event_calendars_slots_booked.user_id' => \Yii::$app->user->id])
            ->count();
//        var_dump(EventCalendarsSlotsBooked::find()
//            ->innerJoinWith('eventCalendarsSlots.eventCalendars')
//            ->andWhere(['event_calendars.event_id' => $calendar->event_id])
//            ->andWhere(['event_calendars_slots.hour_start' => $this->hour_start])
//            ->andWhere(['event_calendars_slots_booked.user_id' => \Yii::$app->user->id])->createCommand()->rawSql);
        if($count > 0){
            return true;
        }
        return false;
    }



}
