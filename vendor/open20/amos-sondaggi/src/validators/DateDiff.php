<?php

namespace open20\amos\sondaggi\validators;

/*
 * Classe che estende la Validator base per aggiungere
 * altri validatori alle domande custom, praticamente è necessaria
 * una classe per ogni nuovo metodo di validazione
 */

use yii\validators\Validator;
use open20\amos\sondaggi\AmosSondaggi;

class DateDiff extends Validator
{
    public $value   = 18;
    public $message = 'The date is not within the requirements';
 
    public function validateAttribute($model, $attribute)
    {
        $year     = date('Y', strtotime($model->$attribute));
        $nowYear  = date('Y');
        $diffYear = $nowYear - $year;
        if ($diffYear == $this->value) {
            $now  = date('md');
            $data = date('md', strtotime($model->$attribute));
            if ($data <= $now) {
                return true;
            } 
        } else if ($diffYear > $this->value) {
            return true;
        }

        $this->addError($model, $attribute, \Yii::t('amossondaggi', $this->message));

        return false;
    }
}