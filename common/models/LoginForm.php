<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var integer Number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser or the session is manually destroyed.
     */
    public $rememberMe = true;

    /**
     * @var string User Role
     */
    public $ruolo; // TODO Translate

    private $_user;

    /**
     * Define Properties rules
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            ['ruolo', 'required', 'when' => function($model) { // TODO Translate
                    return (isset(\Yii::$app->params['template-amos']) && \Yii::$app->params['template-amos']);
                }],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['ruolo', 'safe'] // TODO Translate
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('amosplatform', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $loginTimeout = Yii::$app->params['loginTimeout'];

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $loginTimeout : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}
