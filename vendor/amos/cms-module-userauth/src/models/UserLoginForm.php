<?php

namespace amos\userauth\models;

use amos\userauth\frontend\Module;
use open20\amos\core\user\User;
use yii\base\Model;


class UserLoginForm extends Model
{
    
    public $adminModule = null;
    
    
    /**
     * @var string The username
     */
    public $username;

    /**
     * @var string The password for a given username.
     */
    public $password;
    
    
    public $rememberme;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'validateUser']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('userauth.models.user.username'),
            'password' => Module::t('userauth.models.user.password'),
            'rememberme' => Module::t('userauth.models.user.rememberme'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['rememberme'], 'safe']
        ];
    }

    /**
     * Validate the current input data against an user.
     *
     * @return boolean
     */
    public function validateUser()
    {
        // check if the user is an active user
        if ($this->user && ($this->user->status != 10)) {
            return $this->addError('username', Module::t('userauth.models.userloginform.error.username_not_active'));
        }
        
        if (!$this->user || !$this->validateInputPassword()) {
            return $this->addError('password',
                    Module::t('userauth.models.userloginform.error.username_password'));
        }
    }
    private $_user;

    /**
     * Get user object, contains false if not found.
     *
     * @return \luya\userauth\models\User|boolean
     */
    public function getUser()
    {
        if ($this->_user === null) 
        {
            if ($this->adminModule->allowLoginWithEmailOrUsername) 
            {
                $this->_user = self::findByUsernameOrEmail($this->username);
            }else
            {
                $this->_user = self::findByUsername($this->username);
            }
        }

        return $this->_user;
    }

    public function validateInputPassword()
    {
        $ret = false;
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user && !empty($user->password_hash) && $user->validatePassword($this->password)) {
                $ret = true;
            }
        }
        return $ret;
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username, 'status' => User::STATUS_ACTIVE]);
    }

    /**
     * Find inactive user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsernameInactive($username)
    {
        return User::findOne(['username' => $username, 'status' => User::STATUS_DELETED]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $condition = ['email' => $email, 'status' => User::STATUS_ACTIVE];
        $allUsersByEmail = User::findAll($condition);
        if (count($allUsersByEmail) > 1) {
            return null;
        }
        return User::findOne($condition);
    }

    /**
     * Finds inactive user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmailInactive($email)
    {
        $condition = ['email' => $email, 'status' => User::STATUS_DELETED];
        $allUsersByEmail = User::findAll($condition);
        if (count($allUsersByEmail) > 1) {
            return null;
        }
        return User::findOne($condition);
    }

    /**
     * Finds user by username or email
     *
     * @param string $usernameOrEmail
     * @return static|null
     */
    public static function findByUsernameOrEmail($usernameOrEmail)
    {
        $user = self::findByUsername($usernameOrEmail);
        if (is_null($user)) {
            $user = self::findByEmail($usernameOrEmail);
        }
        return $user;
    }
}