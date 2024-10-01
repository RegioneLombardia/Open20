<?php

namespace open20\amos\collaborations\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "collaboration_target_countries".
 *
 * @property-read null|string $iconMarker
 * @property-read \open20\amos\collaborations\models\url|null $urlEvent
 * @property-read \open20\amos\collaborations\models\color|null $colorEvent
 * @property-read null|\open20\amos\collaborations\models\title $titleEvent
 * @property-read null|array $events
 */
class CollaborationTargetCountries extends \open20\amos\collaborations\models\base\CollaborationTargetCountries
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
                'slug' => 'alpha_2',
                'label' => $labels['alpha_2'],
                'type' => 'string'
            ],
            [
                'slug' => 'alpha_3',
                'label' => $labels['alpha_3'],
                'type' => 'string'
            ],
            [
                'slug' => 'country_code',
                'label' => $labels['country_code'],
                'type' => 'integer'
            ],
            [
                'slug' => 'iso_3166_2',
                'label' => $labels['iso_3166_2'],
                'type' => 'string'
            ],
            [
                'slug' => 'region',
                'label' => $labels['region'],
                'type' => 'string'
            ],
            [
                'slug' => 'sub_region',
                'label' => $labels['sub_region'],
                'type' => 'string'
            ],
            [
                'slug' => 'intermediate_region',
                'label' => $labels['intermediate_region'],
                'type' => 'string'
            ],
            [
                'slug' => 'region_code',
                'label' => $labels['region_code'],
                'type' => 'integer'
            ],
            [
                'slug' => 'sub_region_code',
                'label' => $labels['sub_region_code'],
                'type' => 'integer'
            ],
            [
                'slug' => 'intermediate_region_code',
                'label' => $labels['intermediate_region_code'],
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
