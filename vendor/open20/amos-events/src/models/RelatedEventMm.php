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
 * Class RelatedEventMm
 * This is the model class for table "related_event_mm".
 * @package open20\amos\events\models
 */
class RelatedEventMm extends base\RelatedEventMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'main_luogo_id',
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
