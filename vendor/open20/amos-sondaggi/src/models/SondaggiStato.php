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
 * Class SondaggiStato
 * This is the model class for table "sondaggi_stato".
 * @package open20\amos\sondaggi\models
 */
class SondaggiStato extends \open20\amos\sondaggi\models\base\SondaggiStato
{
    public $byBassRuleCwh = true;
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'descrizione'
        ];
    }
}
