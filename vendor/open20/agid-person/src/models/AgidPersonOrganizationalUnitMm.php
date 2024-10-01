<?php

namespace open20\agid\person\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agid_person_organizational_unit_mm".
 */
class AgidPersonOrganizationalUnitMm extends \open20\agid\person\models\base\AgidPersonOrganizationalUnitMm
{
    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'agid_organizational_unit_id',
                'label' => $labels['agid_organizational_unit_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'agid_person_id',
                'label' => $labels['agid_person_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'role',
                'label' => $labels['role'],
                'type' => 'string'
            ],
            [
                'slug' => 'function',
                'label' => $labels['function'],
                'type' => 'text'
            ],
        ];
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
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

    public function attributeHints()
    {
        return [
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
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
