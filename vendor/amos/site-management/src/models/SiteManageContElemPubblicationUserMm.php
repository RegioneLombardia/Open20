<?php

namespace amos\sitemanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_manage_cont_elem_pubblication_user_mm".
 */
class SiteManageContElemPubblicationUserMm extends \amos\sitemanagement\models\base\SiteManageContElemPubblicationUserMm
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
                'slug' => 'cont_elem_pubblication_id',
                'label' => $labels['cont_elem_pubblication_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
        ];
    }
}
