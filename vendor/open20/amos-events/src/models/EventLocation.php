<?php

namespace open20\amos\events\models;

use open20\amos\organizzazioni\components\EventPlacesComponents;
use Yii;
use yii\helpers\ArrayHelper;

use open20\amos\core\interfaces\ViewModelInterface;

/**
 * This is the model class for table "event_location".
 */
class EventLocation extends \open20\amos\events\models\base\EventLocation implements
    ViewModelInterface
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
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'string'
            ],
            [
                'slug' => 'event_place_id',
                'label' => $labels['event_place_id'],
                'type' => 'string'
            ],
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        \open20\amos\events\components\EventPlacesComponents::checkPlace($this->event_place_id);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        //
    }


    /**
     * @inheritdoc
     */
    public function getFullViewUrl()
    {
        //
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
     */
    public function getAddressField()
    {
            if (is_null($this->eventPlaces)) {
                return '-';
            }
            return ($this->eventPlaces->postal_code ? '(' . $this->eventPlaces->postal_code . ')' : '') .
                ($this->eventPlaces->region ? ' ' . $this->eventPlaces->region : '') .
                ($this->eventPlaces->city ? ' ' . $this->eventPlaces->city : '') .
                ($this->eventPlaces->address ? ' ' . $this->eventPlaces->address : '') .
                ($this->eventPlaces->street_number ? ' ' . $this->eventPlaces->street_number : '');
    }


}
