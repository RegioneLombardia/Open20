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
use common\models\User;
use kartik\password\StrengthValidator;

/**
 * First-Login form
 */
class FirstAccessForm extends Model
{

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

    private $_user = false;

    /**
     * Define Properties rules
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'ripetiPassword'], 'safe'],
            [['password'], StrengthValidator::className(), 'min' => 8, 'preset' => 'normal', 'userAttribute' => 'username'],
            ['ripetiPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Le password non coincidono'],
            [['username', 'password', 'ripetiPassword'], 'required'],
            [['token'], 'string']
    ];
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
        $user = new User();
        $verifyUsername = $user->findOne(['username' => $username]);
        return $verifyUsername;
    }
}
