<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\validators
 * @category   CategoryName
 */

namespace open20\amos\events\validators;

use open20\amos\events\AmosEvents;
use yii\validators\Validator;

/**
 * Class CapValidator
 * @package open20\amos\events\validators
 */
class CapValidator extends Validator
{
    /**
     * @param \open20\amos\core\record\Record $model
     * @param string $attribute
     * @return boolean
     */
    function validateAttribute($model, $attribute)
    {
        $pi = $model->$attribute;
        if (strlen($pi) != 5) {
            $this->addError($model, $attribute, AmosEvents::t('amosevents', 'Not valid CAP. Length does not comply.'));
            return false;
        }
        if (!is_numeric($pi)) {
            $this->addError($model, $attribute, AmosEvents::t('amosevents', 'Not valid CAP. It presents non-numeric values.'));
            return false;
        }
        return true;
    }
}
