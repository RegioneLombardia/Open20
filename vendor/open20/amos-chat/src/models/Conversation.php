<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\models;

use open20\amos\chat\AmosChat;
use yii\db\ActiveQuery;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * Class Conversation
 * @package open20\amos\chat\models
 *
 * @property-read User contact
 */
class Conversation extends \open20\amos\chat\models\base\Conversation
{
    /**
     * @inheritDoc
     */
    protected static function baseQuery($userId)
    {
        return parent::baseQuery($userId)->with(['newMessages', 'contact.profile']);
    }

    /**
     * @return ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(\open20\amos\chat\models\User::className(), ['id' => 'contact_id']);
    }

    /**
     * @inheritDoc
     */
    public function fields()
    {
        return [
            'lastMessage' => function ($model) {
                return [
                    'text' => StringHelper::truncate($model['lastMessage']['text'], 120, '...', null, true),
                    'date' => static::formatDate($model['lastMessage']['created_at']),
                    'senderId' => $model['lastMessage']['sender_id']
                ];
            },
            'newMessages' => function ($model) {
                return [
                    'count' => count($model['newMessages'])
                ];
            },
            'contact' => function ($model) {
                return $model['contact'];
            },
            'profile' => function ($model) {
                $profile = null;
                $contact = $model->contact;
                if(!is_null($contact)){
                    $profile = $contact->profile;
                }
                return $profile;
            },
            'name' => function ($model) {
                $name = "";
                $contact = $model->contact;
                if(!is_null($contact)){
                    $name = $contact->name;
                }
                return $name;
            },
            'loadUrl',
            'sendUrl',
            'deleteUrl',
            'readUrl',
            'unreadUrl',
        ];
    }

    public static function formatDate($value)
    {
        $today = date_create()->setTime(0, 0, 0);
        $date = date_create($value)->setTime(0, 0, 0);
        if ($today == $date) {
            $formatted = \Yii::$app->formatter->asTime($value, 'short');
        } elseif ($today->getTimestamp() - $date->getTimestamp() == 24 * 60 * 60) {
            $formatted = AmosChat::tHtml('amoschat', 'Ieri');
        } elseif ($today->format('W') == $date->format('W') && $today->format('Y') == $date->format('Y')) {
            $formatted = \Yii::$app->formatter->asDate($value, 'php:l');
        } elseif ($today->format('Y') == $date->format('Y')) {
            $formatted = \Yii::$app->formatter->asDate($value, 'php:d F');
        } else {
            $formatted = \Yii::$app->formatter->asDate($value, 'medium');
        }

        return $formatted;
    }

    public function getLoadUrl()
    {
        return Url::to(['messages', 'contactId' => $this->contact_id]);
    }

    public function getSendUrl()
    {
        return Url::to(['create-message', 'contactId' => $this->contact_id]);
    }

    public function getDeleteUrl()
    {
        return Url::to(['delete-conversation', 'contactId' => $this->contact_id]);
    }

    public function getReadUrl()
    {
        return Url::to(['mark-conversation-as-read', 'contactId' => $this->contact_id]);
    }

    public function getUnreadUrl()
    {
        return Url::to(['mark-conversation-as-unread', 'contactId' => $this->contact_id]);
    }
}