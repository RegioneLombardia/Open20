<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\record
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\record;

use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\NotifyWidget;
use open20\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\di\Container;
use yii\helpers\ArrayHelper;

/**
 * Class NotifyRecord
 * @package open20\amos\notificationmanager\record
 */
class NotifyRecord extends \open20\amos\core\record\Record implements NotifyRecordInterface
{
    /**
     * @var string $isNewsFiledName
     */
    protected static $isNewsFiledName = 'created_at';

    /**
     * @var array $mailStatuses - map workflow transitions for which an email must be sent with email configurations
     * [ end status =>  ChangeStatusEmail ]
     * for standard email when reaching toValidate and validated statuses, leave the array empty
     */
    public $mailStatuses = [];

    /**
     * @var string $destinatari_notifiche Destinatari notifiche
     */
    public $destinatari_notifiche;
    public $modelClassName;
    public $modelFormName;

    /**
     * @var Container $container - used in search for notification process
     */
    private $container;

    /**
     * @var bool $isSearch - if it is content model search class
     */
    public $isSearch = false;

    public function __construct(array $config = [])
    {
        if ($this->isSearch) {
            $this->container      = new Container();
            $this->container->set('notify', Yii::$app->getModule('notify'));
            $reflectionClass      = new \ReflectionClass($this->className());
            $this->modelClassName = $reflectionClass->getParentClass()->name;
            $parent               = $reflectionClass->getParentClass()->newInstance();
            $this->modelFormName  = $parent->formName();
        } else {
            $this->modelClassName = $this->className();
            $this->modelFormName  = $this->formName();
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
                [['destinatari_notifiche'], 'safe']
        ]);
    }

    /**
     * @return bool
     */
    public function isNews()
    {
        $bool   = false;
        $module = \Yii::$app->getModule('notify');
        if ($module && !\Yii::$app->user->isGuest) {
            $profile = \Yii::$app->getUser()->getIdentity()->getProfile();
            if ($this->__get(self::$isNewsFiledName) > $profile->ultimo_logout && !$module->modelIsRead($this,
                    \Yii::$app->getUser()->id)) {
                $bool = true;
            }
        }
        return $bool;
    }

    /**
     * @return array
     */
    public function createOrderClause()
    {
        if (\Yii::$app->user->isGuest) {
            return [
                'attributes' => [
                    $this->orderAttribute,
                    'id'
                ],
                'defaultOrder' => [
                    $this->orderAttribute => (int) $this->orderType,
                    'id' => SORT_DESC
                ]
            ];
        } else {
            return [
                'attributes' => [
                    $this->orderAttribute,
                    'isNew' => [
                        'asc' => new Expression($this->tableName().".".self::$isNewsFiledName." > '".\Yii::$app->getUser()->getIdentity()->getProfile()->ultimo_logout."'"),
                        'desc' => new Expression($this->tableName().".".self::$isNewsFiledName." > '".\Yii::$app->getUser()->getIdentity()->getProfile()->ultimo_logout."' DESC"),
                        'default' => SORT_DESC,
                    ],
                    'id'
                ],
                'defaultOrder' => [
                    'isNew' => SORT_DESC,
                    $this->orderAttribute => (int) $this->orderType,
                    'id' => SORT_DESC
                ]
            ];
        }
    }

    /**
     * @return mixed|null
     */
    public function getNotifiedUserId()
    {
        $user_id = null;
        try {
            if ($this->hasAttribute('created_by')) {
                $user_id = $this->created_by;
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        return $user_id;
    }

    /**
     * @return bool
     */
    public function sendNotification()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function sendCommunication()
    {
        return true;
    }

    /**
     * @param $notifier
     */
    public function setNotifier(NotifyWidget $notifier)
    {
        $this->container->set('notify', $notifier);
    }

    /**
     * @return $this
     */
    public function getNotifier()
    {
        try {
            return $this->container->get('notify');
        } catch (\Exception $e) {
            return null;
        } catch (\Error $e) {
            return null;
        }
    }

    /**
     * Switch off notifications
     *
     * @param ActiveQuery $query
     */
    protected function switchOffNotifications($query)
    {
        /** @var  $notify AmosNotify */
        $notify = $this->getNotifier();
        if ($notify) {
            $notify->notificationOff(
                Yii::$app->getUser()->id, $this->modelClassName, $query, NotificationChannels::CHANNEL_READ
            );
        }
    }
}