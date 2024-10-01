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

use open20\amos\core\user\User;
use kartik\password\StrengthValidator;
use Yii;
use open20\amos\core\models\AmosModel as Model;
use open20\amos\admin\AmosAdmin;

/**
 * 
 */
class CambiaPasswordForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $vecchiaPassword;
    /**
     * 
     * @var type
     */
    public $nuovaPassword;
    /**
     * 
     * @var type
     */
    public $ripetiPassword;
    /**
     * 
     * @var type
     */
    public $username;

    /**
     * 
     * @var type
     */
    private $_user = false;

    /**
     * 
     */
    public function init()
    {
        parent::init();
        $this->username = $this->getUser()->username;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['vecchiaPassword', 'nuovaPassword', 'ripetiPassword'], 'string'],
            [['vecchiaPassword'], 'validatePassword'],
            [
                ['nuovaPassword'],
                StrengthValidator::class,
                'min' => 8,
                'digit' => 1,
                'special' => 1,
                'upper'=> 1,
                'lower' => 1,
                'userAttribute' => 'username'
            ],
            [
                'ripetiPassword',
                'compare',
                'compareAttribute' => 'nuovaPassword',
                'message' => AmosAdmin::t('amosadmin', '#passwords_dont_match')
            ],
            [
                ['vecchiaPassword', 'nuovaPassword', 'ripetiPassword'],
                'required'
            ],
        ];
    }

    /**
     * 
     * @return type
     */
    public function attributeLabels()
    {
        return [
            'vecchiaPassword' => AmosAdmin::t('amosadmin', 'Vecchia password'),
            'nuovaPassword' => AmosAdmin::t('amosadmin', 'Nuova password'),
            'ripetiPassword' => AmosAdmin::t('amosadmin', 'Ripeti nuova password'),
        ];
    }

    /**
     * 
     * @param type $attribute
     * @param type $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            /**
             * @var $user User
             */
            $user = $this->getUser();

            if (
                !$user
                || !$user->validatePassword($this->vecchiaPassword)
            ) {
                $this->addError(
                    $attribute,
                    AmosAdmin::t('amosadmin','Password inserita non coincide con la password attuale.')
                );
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = AmosAdmin::instance()
                ->createModel('User')
                ->findByUsername(
                    Yii::$app->user->getIdentity()->username
                );
        }

        return $this->_user;
    }
}
