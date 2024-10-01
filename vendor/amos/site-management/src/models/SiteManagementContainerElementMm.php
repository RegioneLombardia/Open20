<?php

namespace amos\sitemanagement\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* This is the model class for table "site_management_container_element_mm".
*/
class SiteManagementContainerElementMm extends \amos\sitemanagement\models\base\SiteManagementContainerElementMm
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
                            'slug' => 'container_id',
                            'label' => $labels['container_id'],
                            'type' => 'integer'
                            ],
                                                    [
                            'slug' => 'element_id',
                            'label' => $labels['element_id'],
                            'type' => 'integer'
                            ],
                                ];
    }

        public function deleteWithRelations(){
            $pubblication = $this->siteManageContElemPubblication;
            if($pubblication) {
                /** @var  $pubblication SiteManageContElemPubblication */
                if(!empty($pubblication->siteManageContElemPubblicationClasses)) {
                    foreach ($pubblication->siteManageContElemPubblicationClasses as $class) {
                        $class->delete();
                    };
                }
                if(!empty($pubblication->siteManageContElemPubblicationUserMms)) {
                    foreach ($pubblication->siteManageContElemPubblicationUserMms as $userMm) {
                        $userMm->delete();
                    };
                }
                $pubblication->delete();
            }
            $this->delete();
        }


}
