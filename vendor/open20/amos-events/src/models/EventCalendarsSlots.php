<?php

namespace open20\amos\events\models;

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


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

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


}
