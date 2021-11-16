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

use open20\amos\discussioni\models\base\DiscussioniCommenti as DiscussioniCommentiBase;
use yii\db\BaseActiveRecord;

/**
 * Class DiscussioniCommenti
 * This is the model class for table "discussioni_commenti".
 * @package open20\amos\discussioni\models
 * @deprecated from version 1.5.
 */
class DiscussioniCommenti extends DiscussioniCommentiBase
{
    /**
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $idTopic = $this->getDiscussioniRisposte()->one()['discussioni_topic_id'];
        DiscussioniTopic::findOne($idTopic)->save(FALSE);
        //TODO Per un comportamento standard elimino la spedizione di notifiche
        parent::afterSave($insert, $changedAttributes);
    }
}
