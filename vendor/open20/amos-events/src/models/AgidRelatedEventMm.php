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
 * Class AgidRelatedEventMm
 * This is the model class for table "agid_related_event_mm".
 * @package open20\amos\events\models
 */
class AgidRelatedEventMm extends \open20\amos\events\models\base\AgidRelatedEventMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'main_event_id',
            'related_event_id'
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
