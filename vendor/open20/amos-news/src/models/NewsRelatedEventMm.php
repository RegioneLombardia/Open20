<?php

namespace open20\amos\news\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "news_related_documenti_mm".
 */
class NewsRelatedEventMm extends \open20\amos\news\models\base\NewsRelatedDocumentiMm
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
                'slug' => 'news_id',
                'label' => $labels['news_id'],
                'type' => 'integer',
            ],
            [
                'slug' => 'event_id',
                'label' => $labels['event_id'],
                'type' => 'integer',
            ],
        ];
    }
}
