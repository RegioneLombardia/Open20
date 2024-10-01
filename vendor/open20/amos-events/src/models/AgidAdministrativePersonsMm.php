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
 * Class AgidAdministrativePersonsMm
 * This is the model class for table "agid_event_administrative_persons_mm".
 * @package open20\amos\events\models
 */
class AgidAdministrativePersonsMm extends \open20\amos\events\models\base\AgidAdministrativePersonsMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'event_id',
            'person_id'
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
