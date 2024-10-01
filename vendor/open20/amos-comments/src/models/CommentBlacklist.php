<?php

namespace open20\amos\comments\models;

use open20\amos\attachments\behaviors\FileBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comment_blacklist".
 *
 * @property-read null|string $iconMarker
 * @property-read \open20\amos\comments\models\url|null $urlEvent
 * @property-read null|\open20\amos\comments\models\color $colorEvent
 * @property-read \open20\amos\comments\models\title|null $titleEvent
 * @property-read null|array $events
 */
class CommentBlacklist extends \open20\amos\comments\models\base\CommentBlacklist
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [

            ]
        );

    }

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [

        ]);
    }

    /**
     * @return array
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    /**
     * @return array[]
     */
    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'word',
                'label' => $labels['word'],
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
