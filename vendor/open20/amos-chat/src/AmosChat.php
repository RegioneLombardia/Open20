<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

namespace open20\amos\chat;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\console\Application as Console;
use yii\db\Connection;
use yii\web\Application as Web;

/**
 * Class AmosChat
 * @package open20\amos\chat RUdy
 */
class AmosChat extends AmosModule implements BootstrapInterface, ModuleInterface
{
    /**
     * @var string $CONFIG_FOLDER Module config folder
     */
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @var string $name Module name
     */
    public $name = 'Messaggi Privati';

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';

    /**
     * @var string $controllerNamespace Default controller namespace
     */
    public $controllerNamespace = 'open20\amos\chat\controllers';

    /**
     * @var string $controllerConsoleNamespace Console namespace
     */
    public $controllerConsoleNamespace = 'open20\amos\chat\console';

    /**
     * @var array $formRedactorButtons List of all Redactor visible buttons in message form
     */
    public $formRedactorButtons = [
        'image',
//        'file'
    ];

    /**
     * @var boolean $immediateNotificationForce force notification sending to the message receiver
     */
    public $immediateNotificationForce = true;
    
    /**     
     * @var string Default e-mail sender if the server allow only sender with the same domain
     */
    public $defaultEmailSender;

    /**
     * @var string $subjectOfimmediateNotification Default subject for emails of "immediate notification force". If in the module settings override the value, you detach translation system
     */
    private $_subjectOfimmediateNotification = 'default';

    /**
     * @var boolean $enableForwardMessage used to enable the forwarding of messages. It's required to insert an array of user_id in the variable $userIdForwardMessage
     */
    public $enableForwardMessage = false;
    /**
     * @var (array) integer $userIdForwardMessage  It's required to enable the function of forwarding message, it contain the list of user to which forward the messages
     */
    public $userIdForwardMessage = [];

    /**
     * @var bool
     */
    public $onlyNetworkUsers = true;

    /**
     * @var string
     */
    public $emailMessageContentAllowedTag = 'p,div';

    /**
     * @var bool
     */
    public $enableVideoconference = false;

    /**
     * @var int $assistanceUserId - User Id of the assistance user to open a conversation on click on WidgetIconChatAssistance
     */
    public $assistanceUserId = 1;

    /**
     * Advanced configuration for assistance graphics widget in community
     */
    public $assistenzaChatCommunity = [];

    /**
     * @var array 
     */
    public $automaticMessage = [];

    /**
     * Initializes amos messaggi privati module.
     * This method will initialize the [[db]] property to make sure it refers to a valid DB connection.
     * @throws InvalidConfigException if [[db]] is invalid.
     */
    public function init()
    {
        parent::init();
        $this->db = Yii::$app->db;

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
        // initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php'));
    }

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return "chat";
    }

    public function getSubjectOfimmediateNotification() {
        if ($this->_subjectOfimmediateNotification == 'default') {
            $this->_subjectOfimmediateNotification = self::t('amoschat', 'New message from chat');
        }

        return $this->_subjectOfimmediateNotification;
    }

    public function setSubjectOfimmediateNotification($s) {
        $this->_subjectOfimmediateNotification = $s;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof Web) {
            $app->getUrlManager()->addRules([
                'messages/<contactId:\d+>' => $this->id . '/default/index',
                'messages' => $this->id . '/default/index',
                'login-as/<userId:\d+>' => $this->id . '/default/login-as',
                'chat/get/messages/<contactId:\d+>' => $this->id . '/default/messages',
                'chat/get/conversations' => $this->id . '/default/conversations',
                'chat/get/userContacts' => $this->id . '/default/userContacts',
                'forward-message' => $this->id .'/default/forward-message',
                'chat/delete/message/<id:\d+>' => $this->id . '/default/delete-message',
                'chat/delete/conversation/<contactId:\d+>' => $this->id . '/default/delete-conversation',
                'chat/post/message/<contactId:\d+>' => $this->id . '/default/create-message',
                'chat/unread/conversation/<contactId:\d+>' => $this->id . '/default/mark-conversation-as-unread',
                'chat/read/conversation/<contactId:\d+>' => $this->id . '/default/mark-conversation-as-read',
            ], false);
        } elseif ($app instanceof Console) {
            $this->controllerNamespace = $this->controllerConsoleNamespace;
            $app->controllerMap[$this->id] = [
                'class' => $this->controllerNamespace . '\ChatController',
                'module' => $this,
            ];
        }
    }

    /**
     *
     */
    public function getWidgetIcons()
    {

    }

    /**
     *
     */
    public function getWidgetGraphics()
    {

    }

    /**
     *
     * @return array
     */
    protected function getDefaultModels()
    {
        return [
            'Conversation' => __NAMESPACE__ . '\\' . 'models\Conversation',
            'Message' => __NAMESPACE__ . '\\' . 'models\Message',
            'User' => __NAMESPACE__ . '\\' . 'models\User',
            'ConversationQuery' => __NAMESPACE__ . '\\' . 'models\ConversationQuery',
            'MessageQuery' => __NAMESPACE__ . '\\' . 'models\MessageQuery',
            'UserContactsQuery' => __NAMESPACE__ . '\\' . 'models\search\UserContactsQuery',
        ];
    }
}
