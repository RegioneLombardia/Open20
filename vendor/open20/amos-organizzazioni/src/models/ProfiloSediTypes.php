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
 * Class ProfiloSediTypes
 * This is the model class for table "profilo_sedi_types".
 * @package open20\amos\organizzazioni\models
 */
class ProfiloSediTypes extends \open20\amos\organizzazioni\models\base\ProfiloSediTypes
{
    const TYPE_LEGAL_HEADQUARTER = 1;
    const TYPE_OPERATIVE_HEADQUARTER = 2;

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->active = 1;
            $this->read_only = 0;
        }
    }
}
