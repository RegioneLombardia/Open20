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
class UserPassword extends Model
{
    public $password_current;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['password', 'password_repeat'], 'required'],
            [['password_current'], 'required', 'when' => function($model) {
                /** @var UserProfile $up */
                $up = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
                return !empty($up->user->password_hash);
            }],
            [['password'], 'compare', 'compareAttribute'=>'password_current', 'operator' => '!=', 'message'=>  Yii::t('preferenceuser', 'Scegli una password diversa da quella attuale')],
            [['password_repeat'],'compare', 'compareAttribute'=>'password', 'message'=>  Yii::t('preferenceuser', 'Le due password inserite non coincidono')],
            [['password_current'], 'currentPassword'],
            ['password', \preference\userprofile\validators\PasswordValidator::className()],
        ];
    }

    public function currentPassword($attribute, $params)
    {
        $up = UserProfile::findOne(['user_id' => Yii::$app->user->id]);
        if(!$up->user->validatePassword($this->$attribute)) {
            $this->addError($attribute, Yii::t('preferenceuser', 'Password errata'));
        }
    }

    public function attributeLabels()
    {
        return [
            'password_current' => Yii::t('preferenceuser', 'Password attuale'),
            'password' => Yii::t('preferenceuser', 'Nuova password'),
            'password_repeat' => Yii::t('preferenceuser', 'Conferma password'),
        ];
    }

}
