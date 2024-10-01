<?php

namespace open20\agid\dataset\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agid_dataset_content_type".
 */
class AgidDatasetContentType extends \open20\agid\dataset\models\base\AgidDatasetContentType
{
    public function representingColumn()
    {
        return [
			//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [];
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
        return ArrayHelper::merge(parent::rules(), []);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), []);
    }

    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'string',
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text',
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
