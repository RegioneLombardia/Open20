<?php

namespace backend\modules\userimporter\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_import_task_user".
 */
class UserImportTaskUser extends \backend\modules\userimporter\models\base\UserImportTaskUser
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
                'slug' => 'user_import_task_id',
                'label' => $labels['user_import_task_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'integer'
            ],
            [
                'slug' => 'surname',
                'label' => $labels['surname'],
                'type' => 'string'
            ],
            [
                'slug' => 'Email',
                'label' => $labels['Email'],
                'type' => 'string'
            ],
            [
                'slug' => 'Company',
                'label' => $labels['Company'],
                'type' => 'string'
            ],
            [
                'slug' => 'token',
                'label' => $labels['token'],
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
