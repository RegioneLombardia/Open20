<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\validators
 * @category   CategoryName
 */

namespace frontend\validators;

use yii\validators\Validator;

/**
 * Class CapValidator
 * @package open20\amos\events\validators
 */
class PasswordValidator extends Validator
{
    /**
     * @param \open20\amos\core\record\Record $model
     * @param string $attribute
     * @return boolean
     */
    function validateAttribute($model, $attribute) {
        $password = $model->$attribute;
        $passwordErr = \Yii::t('preferenceuser', 'La password non rispetta i criteri di sicurezza: deve contenere almeno 8 caratteri, lettere maiuscole e minuscole ed almeno un numero');
        
        if (strlen($password) <= '8') {
            $this->addError($model, $attribute, $passwordErr);
            return false;
        }
        if (!preg_match("#[0-9]+#", $password)) {
            $this->addError($model, $attribute, $passwordErr);
            return false;
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            $this->addError($model, $attribute, $passwordErr);
            return false;
        }
        if (!preg_match("#[a-z]+#", $password)) {
           $this->addError($model, $attribute, $passwordErr);
            return false;
        }

        return true;
    }

}
