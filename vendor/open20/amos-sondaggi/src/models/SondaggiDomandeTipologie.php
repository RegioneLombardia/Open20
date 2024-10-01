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
 * Class SondaggiDomandeTipologie
 * This is the model class for table "sondaggi_domande_tipologie".
 * @package open20\amos\sondaggi\models
 */
class SondaggiDomandeTipologie extends \open20\amos\sondaggi\models\base\SondaggiDomandeTipologie
{
    public $byBassRuleCwh = true;
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'tipologia',
        ];
    }
}
