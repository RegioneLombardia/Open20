<?php

/**
 * Aria S.p.A.
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
class CodiceAtecoValidator extends Validator
{
    /**
     * @param \open20\amos\core\record\Record $model
     * @param string $attribute
     * @return boolean
     */
    function validateAttribute($model, $attribute) {
        $ateco = $model->$attribute;      
        $atecoErr = \Yii::t('preferenceuser', 'Formato non corretto. Inserire il formato XX.XX.XX');
    
        if (!preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $ateco)) {
            $this->addError($model, $attribute, $atecoErr);
            return false;
        }

        return true;
    }
    
    public function clientValidateAttribute($model, $attribute, $view)
    {      
        $error_msg = \Yii::t('amosapp', 'Formato non corretto. Inserire il formato XX.XX.XX');
        return <<<JS
        
        var ateco = value;
        
        if( ateco == '' ){
            return '';
        }  
        if( ! /^\d{2}\.\d{2}\.\d{2}$/.test(ateco) ){
            messages.push( "$error_msg");
        }
               
        return true;
JS;


    }

}
