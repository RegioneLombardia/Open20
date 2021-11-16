<?php

namespace amos\sitemanagement\models;

use open20\amos\attachments\behaviors\FileBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_management_container_elem_fields_val".
 */
class SiteManagementContainerElemFieldsVal extends \amos\sitemanagement\models\base\SiteManagementContainerElemFieldsVal
{
    public $attachFiles;

    public $file;

    /**
     * Adding the file behavior
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ]
        ]);
    }


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
            [['file'], 'file'],
            [['attachFiles'], 'file'],

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
                'slug' => 'container_elem_id',
                'label' => $labels['container_elem_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'field_id',
                'label' => $labels['field_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'value',
                'label' => $labels['value'],
                'type' => 'string'
            ],
        ];
    }

    /**
     * @return File
     */
    public function getFile()
    {
        if (empty($this->file)) {
            $query = $this->hasOneFile('file');
            $this->file = $query->one();
        }
        return $this->file;
    }

    /**
     * @return File[]
     */
    public function getAttachFiles()
    {
        if (empty($this->attachFiles)) {
            $query = $this->hasOneFile('attachFiles');
            $this->attachFiles = $query->one();
        }
        return $this->attachFiles;
    }
}
