<?php

namespace amos\newsletter\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
* This is the model class for table "newsletter_template_content".
*/
class NewsletterTemplateContent extends \amos\newsletter\models\base\NewsletterTemplateContent{
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
        'slug' => 'newsletter_template_id',
        'label' => $labels['newsletter_template_id'],
        'type' => 'integer'
        ],
                [
        'slug' => 'view_path_column_1',
        'label' => $labels['view_path_column_1'],
        'type' => 'string'
        ],
                [
        'slug' => 'view_path_column_2',
        'label' => $labels['view_path_column_2'],
        'type' => 'string'
        ],
                [
        'slug' => 'model_content_classname',
        'label' => $labels['model_content_classname'],
        'type' => 'string'
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
