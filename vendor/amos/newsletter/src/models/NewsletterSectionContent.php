<?php

namespace amos\newsletter\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
* This is the model class for table "newsletter_section_content".
*/
class NewsletterSectionContent extends \amos\newsletter\models\base\NewsletterSectionContent{
public function representingColumn()
{
return [
//inserire il campo o i campi rappresentativi del modulo
];
}

public function attributeHints(){
return [
];
}

/**
* Returns the text hint for the specified attribute.
* @param string $attribute the attribute name
* @return string the attribute hint
*/
public function getAttributeHint($attribute) {
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


public static function getEditFields() {
$labels = self::attributeLabels();

return [
        [
        'slug' => 'newsletter_section_id',
        'label' => $labels['newsletter_section_id'],
        'type' => 'integer'
        ],
                [
        'slug' => 'newsletter_content_type_id',
        'label' => $labels['newsletter_content_type_id'],
        'type' => 'integer'
        ],
                [
        'slug' => 'model_content_classname',
        'label' => $labels['model_content_classname'],
        'type' => 'string'
        ],
                [
        'slug' => 'model_content_id',
        'label' => $labels['model_content_id'],
        'type' => 'integer'
        ],
                [
        'slug' => 'html',
        'label' => $labels['html'],
        'type' => 'text'
        ],
        ];
}

/**
* @return string marker path
*/
public function getIconMarker(){
return null; //TODO
}

/**
* If events are more than one, set 'array' => true in the calendarView in the index.
* @return array events
*/
public function getEvents() {
return NULL; //TODO
}

/**
* @return url event (calendar of activities)
*/
public function getUrlEvent() {
return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
}

/**
* @return color event 
*/
public function getColorEvent() {
return NULL; //TODO
}

/**
* @return title event
*/
public function getTitleEvent() {
return NULL; //TODO
}




}
