<?php

namespace amos\sitemanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* This is the model class for table "site_management_template_fields".
*/
class SiteManagementTemplateFields extends \amos\sitemanagement\models\base\SiteManagementTemplateFields
{
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


    public function getEditFields() {
        $labels = $this->attributeLabels();

        return [
                                        [
                            'slug' => 'template_id',
                            'label' => $labels['template_id'],
                            'type' => 'integer'
                            ],
                                                    [
                            'slug' => 'field_id',
                            'label' => $labels['field_id'],
                            'type' => 'integer'
                            ],
                                ];
    }

    /**
     * @return File[]
     */
    public function getAttachFiles()
    {
        if (empty($this->attachFiles)) {
            $query = $this->hasMultipleFiles('attachFiles');
            $this->attachFiles = $query->all();
        }
        return $this->attachFiles;
    }
}
