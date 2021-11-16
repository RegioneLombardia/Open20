<?php
namespace preference\userprofile\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserOtpCode;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\TopicTagUtility;
use preference\userprofile\utility\UserProfileUtility;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Model without table
 * 
 */
class StepPersonalData extends Model
{
    public $name;
    public $surname;
    public $gender;
    public $birth_date;
    public $residence_province;
    public $residence_city;
    public $email;
    public $password;
    public $password_repeat;
    public $fiscal_code;
    public $auth_code;
    public $error_class_email = 'invalid-feedback d-block server-side-error';

    const SCENARIO_DEFAULT = 'default';
    const SCENARIO_EMAIL = 'email';
    const SCENARIO_OTP = 'otp';

    private $genderChoices = [
        'm' => 'M',
        'f' => 'F',
    ];

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['gender', 'fiscal_code'], 'safe'],
            [['name', 'surname'], 'string', 'max' => 255],
            [['birth_date'], 'date', 'format' => 'd/m/Y'],
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
            [['residence_province', 'residence_city'], 'integer'],
//            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
//            ['password', \preference\userprofile\validators\PasswordValidator::className()],
            // ['fiscal_code', 'required'],
            // ['fiscal_code', 'checkCodiceFiscale'],

            ['auth_code', 'required', 'on' => self::SCENARIO_OTP],
            ['auth_code', 'checkOpt', 'on' => self::SCENARIO_OTP],
            ['email', 'checkOptEmail', 'on' => self::SCENARIO_EMAIL],
        ];
    }

    public function scenarios()
    {
        $fields = [
            'name', 'surname', 'gender', 'birth_date', 'residence_province', 'residence_city',
            'email', 'password', 'password_repeat', 'fiscal_code', 'auth_code'
        ];
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DEFAULT] = $fields;
        $scenarios[self::SCENARIO_EMAIL] = $fields;
        $scenarios[self::SCENARIO_OTP] = $fields;
        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('preferenceuser', 'Nome'),
            'surname' => Yii::t('preferenceuser', 'Cognome'),
            'gender' => Yii::t('preferenceuser', 'Sesso'),
            'email' => Yii::t('preferenceuser', 'Email'),
            'birth_date' => Yii::t('preferenceuser', 'Data di nascita'),
            'residence_province' => Yii::t('preferenceuser', 'Provincia'),
            'residence_city' => Yii::t('preferenceuser', 'Comune'),
            'password' => Yii::t('preferenceuser', 'Password'),
            'password_confirmed' => Yii::t('preferenceuser', 'Ripeti passwod'),
            'fiscal_code' => Yii::t('preferenceuser', 'Codice fiscale'),
            'auth_code' => Yii::t('preferenceuser', 'Codice di validazione'),
        ];
    }

    public function getGenderChoices() 
    {
        return $this->genderChoices;
    }

    public function checkOptEmail($attribute, $params)
    {
        if ($this->$attribute != Yii::$app->session->get('IDM')['emailAddress']) {
            /** @var UserOtpCode $otp */
            $otp = UserProfileUtility::generateOPT();
            EmailUtility::sendUserMailValidationEmailOtp($otp->otp_code, $this->$attribute,'Lombardia Informa, validazione email');

            $this->addError($attribute, 'L\'email inserita è diversa da quella ricevuta dal sistema di autenticazione. E\' stata inviata una mail all\'indirizzo indicato con un codice di validazione da inserire nel campo qui a fianco. Il codice rimane valido per 5 min dall\'invio');
            $this->error_class_email = 'alert alert-success mt-1';
            $this->setScenario(self::SCENARIO_OTP);
        }
    }

    public function checkOpt($attribute, $params)
    {
        $code = $this->$attribute;
        if (UserOtpCode::isValidCodice($code, UserOtpCode::TYPE_AUTH_EMAIL)) {
            if (UserOtpCode::isExpired($code, UserOtpCode::TYPE_AUTH_EMAIL)) {
                $this->addError($attribute, 'Codice di validazione scaduto, premi su continua per inviarne un\'altro');
                // se scaduto butto via tutti i codici... se proprio ne verrà inviato un'altro...
                UserOtpCode::deleteAll(['session_id' => \Yii::$app->session->getId()]);
                $this->setScenario(self::SCENARIO_OTP);
            }
        } else {
            $this->addError($attribute, 'Codice di validazione non valido');
            $this->setScenario(self::SCENARIO_OTP);
        }
    }



}