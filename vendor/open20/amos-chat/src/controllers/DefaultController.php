<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat\controllers;

use open20\amos\chat\AmosChat;
use open20\amos\chat\assets\AmosChatAsset;
use open20\amos\chat\models\Conversation;
use open20\amos\chat\models\Message;
use open20\amos\chat\models\User;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use pendalf89\filemanager\models\Mediafile;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DefaultController
 * @package open20\amos\chat\controllers
 *
 * @property-read User user
 */
class DefaultController extends CrudController
{


    use ControllerTrait {
        behaviors as behaviorsTrait;
    }

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @var AmosChat
     */
    public $module;

    /**
     * @var User
     */
    private $_user;

    /**
     * Init all view types
     *
     */
    public function init()
    {
        $this->setModelObj(new Conversation());
        $this->setModelSearch(new \open20\amos\chat\models\base\Conversation());

        AmosChatAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosChat::t('amoschat', '{iconaLista}' . Html::tag('p', AmosChat::t('amoschat', 'Lista')), [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),
                'url' => '?currentView=list'
            ],
        ]);

        parent::init();
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge($this->behaviorsTrait(), [
            [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'index' => ['get', 'post'],
                    'assistance' => ['get', 'post'],
                    'login-as' => ['post'],
                    'messages' => ['get', 'post'],
                    'send-message' => ['get', 'post'],
                    'conversations' => ['get', 'post'],
                    'contacts' => ['get'],
                    'create-message' => ['post'],
                    'forward-message' => ['post', 'get'],
                    'delete-conversation' => ['delete'],
                    'mark-conversation-as-read' => ['patch', 'put'],
                    'mark-conversation-as-unread' => ['patch', 'put'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'chat-with-assistance',
                            'send-message-ajax'
                        ],
                        'roles' => ['BASIC_USER','ADMIN']
                    ],
                    ]
                ]
        ]);
    }
    
    
    /**
     * disabled csrf token for send-message
     */
    
    public function beforeAction($action) {
      if ($action->id == 'send-message') {
        $this->enableCsrfValidation = false;
     // Yii::$app->controller->enableCsrfValidation = FALSE;
      }
      return parent::beforeAction($action);
    }

    /**
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @param null $contactId
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($contactId = null)
    {
        // Logged user
        $user = $this->user;
        $userId = $user->id;

        /** @var $userContactClass UserContactsQuery */
        $userContactClass = $this->userContactClass;
        $userContactDataProvider = $userContactClass::getUserContacts($userId);
        $userContactDataProvider->setPagination(false);

        //gets the community users (if the scope is a community): if a user is not
        //in this list, it will be disabled. Otherwise this var is false
        $idsCommunity = $userContactClass::getCommunityUsers($userId);

        if ($contactId == $userId) {
            throw new ForbiddenHttpException(AmosChat::t('amoschat', 'Impossibile aprire questa conversazione'));
        }

        if (isset($contactId)) {
            $current = new Conversation(['user_id' => $userId, 'contact_id' => $contactId]);
        }

        /** @var $conversationClass Conversation */
        $conversationClass = $this->conversationClass;

        $conversationDataProvider = $conversationClass::get($userId, 8);
        $conversationDataProvider->setPagination(false);

        if (!isset($current)) {

            if (0 == $conversationDataProvider->getTotalCount()) {

                /** @var $messageClass Message */
                $messageClass = $this->messageClass;
                $messageDataProvider = $messageClass::get($userId, 0, 10);
                $users = $this->getUsers([$userId, 0]);
                $current = null;
                $contact = null;
                $asset = $this->assetRegistration();

                return $this->render(
                    'index',
                    compact('conversationDataProvider', 'messageDataProvider', 'userContactDataProvider', 'users', 'user', 'contact', 'current', 'asset', 'idsCommunity')
                );
            } else {
                $current = current($conversationDataProvider->getModels());

            }
        }

        $contact = $current['contact'];

        if (is_array($contact)) {
            $contactId = $contact['id'];

            $contact = User::findOne($contactId);


            $current['contact'] = $contact;


        }

        if (empty($contact)) {
            throw new NotFoundHttpException(AmosChat::t('amoschat', 'Contatto non trovato'));
        }


        /** @var $messageClass Message */
        $messageClass = $this->messageClass;
        $messageDataProvider = $messageClass::get($userId, $contact->id, 10);
        //$users = $this->getUsers([$userId, $contact->id]);

        // This set all "is_new" field of current conversation messages to false, that mean the conversation is read.
        $conversationClass::read($userId, $contact->id);

        $asset = $this->assetRegistration();
        return $this->render(
            'index',
            compact('conversationDataProvider', 'messageDataProvider', 'userContactDataProvider', 'users', 'user', 'contact', 'current', 'asset', 'idsCommunity')
        );
    }

    /**
     * @param $user_id
     * @param $url
     */
    public function actionChatWithAssistance($user_id, $url, $idchatAssistance){
        $chat = \Yii::$app->getModule(AmosChat::getModuleName());
        $defaultMessage = AmosChat::t('amoschat', 'Ciao, se hai dubbi scrivici utilizzando quest chat');
        $text = $defaultMessage;

        if(!empty($chat)){
            $text = !empty($chat->assistenzaChatCommunity[$idchatAssistance]['welcome_message'])
                ? $chat->assistenzaChatCommunity[$idchatAssistance]['welcome_message']
                : $defaultMessage;
        }

        $today = new \DateTime();
        $countTodayMessages = Message::find()->andWhere(['OR',
            ['AND', ['receiver_id' => \Yii::$app->user->id],['sender_id' => $user_id]],
            ['AND', ['receiver_id' => $user_id],['sender_id' => \Yii::$app->user->id]]
        ])
        ->andWhere([new Expression('DATE_FORMAT(created_at,"%Y-%m-%d")') => $today])->count();
        if($countTodayMessages == 0) {
            $message = new Message();
            $message->text = $text;
            $message->sender_id = $user_id;
            $message->receiver_id = \Yii::$app->user->id;
            $message->is_new = 1;
            $message->save();
        }

        return $this->redirect($url);
    }

    /**
     * @param array $except
     * @return array
     */
    public function getUsers(array $except = [])
    {
        $users = [];
        foreach (User::getAll(true) as $userItem) {
            $users[] = [
                'label' => $userItem['userProfile']['nome'] . " " . $userItem['userProfile']['cognome'],
                'url' => Url::to(['login-as', 'userId' => $userItem['id']]),
                'options' => ['class' => in_array($userItem['id'], $except) ? 'disabled' : ''],
                'linkOptions' => ['data-method' => 'post'],
            ];
        }
        return $users;
    }

    private function assetRegistration()
    {
        if (!Yii::$app->getRequest()->isPjax) {
            $view = $this->getView();
            return AmosChatAsset::register($view);
        } else {
            return new AmosChatAsset();
        }
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

    /**
     * @return string
     */
    public function getUserContactClass()
    {
        $module = \Yii::$app->getModule('chat');
        if($module){
            return $module->model('UserContactsQuery');
        }
        return null;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        if (null === $this->_user) {
            $this->_user = User::findIdentity(Yii::$app->getUser()->getId());
        }
        return $this->_user;
    }

    /**
     * @deprecated
     * @param int $modelId Model ID.
     * @param string $modelType Type of the model. It can be USER or AVATAR.
     * @return string
     */
    public function getUserAvatar($modelId, $modelType)
    {
        $cleanModelType = strtoupper(trim($modelType));
        if (!is_null($modelId) && is_numeric($modelId)) {
            if (strcmp($cleanModelType, 'USER') == 0) {
                $user = User::findOne($modelId);
                return $user->getAvatar();
            } elseif (strcmp($cleanModelType, 'AVATAR') == 0) {
                $mediafile = Mediafile::findOne($modelId);
                if ($mediafile) {
                    return $mediafile->getThumbImage('small', ['class' => 'media-object avatar']);
                }
            }
        }

        return Html::img("/img/defaultProfilo.png", ['width' => '50', 'class' => 'media-object avatar']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Conversation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conversation::findOne($id)) !== null) {
            $this->model = $model;
            return $model;
        } else {
            throw new NotFoundHttpException(AmosChat::t('amoschat', 'La pagina richiesta non è disponibile'));
        }
    }

    public function actionForwardMessage($idMessage, $idUserToForward)
    {
//        $idMessage = Yii::$app->request->get('idMessage');
//        $idUserToForward = Yii::$app->request->get('idUserToForward');
//        var_dump(Yii::$app->request->get());
//        $idMessage = 2;
//        $idUserToForward = 372;
        $message = Message::findOne(['id' => $idMessage]);
        $message->receiver_id = $idUserToForward;
        $message->is_new = true;
        if ($message->save(false)) {
            return \yii\helpers\Json::encode('true');
        }
    }

    /**
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionAssistance()
    {
        $moduleChat = AmosChat::getInstance();
        return $this->redirect(['index', 'contactId' => $moduleChat->assistanceUserId ]);
    }

    /**
     * @param null $layout
     * @return bool
     */

    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');

        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }

    /**
     * @return array
     */
    public function actionSendMessageAjax(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $sender_id = \Yii::$app->request->post('sender_id');
        $receiver_id = \Yii::$app->request->post('receiver_id');
        $text = \Yii::$app->request->post('text');

        $message = new Message();
        $message->sender_id = $sender_id;
        $message->receiver_id = $receiver_id;
        $message->text = $text;
        $message->is_new = 1;
        $ok = $message->save(false);
        return ['success' => $ok];
    }
}
