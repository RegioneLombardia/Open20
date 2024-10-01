<?php

namespace amos\sitemanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* This is the model class for table "site_management_container".
*/
class SiteManagementContainer extends \amos\sitemanagement\models\base\SiteManagementContainer
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
                            'slug' => 'section_id',
                            'label' => $labels['section_id'],
                            'type' => 'integer'
                            ],
                                                    [
                            'slug' => 'is_masonry',
                            'label' => $labels['is_masonry'],
                            'type' => 'integer'
                            ],
                                                    [
                            'slug' => 'title',
                            'label' => $labels['title'],
                            'type' => 'string'
                            ],
                                                    [
                            'slug' => 'description',
                            'label' => $labels['description'],
                            'type' => 'text'
                            ],
                                ];
    }

    /**
     * @return int
     */
    public function getLastContainerOrder(){
        $order = $this->getSiteManagementContainerElementMms()->max('elem_order');
        if(empty($order)){
            $order = 0;
        }
        return $order;
    }
}
