<?php

namespace preference\userprofile\models;

use open20\amos\admin\models\UserProfile;
use preference\userprofile\utility\UserInterestTagUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

/**
 * Model without table
 * 
 */
class UserEmail extends Model
{
    public $actualEmail;
    public $email;

    public function rules()
    {
        return [
            [['actualEmail', 'email'], 'email'],
            [['actualEmail', 'email'], 'required'],
            [['email'], 'compare', 'compareAttribute' => 'actualEmail', 'operator' => '!=', 'message' =>  Yii::t('preferenceuser', 'La nuova mail inserita deve essere differente a quella precedente')],
            [['actualEmail'], function ($attribute, $params, $validator) {
                $up = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
                if ($this->$attribute !=  $up->user->username) {
                    $this->addError($attribute, Yii::t('preferenceuser', 'La mail inserita non corrisponde alla email del profilo'));
                }
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'actualEmail' => Yii::t('preferenceuser', 'Email attuale'),
            'email' => Yii::t('preferenceuser', 'Nuova email'),
        ];
    }

}
