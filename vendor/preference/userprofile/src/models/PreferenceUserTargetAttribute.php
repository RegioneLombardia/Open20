<?php

namespace preference\userprofile\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "preference_user_target_attribute".
 */
class PreferenceUserTargetAttribute extends \preference\userprofile\models\base\PreferenceUserTargetAttribute
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
                'slug' => 'email',
                'label' => $labels['email'],
                'type' => 'string',
            ],
            [
                'slug' => 'validated_email_flag',
                'label' => $labels['validated_email_flag'],
                'type' => 'tinyint',
            ],
            [
                'slug' => 'phone',
                'label' => $labels['phone'],
                'type' => 'string',
            ],
            [
                'slug' => 'validated_phone_flag',
                'label' => $labels['validated_phone_flag'],
                'type' => 'tinyint',
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
        return null; //TODO
    }

/**
 * @return url event (calendar of activities)
 */
    public function getUrlEvent()
    {
        return null; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

/**
 * @return color event
 */
    public function getColorEvent()
    {
        return null; //TODO
    }

/**
 * @return title event
 */
    public function getTitleEvent()
    {
        return null; //TODO
    }

}
