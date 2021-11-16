<?php

namespace open20\amos\admin\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
* This is the model class for table "user_profile_reactivation_request".
*/
class UserProfileReactivationRequest extends \open20\amos\admin\models\base\UserProfileReactivationRequest
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

    
    public static function getEditFields() {
        $labels = self::attributeLabels();

        return [
                                        [
                            'slug' => 'user_profile_id',
                            'label' => $labels['user_profile_id'],
                            'type' => 'integer'
                            ],
                                                    [
                            'slug' => 'message',
                            'label' => $labels['message'],
                            'type' => 'text'
                            ],
                                ];
    }

}
