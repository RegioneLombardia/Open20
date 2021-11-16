<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

namespace open20\amos\discussioni\models;

use open20\amos\discussioni\models\base\DiscussioniRisposte as DiscussioniRisposteBase;
use yii\db\BaseActiveRecord;

/**
 * Class DiscussioniRisposte
 * This is the model class for table "discussioni_risposte".
 * @package open20\amos\discussioni\models
 * @deprecated from version 1.5.
 */
class DiscussioniRisposte extends DiscussioniRisposteBase
{
    /**
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        $this->getDiscussioniTopic()->one()->save(FALSE);
        //TODO Disabilito la spedizione delle notifiche in una situazione standard
        parent::afterSave($insert, $changedAttributes);
    }

}
