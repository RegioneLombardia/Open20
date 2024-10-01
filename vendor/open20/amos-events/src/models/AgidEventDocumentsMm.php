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
 * Class AgidEventDocumentsMm
 * This is the model class for table "agid_event_documents_mm".
 * @package open20\amos\events\models
 */
class AgidEventDocumentsMm extends \open20\amos\events\models\base\AgidEventDocumentsMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'event_id',
            'document_id'
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
