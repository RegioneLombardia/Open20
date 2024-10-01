<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace open20\amos\admin\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\models\AmosModel as Model;
use yii\helpers\ArrayHelper;

/**
 * Class RegisterForm
 * @package open20\amos\admin\models
 */
class RegisterForm extends Model
{
    /**
     * @var string $nome
     */
    public $nome;

    /**
     * @var string $cognome
     */
    public $cognome;

    /**
     * @var string $email
     */
    public $email;

    /**
     * @var int $privacy
     */
    public $privacy;

    /**
     * @var string $moduleName
     */
    public $moduleName;

    /**
     * @var string $contextModelId
     */
    public $contextModelId;

    /**
     * @var string $captcha
     */
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $config =  [
            // username and password are both required
            [['nome'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_name_alert")],
            [['cognome'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_surname_alert")],
            [['email'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_email_alert")],
            [['privacy'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_privacy_alert")],
            [['privacy'], 'required', 'requiredValue' => 1, 'message' => AmosAdmin::t('amosadmin', "#register_privacy_alert_not_accepted")],
            [['nome', 'cognome', 'moduleName', 'contextModelId'], 'string'],
            ['email', 'email'],
        ];
        $moduleAdmin = \Yii::$app->getModule('amosadmin');
        if($moduleAdmin){
            if(!$moduleAdmin->disableRecatchaRegistration) {
                $config [] = ['reCaptcha', \open20\amos\admin\validators\ReCaptchaValidator::className(), 'message' => AmosAdmin::t('amosadmin', "#register_recaptcha_alert")];
            }
        }

        return $config;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'nome' => AmosAdmin::t('amosadmin', 'Nome'),
            'cognome' => AmosAdmin::t('amosadmin', 'Cognome'),
            'contextModelId' => AmosAdmin::t('amosadmin', '#contextModelId'),
        ]);
    }
}
