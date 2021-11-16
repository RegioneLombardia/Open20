<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\models\search;

use yii\db\ActiveQuery;

/**
 * Class MessageQuery
 * @package open20\amos\chat\models\search
 */
class MessageQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->alias('m');
    }

    /**
     * @param $userId
     * @param $contactId
     * @return $this
     */
    public function between($userId, $contactId)
    {
        return $this->andWhere(['or',
            ['sender_id' => $contactId, 'receiver_id' => $userId, 'is_deleted_by_receiver' => false],
            ['sender_id' => $userId, 'receiver_id' => $contactId, 'is_deleted_by_sender' => false],
            ])
            ->innerJoin('user_profile profile1', 'profile1.user_id = receiver_id AND profile1.deleted_at IS NULL')
            ->innerJoin('user_profile profile2', 'profile2.user_id = sender_id AND profile2.deleted_at IS NULL')
            ->andWhere(['profile1.attivo' => 1])
            ->andWhere(['profile2.attivo' => 1]);
    }
}
