<?php

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event_calendars".
 */
class EventCalendars extends \open20\amos\events\models\base\EventCalendars
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

    /**
     * @return array
     */
    public static function getAvailableModel(){
        return [
            'openinnovation\landing\models\LandingCallhubSchede' => 'Schede progetto callhub'
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
                'slug' => 'event_id',
                'label' => $labels['event_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
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
                'slug' => 'hour_start',
                'label' => $labels['hour_start'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'hour_end',
                'label' => $labels['hour_end'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'slot_duration',
                'label' => $labels['slot_duration'],
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
     * @throws \Exception
     */
    public function generateSlots(){
        $dateStart = new \DateTime($this->date_start);
        $timeStart =  new \DateTime($this->hour_start);
        $timeEnd =  new \DateTime($this->hour_end);

//        if(!empty($this->date_end)){
//            $dateEnd = new \DateTime($this->date_end);
//            $dateStart->add(new \DateInterval('P1M'));
//        }

        $errors = [];


        $i = 0;
        while($timeStart < $timeEnd){
            /** @var EventCalendarsSlots $eventCalendarsSlotsModel */
            $slot = $this->eventsModule->createModel('EventCalendarsSlots');
            $slot->event_calendars_id = $this->id;
            $slot->date = $timeStart->format('Y-m-d');
            $slot->hour_start = $timeStart->format('H:i:s');
            $timeStartadded = $timeStart->add(new \DateInterval('PT'.($this->slot_duration).'M'));
            $slot->hour_end = $timeStartadded->format('H:i:s');

            if($timeStart <=  $timeEnd){
                $i++;
                $slot->save(false);
            }
            else {
               $errors []= AmosEvents::t('amosevents', 'Cannot create slot') .' '. \Yii::$app->formatter->asDate($slot->date) . ' => '
                   . \Yii::$app->formatter->asTime($slot->hour_start)  . ' - ' .$timeStartadded->format('H:i:s');
            }
        }
        if($i > 0){
            \Yii::$app->session->addFlash('success', AmosEvents::t('amosevents','Sono stati inseriri {n} slot', ['n' => $i]));
        }
        if(count($errors) > 0){
            $strError = implode("<br>", $errors);
            \Yii::$app->session->addFlash('danger', $strError);
        }
        return $errors;
    }

    /**
     * @param $user_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function hasUserBookedSlot($user_id){
        $count = EventCalendarsSlotsBooked::find()
            ->innerJoin('event_calendars_slots','event_calendars_slots.id = event_calendars_slots_booked.event_calendars_slots_id')
            ->andWhere(['event_calendars_slots.event_calendars_id' => $this->id])
            ->andWhere(['event_calendars_slots_booked.user_id' => $user_id])->count();
        return $count > 0;
    }

    /**
     * @return int|string
     */
    public function getNumberEmptySlots(){
        $count = $this->getEventCalendarsSlots()
            ->andWhere(['IS', 'user_id', null])->count();
        return $count;
    }

    /**
     * @return int|string
     */
    public function getTotNumberSlots(){
        $count = $this->getEventCalendarsSlots()->count();
        return $count;
    }
}
