<?php

namespace amos\sitemanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_management_template".
 */
class SiteManagementTemplate extends \amos\sitemanagement\models\base\SiteManagementTemplate
{
    public $fieldTypes;

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
            ['fieldTypes', 'safe']
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
                'slug' => 'name',
                'label' => $labels['name'],
                'type' => 'integer'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'view_path',
                'label' => $labels['view_path'],
                'type' => 'string'
            ],
        ];
    }
}
