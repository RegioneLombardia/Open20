<?php

namespace frontend\modules\userprofiling\models;

use open20\amos\admin\AmosAdmin;
use yii\base\Model;


class UserRegistrationForm extends Model
{
    public $name;
    public $email;
    public $surname;
    public $gender;
    public $user_profile_age_group_id;
    public $provincia_residenza_id;
    public $comune_residenza_id;
    public $user_profile_titoli_studio_id;
    public $interestTagValues;
    public $email_frequency_id;
    public $user_profile_stati_civili_id;

    public $privacy;

    /**
     * function rules
     */
    function rules()
    {
        return [
            [['user_profile_age_group_id', 'provincia_residenza_id', 'comune_residenza_id', 'user_profile_titoli_studio_id',
                'email_frequency_id', 'user_profile_stati_civili_id'], 'integer'],
            [['email'], 'email'],
            [['privacy'], 'boolean'],
            [['privacy'], 'required', 'requiredValue' => 1, 'message' => AmosAdmin::t('amosadmin', "#register_privacy_alert_not_accepted")],
            [['gender'], 'safe'],
            [['interestTagValues'], 'CheckTag', 'skipOnEmpty'=>false],
            [['email', 'name', 'surname', 'privacy', 'email_frequency_id'], 'required'],
        ];
    }

    public function CheckTag($attribute, $params)
    {

        $tot = count($this->$attribute);
        if($tot == 0)
        {
            $this->addError($attribute, AmosAdmin::t('amosadmin', "E' obbligatorio scegliere almeno un tag prima di salvare il profilo"));
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => AmosAdmin::t('amosadmin', 'Nome'),
            'surname' => AmosAdmin::t('amosadmin', 'Cognome'),
            'gender' => AmosAdmin::t('amosadmin', 'Sesso'),
            'privacy' => AmosAdmin::t('amosadmin', 'Accettazione privacy'),
            'user_profile_age_group_id' => AmosAdmin::t('amosadmin', 'Age Group Id'),
            'provincia_residenza_id' => AmosAdmin::t('amosadmin', 'Provincia'),
            'comune_residenza_id' => AmosAdmin::t('amosadmin', 'Comune'),
            'user_profile_titoli_studio_id' => AmosAdmin::t('amosadmin', 'Titolo di studio'),
            'interestTagValues' => AmosAdmin::t('amosadmin', 'Interessi'),
            'email_frequency_id' =>  AmosAdmin::tHtml('amosadmin', 'Frequenza notifica (scelta obbligatoria)'),
        ];
    }

}