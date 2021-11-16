<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\controllers;

use open20\amos\chat\AmosChat;
use open20\amos\chat\DataProvider;
use open20\amos\chat\models\Conversation;
use open20\amos\chat\models\Message;
use Yii;
use yii\base\NotSupportedException;
use yii\filters\AccessRule;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;


/**
 * Class ControllerTrait
 * @package open20\amos\chat\controllers
 *
 * @property-read IdentityInterface user
 * @property-read string messageClass
 * @property-read string conversationClass
 * @property-read string contactClass
 */
trait ControllerTrait
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'messages',
                            'create-message',
                            'send-message',
                            'conversations',
                            'delete-conversation',
                            'mark-conversation-as-read',
                            'mark-conversation-as-unread',
                            'forward-message',
                            'userContacts',
                            'delete-message',
                            'login-as'
                        ],
                        'roles' => ['AMMINISTRATORE_CHAT']
                    ],
                ],
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        if ($result instanceof DataProvider) {
            return $result->toArray();
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function actionUserContacts()
    {
        /** @var $userContactClass UserContactsQuery */
        $userContactClass = $this->userContactClass;
        return $userContactClass::findUserContacts($this->user->getId());
    }

    /**
     * @return DataProvider
     */
    public function actionConversations()
    {
        $userId = $this->user->getId();
        $request = Yii::$app->request;
        $limit = $request->get('limit', $request->post('limit'));
        $key = $request->get('key', $request->post('key'));
        $history = strcmp('new', $request->get('type', $request->post('type')));
        /** @var $conversationClass Conversation */
        $conversationClass = $this->conversationClass;
        return $conversationClass::get($userId, $limit, $history, $key);
    }

    /**
     * @param $contactId
     * @return DataProvider
     */
    public function actionMessages($contactId)
    {
        $userId = $this->user->getId();
        $request = Yii::$app->request;
        $limit = $request->get('limit', $request->post('limit'));
        $key = $request->get('key', $request->post('key'));
        $history = strcmp('new', $request->get('type', $request->post('type')));
        /** @var $messageClass Message */
        $messageClass = $this->messageClass;
        return $messageClass::get($userId, $contactId, $limit, $history, $key);
    }

    /**
     * @param $contactId
     * @return array|bool
     * @throws ForbiddenHttpException
     */
    public function actionCreateMessage($contactId)
    {
        $userId = $this->user->getId();
        if ($userId == $contactId) {
            throw new ForbiddenHttpException(AmosChat::t('amoschat', 'Non puoi inviare un messaggio in questa conversazione'));
        }
        $text = Yii::$app->request->post('text');
        /** @var $messageClass Message */
        $messageClass = $this->messageClass;


        return $messageClass::create($userId, $contactId, $text);

    }

    /**
     * @param $contactId
     * @return array|bool
     * @throws ForbiddenHttpException
     */
    public function actionSendMessage($contactId)
    {
        $userId = $this->user->getId();
        if ($userId == $contactId) {
            throw new ForbiddenHttpException(AmosChat::t('amoschat', 'Non puoi inviare un messaggio in questa conversazione'));
        }
        $text = Yii::$app->request->post('text');
        /** @var $messageClass Message */
        $messageClass = $this->messageClass;
        $messageClass::create($userId, $contactId, $text);
        if(\Yii::$app->request->isAjax){
            return json_encode(['success' => 1, 'url'=> Url::to(['/messages'])]);
        }
        return $this->redirect(['/messages']);
    }

    /**
     * @param $id
     * @throws NotSupportedException
     */
    public function actionDeleteMessage($id)
    {
        throw new NotSupportedException(get_class($this) . " " . AmosChat::t('amoschat', 'non supporta') . " actionDeleteMessage($id).");
    }

    /**
     * @param $contactId
     * @return array
     */
    public function actionDeleteConversation($contactId)
    {
        $userId = $this->user->getId();
        /** @var $conversationClass Conversation */
        $conversationClass = $this->conversationClass;
        return json_encode($conversationClass::remove($userId, $contactId));
    }

    /**
     * @param $contactId
     * @return array
     */
    public function actionMarkConversationAsRead($contactId)
    {
        $userId = $this->user->getId();
        /** @var $conversationClass Conversation */
        $conversationClass = $this->conversationClass;
        return $conversationClass::read($userId, $contactId);
    }

    /**
     * @param $contactId
     * @return array
     */
    public function actionMarkConversationAsUnread($contactId)
    {
        $userId = $this->user->getId();
        /** @var $conversationClass Conversation */
        $conversationClass = $this->conversationClass;
        return $conversationClass::unread($userId, $contactId);
    }

    /**
     * @return IdentityInterface
     */
    public function getUser()
    {

        return Yii::$app->user->identity;
    }

    /**
     * @return string
     */
    public function getMessageClass()
    {
        return Message::className();
    }

    /**
     * @return string
     */
    public function getConversationClass()
    {
        return Conversation::className();
    }


}
