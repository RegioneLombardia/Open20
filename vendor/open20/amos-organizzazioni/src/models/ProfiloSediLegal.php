<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models;

/**
 * Class ProfiloSediLegal
 * @package open20\amos\organizzazioni\models
 */
class ProfiloSediLegal extends ProfiloSedi
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->profilo_sedi_type_id = ProfiloSediTypes::TYPE_LEGAL_HEADQUARTER;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        
        if (!empty($this->organizzazioniModule->addRequired) && isset($this->organizzazioniModule->addRequired['ProfiloSediLegal'])) {
            $rules[] = [$this->organizzazioniModule->addRequired['ProfiloSediLegal'], 'required'];
        }
        
        return $rules;
    }
}
