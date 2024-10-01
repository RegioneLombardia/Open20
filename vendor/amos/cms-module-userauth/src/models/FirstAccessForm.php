<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\forms
 * @category   CategoryName
 */

namespace amos\userauth\models;

use common\models\User;
use open20\amos\core\module\Module;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\models\AmosModel;
use yii\helpers\ArrayHelper;

/**
 * First-Login form
 */
class FirstAccessForm extends AmosModel
{
    /**
     * 
     */
    const SCENARIO_CHECK_PRIVACY = 'check-privacy';
    const RESET_PASSWORD = 'reset-password';

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string Repeated Password
     */
    public $ripetiPassword;

    /**
     * @var string Password-reset token
     */
    public $token;

    /**
     * @var integer Privacy
     */
    public $privacy;
    
    /**
     * 
     * @var type
     */
    private $_user = false;

    /**
     * 
     * @return type
     */
    public function scenarios()
    {
        $parentScenarios = parent::scenarios();
        $scenarios       = ArrayHelper::merge(
            $parentScenarios,
            [
                self::SCENARIO_CHECK_PRIVACY => [
                    'username', 'password', 'ripetiPassword', 'privacy'
                ]
            ],
            [
                self::RESET_PASSWORD => [
                    'password', 'ripetiPassword',
                ]
            ]
        );

        return $scenarios;
    }

    /**
     * Define Properties rules
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'ripetiPassword'], 'safe'],
            [
                'ripetiPassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => Module::t('amoscore',"#first_access_pwd_compare_alert")
            ],
            
            [
                ['username'],
                'required',
                'on' => self::SCENARIO_CHECK_PRIVACY
            ],
            [
                ['password'],
                'required',
                'when' => function($model) {
                    return $this->validatePassword();
                },
                'whenClient' => 'validatePassword',
                'message' => Module::t('amoscore', "#first_access_pwd_alert")
            ],
            [
                ['ripetiPassword'],
                'required',
                'message' => Module::t('amoscore', "#first_access_pwd_2_alert")
            ],
            [
                ['privacy'],
                'required',
                'requiredValue' => 1,
                'message' => Module::t(
                    'amoscore',
                    "#first_access_privacy_alert_not_accepted"
                ),
                'on' => self::SCENARIO_CHECK_PRIVACY
            ],
            [['token'], 'string']
        ];
    }

    /**
     * 
     * @param type $attributes
     * @param type $params
     */
    public function validatePassword()
    {
        /**
         * Means
         * The password length must be greater than or equal to 8
         * The password must contain one or more uppercase characters
         * The password must contain one or more lowercase characters
         * The password must contain one or more numeric values
         * The password must contain one or more special characters
         * 
         */
        $re = '/(?=^.{8,}$)(?=.*\d)(?=.*[!@#$%^&*\.-]+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/m';
        $password = $this->password;
        
        preg_match_all($re, $password, $matches, PREG_SET_ORDER, 0);
        
        if (count($matches) == 0) {
            $this->addError(
                'password',
                AmosAdmin::t('amosadmin', 'La password deve contenere almeno: 8 caratteri, 1 o più lettere maiuscole e minuscole, 1 numero e 1 carattere speciale')
            );
            
            return false;
        }
        
        return true;
    }
 
    /**
     * Find User by Username
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Check Username existence
     * @param string $username Username
     * @return User|null
     */
    public function verifyUsername($username)
    {
        $user           = new User();
        $verifyUsername = $user->findOne(['username' => $username]);
        
        return $verifyUsername;
    }
}