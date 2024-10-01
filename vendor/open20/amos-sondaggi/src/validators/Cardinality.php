<?php

namespace open20\amos\sondaggi\validators;

/*
 * Classe che estende la Validator base per aggiungere
 * altri validatori alle domande custom, praticamente è necessaria
 * una classe per ogni nuovo metodo di validazione
 */

use yii\validators\Validator;
use open20\amos\sondaggi\AmosSondaggi;

class Cardinality extends Validator
{
    public $min;
    public $max;
    public $messageMin = 'Il numero di elementi selezionati non deve essere inferiore a {min}';
    public $messageMax = 'Il numero di elementi selezionati non deve essere superiore a {max}';

    public function validateAttribute($model, $attribute)
    {

        if (!empty($this->min) && count($model->$attribute) < $this->min) {
            $this->addError($model, $attribute,
                AmosSondaggi::t('amossondaggi', $this->messageMin, ['min' => $this->min]));
            return false;
        }

        if (!empty($this->max) && count($model->$attribute) > $this->max) {
            $this->addError($model, $attribute,
                AmosSondaggi::t('amossondaggi', $this->messageMax, ['max' => $this->max]));
            return false;
        }

        return true;
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $error_msg_min = AmosSondaggi::t('amossondaggi', $this->messageMin, ['min' => $this->min]);
        $error_msg_max = AmosSondaggi::t('amossondaggi', $this->messageMax, ['max' => $this->max]);

        $min = empty($this->min) ? 0 : $this->min;
        $max = empty($this->max) ? 0 : $this->max;

        return <<<JS
        var max = $max;
        var min = $min;
        var count = 0;
	if(typeof min != 'undefined' || typeof max != 'undefined'){
            if(typeof value != 'undefined'){
                if(Array.isArray(value)){
                        count = value.length;
                } else {
                        count = 1;
                }
            }
            if($("#div-$attribute").is(":visible")){
                if(typeof max != 'undefined' && count > max){
                    messages.push("$error_msg_max");
                    return false;
                } else if(typeof min != 'undefined' && count < min){
                    messages.push("$error_msg_min");
                    return false;
                }
                return false;
            }
	}		
        return true;
JS;
    }
}