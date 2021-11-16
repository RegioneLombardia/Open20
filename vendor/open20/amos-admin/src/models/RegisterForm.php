<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models
 * @category   CategoryName
 */

namespace open20\amos\admin\models;

use open20\amos\admin\AmosAdmin;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class LoginForm
 * @package open20\amos\admin\models
 */
class RegisterForm extends Model
{
    public $nome;
    public $cognome;
    public $email;
    public $privacy;

    /**
     * @var string $captcha
     */
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['nome'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_name_alert")],
            [['cognome'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_surname_alert")],
            [['email'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_email_alert")],
            [['privacy'], 'required', 'message' => AmosAdmin::t('amosadmin', "#register_privacy_alert")],
            [['privacy'], 'required', 'requiredValue' => 1, 'message' => AmosAdmin::t('amosadmin', "#register_privacy_alert_not_accepted")],
            [['nome', 'cognome'], 'string'],
            ['email', 'email'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'message' => AmosAdmin::t('amosadmin', "#register_recaptcha_alert")]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'nome' => AmosAdmin::t('amosadmin', 'Nome'),
            'cognome' => AmosAdmin::t('amosadmin', 'Cognome'),
        ]);
    }
}
