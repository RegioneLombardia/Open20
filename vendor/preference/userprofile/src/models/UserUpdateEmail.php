<?php

namespace preference\userprofile\models;

use open20\amos\admin\models\UserOtpCode;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use preference\userprofile\utility\UserProfileUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\helpers\VarDumper;

/**
 * Model without table
 * 
 */
class UserUpdateEmail extends Model
{
    public $email;
    public $auth_code;
    public $error_class_email = 'invalid-feedback d-block server-side-error';

    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_EMAIL = 'email';
    const SCENARIO_OTP = 'otp';

    public function rules()
    {
        return [
            [['email'], 'email'],
            [['email'], function ($attribute, $params, $validator) {
                $upm = User::findOne(['email' => $this->$attribute]);
                if (!empty($upm)) {
                    $this->addError($attribute, Yii::t('preferenceuser', 'La email inserita è già registrata'));
                }

                $upm = User::findOne(['username' => $this->$attribute]);
                if (!empty($upm)) {
                    $this->addError($attribute, Yii::t('preferenceuser', 'La email inserita è già registrata'));
                }
            }],
            [['email'], 'required'],
            [['error_class_email'], 'safe'],


            ['auth_code', 'required', 'on' => self::SCENARIO_OTP],
            ['auth_code', 'checkOpt', 'on' => self::SCENARIO_OTP],
            ['email', 'checkOptEmail', 'on' => self::SCENARIO_EMAIL],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('preferenceuser', 'Nuova email'),
            'auth_code' => Yii::t('preferenceuser', 'Codice di validazione'),
        ];
    }

    public function scenarios()
    {
        $fields = [
            'email', 'auth_code'
        ];
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = $fields;
        $scenarios[self::SCENARIO_EMAIL] = $fields;
        $scenarios[self::SCENARIO_OTP] = $fields;
        return $scenarios;
    }

    public function checkOptEmail($attribute, $params)
    {
        if ($this->$attribute != Yii::$app->session->get('IDM')['emailAddress']) {

            /** @var UserOtpCode $otp */
            $otp = UserProfileUtility::generateOPT(Yii::$app->user);
            EmailUtility::sendUserMailValidationEmailOtp($otp->otp_code, $this->$attribute,'Lombardia Informa, validazione email');

            $this->addError($attribute, 'E\' stata inviata una mail all\'indirizzo indicato con un codice di validazione da inserire nel campo qui sotto. Il codice rimane valido per 5 min dall\'invio');
            $this->error_class_email = 'alert alert-success mt-1';
            $this->setScenario(self::SCENARIO_OTP);
        }
    }

    public function checkOpt($attribute, $params)
    {
        $code = $this->$attribute;
        if (UserOtpCode::isValidCodice($code, UserOtpCode::TYPE_AUTH_EMAIL)) {
            if (UserOtpCode::isExpired($code, UserOtpCode::TYPE_AUTH_EMAIL)) {
                $this->addError($attribute, 'Codice di validazione scaduto, premi su aggiorna per inviarne un\'altro');
                // se scaduto butto via tutti i codici... se proprio ne verrà inviato un'altro...
                UserOtpCode::deleteAll(['user_id' => Yii::$app->user->id, 'type' => UserOtpCode::TYPE_AUTH_EMAIL]);
                $this->setScenario(self::SCENARIO_OTP);
                $this->auth_code = null;
            }
        } else {
            $this->addError($attribute, 'Codice di validazione non valido');
            $this->setScenario(self::SCENARIO_OTP);
        }
    }

}
