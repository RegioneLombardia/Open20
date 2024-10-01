<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\models
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\models;

/**
 * Class SondaggiDomandeCondizionate
 * This is the model class for table "sondaggi_domande_condizionate".
 * @package open20\amos\sondaggi\models
 */
class SondaggiDomandeCondizionate extends \open20\amos\sondaggi\models\base\SondaggiDomandeCondizionate
{
    public $byBassRuleCwh = true;
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'sondaggi_risposte_predefinite_id',
            'sondaggi_domande_id'
        ];
    }
}
