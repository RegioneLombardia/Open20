<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\models
 * @category   CategoryName
 */

namespace open20\agid\service\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agid_service_type".
 */
class AgidServiceType extends \open20\agid\service\models\base\AgidServiceType
{
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
