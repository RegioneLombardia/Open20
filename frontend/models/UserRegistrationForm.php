<?php

namespace app\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\tag\models\Tag;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class UserRegistrationForm extends Model
{
    const WIZARD_SCENARIO = 'wizard';
    const UPDATE_SCENARIO = 'change';
    const UNSUBSCRIBE_SCENARIO = 'unsubscribe';
    const RETENTION_SCENARIO = 'retention';

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
    public $hiddenIdsRoot;

    public $arrayLabels = [
        self::WIZARD_SCENARIO => [
            'cancel' => 'Cancel',
            'submit' => '#text_button_register'
        ],
        self::UPDATE_SCENARIO => [
            'cancel' => 'Cancel',
            'submit' => 'Save'
        ],
        self::UNSUBSCRIBE_SCENARIO => [
            'cancel' => '#exit',
            'submit' => 'Confirm'
        ],
        self::RETENTION_SCENARIO => [
            'cancel' => 'Cancel',
            'submit' => 'Edit'
        ]
    ];

    /**
     * function rules
     */
    function rules()
    {
        return [
            [['user_profile_age_group_id', 'provincia_residenza_id', 'comune_residenza_id', 'user_profile_titoli_studio_id',
                'email_frequency_id', 'user_profile_stati_civili_id'], 'integer'],
            [['hiddenIdsRoot'], 'string'],
            [['email'], 'email'],
            [['privacy'], 'boolean'],
            [['privacy'], 'required', 'requiredValue' => 1, 'message' => AmosAdmin::t('amosadmin', "#register_privacy_alert_not_accepted"), 'on' => [self::WIZARD_SCENARIO, self::RETENTION_SCENARIO]],
            [['gender'], 'safe'],
            [['interestTagValues'], 'CheckTag', 'skipOnEmpty'=>false, 'on' => [self::WIZARD_SCENARIO, self::UPDATE_SCENARIO, self::RETENTION_SCENARIO]],
            [['email', 'name', 'surname'], 'required'],
            [['email_frequency_id'], 'required', 'on' => [self::WIZARD_SCENARIO, self::UPDATE_SCENARIO, self::RETENTION_SCENARIO]]
        ];
    }

    public function getScenarios()
    {
        $scenarios =
            [
                self::WIZARD_SCENARIO => ['name', 'surname', 'email', 'tags', 'emailFrequency', 'privacy'],
                self::UPDATE_SCENARIO => ['name', 'surname', 'email', 'tags', 'emailFrequency', 'delete', 'logout'],
                self::UNSUBSCRIBE_SCENARIO => ['name', 'surname', 'email', 'unsubscribe'],
                self::RETENTION_SCENARIO => ['name', 'surname', 'email', 'tags', 'emailFrequency', 'privacy']
            ];
        return $scenarios;
    }

    public function CheckTag($attribute, $params)
    {
        $keyMissing = false;
        $listCategoryError = '';
        foreach(json_decode($this->hiddenIdsRoot) as $id){
            if(!array_key_exists($id, $this->$attribute['simple-choice'])) {
                $listCategoryError = $this->addErrorCategoryTag($listCategoryError, Tag::findOne($id)->nome);
                $keyMissing = true;
            }
        }
        if($keyMissing)
        {
            $this->addError($attribute, AmosAdmin::t('amosadmin', "E' obbligatorio scegliere almeno un tag delle seguenti categorie prima di salvare il profilo: $listCategoryError"));
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

    public function inScenario($attribute) {
        $scenarios = $this->getScenarios();
        return in_array($attribute, $scenarios[$this->scenario]);
    }

    public function getButtonLabels($typeButton) {
        return Yii::t('amosapp', $this->arrayLabels[$this->scenario][$typeButton]);
    }

    private function addErrorCategoryTag($message, $category) {
        if(empty($message)) {
            return $category;
        } else {
            return "$message, $category";
        }
    }
}