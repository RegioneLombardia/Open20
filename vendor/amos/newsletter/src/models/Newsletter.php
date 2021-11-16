<?php

namespace amos\newsletter\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "newsletter".
 */
class Newsletter extends \amos\newsletter\models\base\Newsletter
{
    public $serviceMailFields = [];

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
            ['serviceMailFields', 'safe']
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
                'slug' => 'newsletter_template_id',
                'label' => $labels['newsletter_template_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'subject',
                'label' => $labels['subject'],
                'type' => 'string'
            ],
            [
                'slug' => 'text',
                'label' => $labels['text'],
                'type' => 'text'
            ],
            [
                'slug' => 'welcome_type',
                'label' => $labels['welcome_type'],
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

    public function __toString()
    {
        return '';
    }

    /**
     *
     */
    public function loadDynamicFields(){
        $dynamicFields = $this->newsletterServiceMailFieldsMm;
        foreach ($dynamicFields as $field){
            $this->serviceMailFields []= $field->service_mail_field_id;
        }
    }


}
