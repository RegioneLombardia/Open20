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
use open20\amos\core\user\User;
use yii\base\Model;

/**
 * Class ForgotPasswordForm
 *
 * Reset password form
 *
 * @package open20\amos\admin\models
 */
class ForgotPasswordForm extends Model
{
    /**
     * @var string Username
     */
    public $username;
    
    /**
     * @var string Social Security Number
     */
    public $codice_fiscale;

    /**
     * @var string user email
     */
    public $email;
    
    private $_user = false;
    
    /**
     * Define Properties rules
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['codice_fiscale'], 'safe'],
            [['username'], 'safe'],
            ['email', 'email'],
            ['email', 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            [['email'], 'required', 'message' => AmosAdmin::t('amosadmin', "#forgot_pwd_alert")],
        ];
    }
    
    /**
     * Check Username and Social Security Number existence and pairment
     * @param string $attribute_name the attribute currently being validated
     * @param array $params Reserved for future use
     * @return bool
     */
    public function verifica($attribute_name, $params) // TODO translate
    {
        if (empty($this->username) && empty($this->codice_fiscale)) {
            $this->addError($attribute_name, AmosAdmin::t('amosadmin', 'Almeno uno tra USERNAME e CODICE FISCALE deve essere specificato.')); // TODO translate
            
            return false;
        }
        
        return true;
    }
    
    /**
     * Labels for attributes
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => AmosAdmin::t('amosadmin', 'Email di registrazione'),
        ];
    }
    
    /**
     * Returns Logged-in User
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
     * @param string $username
     * @return User|null
     */
    public function verifyUsername($username)
    {
        $user = new User();
        $verifyUsername = $user->findOne(['username' => $username]);
        return $verifyUsername;
    }
    
    /**
     * Check Social Security Number existence
     * @param string $cf Social Security Number
     * @return UserProfile|null
     */
    public function verifySocialSecurityNumber($cf)
    {
        $user = new UserProfile();
        $verificacf = $user->findOne(['codice_fiscale' => $cf]); // TODO Translate
        return $verificacf;
    }


    /**
     * Verify email present in db.
     *
     * @param $email
     * @return User|null
     */
    public function verifyEmail($email)
    {
        $user = new User();
        $verifyEmail = $user->findOne(['email' => $email]);
        return $verifyEmail;
    }
}
