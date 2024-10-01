<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\models\base;

use open20\amos\chat\DataProvider;
use open20\amos\chat\models\search\ConversationQuery;
use open20\amos\core\record\Record;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class Conversation
 * @package open20\amos\chat\models\base
 *
 * @property int $user_id
 * @property int contact_id
 * @property int last_message_id
 *
 * @property-read \open20\amos\chat\models\Message $lastMessage
 * @property-read \open20\amos\chat\models\Message[] $newMessages
 */
class Conversation extends Record
{
    /**
     * @param $userId
     * @param $limit
     * @param bool $history
     * @param null $key
     * @return DataProvider
     */
    public static function get($userId, $limit, $history = true, $key = null)
    {
        $query = static::baseQuery($userId);
        if (null !== $key) {
            $query->andHaving([$history ? '<' : '>', 'last_message_id', $key]);
        }
        
        return new DataProvider([
            'query' => $query,
            'key' => 'last_message_id',
        ]);
    }

    /**
     * @param int $userId
     * @return ConversationQuery
     */
    protected static function baseQuery($userId)
    {
        return static::find()
            ->forUser($userId)
            ->addSelect(['user_id' => new Expression(':userId')])
            ->with('lastMessage')
            ->orderBy(['last_message_id' => SORT_DESC]);
    }

    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(ConversationQuery::className(), [get_called_class()]);
    }

    /**
     * @param $userId
     * @return array|null|ActiveRecord
     */
    public static function recent($userId)
    {
        return static::baseQuery($userId)->one();
    }

    /**
     * @param string $userId
     * @param string $contactId
     * @return array the number of rows updated
     */
    public static function remove($userId, $contactId)
    {
        $count = static::updateAll([
            'is_deleted_by_sender' => new Expression('IF([[sender_id]] = :userId, TRUE, is_deleted_by_sender)'),
            'is_deleted_by_receiver' => new Expression('IF([[receiver_id]] = :userId, TRUE, is_deleted_by_receiver)')
        ], ['or',
            ['receiver_id' => new Expression(':userId'), 'sender_id' => $contactId, 'is_deleted_by_receiver' => false],
            ['sender_id' => new Expression(':userId'), 'receiver_id' => $contactId, 'is_deleted_by_sender' => false],
        ], [
            'userId' => $userId
        ]);
        return compact('count');
    }

    /**
     * This method sets the "is_new" field at false of all current conversation messages.
     *
     * @param int $userId ID of the logged user
     * @param int $contactId ID of the contact that we are texting to
     *
     * @return array the number of rows updated
     */
    public static function read($userId, $contactId)
    {
        $count = static::updateAll([
            'is_new' => false
        ], [
            'receiver_id' => $userId,
            'sender_id' => $contactId,
            'is_new' => true
        ]);

        return compact('count');
    }

    /**
     * @param $userId
     * @param $contactId
     * @return array
     */
    public static function unread($userId, $contactId)
    {
        /** @var \open20\amos\chat\models\Message $message */
        $message = \open20\amos\chat\models\Message::find()
            ->where(['sender_id' => $contactId, 'receiver_id' => $userId, 'is_deleted_by_receiver' => false])
            ->orderBy(['id' => SORT_DESC])
            ->limit(1)
            ->one();
        $count = 0;
        if ($message) {
            $message->is_new = 1;
            $count = intval($message->update());
        }
        return compact('count');
    }

    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return \open20\amos\chat\models\Message::tableName();
    }

    /**
     * @inheritdoc
     */
    public static function populateRecord($record, $row)
    {
        foreach (['user_id', 'contact_id'] as $name) {
            if (isset($row[$name])) {
                $row[$name] = intval($row[$name]);
            }
        }
        parent::populateRecord($record, $row);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastMessage()
    {
        return $this->hasOne(\open20\amos\chat\models\Message::className(), ['id' => 'last_message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewMessages()
    {
        return $this->hasMany(\open20\amos\chat\models\Message::className(), ['sender_id' => 'contact_id', 'receiver_id' => 'user_id'])
            ->andOnCondition(['is_new' => true]);
    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function beforeDelete()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function beforeValidate()
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['user_id', 'contact_id', 'last_message_id'];
    }

    public function getLoadUrl()
    {
        return '';
    }

    public function getSendUrl()
    {
        return '';
    }

    public function getDeleteUrl()
    {
        return '';
    }

    public function getReadUrl()
    {
        return '';
    }

    public function getUnreadUrl()
    {
        return '';
    }

    /**
     * Representation of the record in string
     *
     * @return string
     */
    public function __toString()
    {
        return ''. $this->user_id . $this->contact_id . $this->last_message_id;
    }
}
