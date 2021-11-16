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
use yii\db\Expression;

/**
 * Class ConversationQuery
 * @package open20\amos\chat\models\search
 */
class ConversationQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->alias('c');
        $this->select([
            'last_message_id' => new Expression('MAX([[c.id]])'),
            'contact_id' => new Expression('IF([[sender_id]] = :userId, [[receiver_id]], [[sender_id]])')
        ])
            ->innerJoin('user_profile profile1', 'profile1.user_id = receiver_id AND profile1.deleted_at IS NULL')
            ->innerJoin('user_profile profile2', 'profile2.user_id = sender_id AND profile2.deleted_at IS NULL')
            ->andWhere(['or',
                ['receiver_id' => new Expression(':userId'), 'is_deleted_by_receiver' => false],
                ['sender_id' => new Expression(':userId'), 'is_deleted_by_sender' => false],
            ])
            ->andWhere(['profile1.attivo' => 1])
            ->andWhere(['profile2.attivo' => 1])
            ->groupBy(['contact_id']);
    }

    /**
     * @param $userId
     * @return $this
     */
    public function forUser($userId)
    {
        return $this->addParams(['userId' => $userId]);
    }
}
