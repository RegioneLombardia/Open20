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

use open20\amos\chat\AmosChat;
use open20\amos\chat\DataProvider;
use open20\amos\chat\models\search\MessageQuery;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\Email;
use Yii;
use yii\db\Expression;

/**
 * Class Message
 * @package open20\amos\chat\models\base
 *
 * @property string id
 * @property int sender_id
 * @property int receiver_id
 * @property string text
 * @property bool is_new
 * @property bool is_deleted_by_sender
 * @property bool is_deleted_by_receiver
 * @property string created_at
 *
 * @property-read mixed newMessages
 */
class Message extends Record
{
    /**
     */
    public static function tableName()
    {
        return 'amoschat_message';
    }

    /**
     * @param int $userId
     * @param int $contactId
     * @param int $limit
     * @param bool $history
     * @param int $key
     * @return DataProvider
     */
    public static function get($userId, $contactId, $limit, $history = true, $key = null)
    {
        $query = static::baseQuery($userId, $contactId);
        if (null !== $key) {
            $query->andWhere([$history ? '<' : '>', 'id', $key]);
        }

        \open20\amos\chat\models\Conversation::read($userId, $contactId);
        
        return new DataProvider([
            'query' => $query,

        ]);
    }

    /**
     * @param int $userId
     * @param int $contactId
     * @return MessageQuery
     */
    protected static function baseQuery($userId, $contactId)
    {
        return static::find()
            ->between($userId, $contactId)
            ->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return MessageQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return Yii::createObject(MessageQuery::className(), [get_called_class()]);
    }

    /**
     * @param int $userId
     * @param int $contactId
     * @param string $text
     * @return array|bool returns true on success or errors if validation fails
     */
    public static function create($userId, $contactId, $text)
    {
        $instance = new static(['scenario' => 'create']);
        $instance->created_at = new Expression('UTC_TIMESTAMP()');
        $instance->sender_id = $userId;
        $instance->receiver_id = $contactId;
        $instance->text = $text;
        $instance->is_deleted_by_sender = 0;
        $instance->is_deleted_by_receiver =  0;
        $instance->is_new = 1;
        if($instance->save()){
            Message::automaticMessage($contactId);
        }


        $result = '';
        if(count($instance->errors)){
            $result = json_encode($instance->errors);
        }else{
            $result = json_encode('');
        }
        return $result;
    }

    /**
     * @param $contactId
     */
    public static function automaticMessage($contactId){
       $module =  \Yii::$app->getModule('chat');
       if($module){
           $automaticMessages = $module->automaticMessage;
           foreach ((array)$automaticMessages as $message){
               $userId = $message['id'];
               $text = $message['message'];
               if($contactId == $userId) {
                   $message = new Message();
                   $message->text = $text;
                   $message->sender_id = $contactId;
                   $message->receiver_id = \Yii::$app->user->id;
                   $message->is_new = 1;
                   $message->save();
               }
           }
       }
    }
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'text', 'created_at'], 'required', 'on' => 'create']
        ];
    }
}
