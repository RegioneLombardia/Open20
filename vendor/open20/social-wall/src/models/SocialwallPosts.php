<?php

namespace open20\socialwall\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "socialwall_posts".
 */
class SocialwallPosts extends \open20\socialwall\models\base\SocialwallPosts
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


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'type',
                'label' => $labels['type'],
                'type' => 'string'
            ],
            [
                'slug' => 'profile_name',
                'label' => $labels['profile_name'],
                'type' => 'string'
            ],
            [
                'slug' => 'text',
                'label' => $labels['text'],
                'type' => 'text'
            ],
            [
                'slug' => 'permalink',
                'label' => $labels['permalink'],
                'type' => 'text'
            ],
            [
                'slug' => 'publication_date',
                'label' => $labels['publication_date'],
                'type' => 'datetime'
            ],
            [
                'slug' => 'profile_url',
                'label' => $labels['profile_url'],
                'type' => 'string'
            ],
            [
                'slug' => 'media_type',
                'label' => $labels['media_type'],
                'type' => 'string'
            ],
            [
                'slug' => 'search_tags',
                'label' => $labels['search_tags'],
                'type' => 'string'
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
