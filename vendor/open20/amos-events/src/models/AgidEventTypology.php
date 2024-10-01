<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models
 * @category   CategoryName
 */

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;

/**
 * Class AgidEventTypology
 * This is the model class for table "agid_event_typology".
 * @package open20\amos\events\models
 */
class AgidEventTypology extends \open20\amos\events\models\base\AgidEventTypology
{
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
    public function getModelModuleName()
    {
        return AmosEvents::getModuleName();
    }
}
