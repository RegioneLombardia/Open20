<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\behaviors
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\behaviors;

use open20\amos\core\models\ModelsClassname;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\models\NotificationChannels;
use \open20\amos\core\controllers\CrudController;
use open20\amos\notificationmanager\models\NotificationSendEmail;
use open20\amos\notificationmanager\models\NotificationsRead;
use open20\amos\notificationmanager\models\NotificationContentLanguage;
use ReflectionClass;
use Yii;
use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\web\Application;

/**
 * Class NotifyBehavior
 * @package open20\amos\core\behaviors
 */
class NotifyBehavior extends Behavior
{
    public static $EVENT_METHOD_EVALUATE      = 'evaluatenotify';
    public static $EVENT_METHOD_READED        = 'notifyreaded';
    public static $EVENT_METHOD_READED_DETAIL = 'notifyreadeddetail';
    private $events                           = [];
    private $channels                         = [];
    private $conditions                       = [];
    public $saveNotificationSendEmail;
    public $modelOldAttributes;

    /**
     * @var array $mailStatuses
     */
    private $mailStatuses = [];

    /**
     * @var AmosNotify $notifyModule
     */
    public $notifyModule = null;

    /**
     * 
     */
    public function init()
    {
        $this->notificationInit();
        parent::init();
        $this->notifyModule = AmosNotify::instance();
    }

    /**
     *
     */
    private function notificationInit()
    {
        $this->initNotifyType();
        $this->initNotifyEvents();
    }

    /**
     *
     */
    private function initNotifyType()
    {
        $channels = $this->getChannels();
        if (empty($channels)) {
            $this->setChannels([NotificationChannels::CHANNEL_ALL]);
        }
    }

    /**
     *
     */
    private function initNotifyEvents()
    {
        $events = $this->getEvents();
        if (empty($events)) {
            $this->setEvents([
                ActiveRecord::EVENT_AFTER_INSERT,
                ActiveRecord::EVENT_AFTER_UPDATE,
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        $eventsList = [/* ActiveRecord::EVENT_AFTER_FIND => self::$EVENT_METHOD_READED, */
            CrudController::AFTER_FINDMODEL_EVENT => self::$EVENT_METHOD_READED_DETAIL,
            ActiveRecord::EVENT_AFTER_FIND => 'saveOldAttributes'
        ];
        foreach ($this->events as $event) {
            $eventsList[$event] = self::$EVENT_METHOD_EVALUATE;
        }
        return $eventsList;
    }

    /**
     *
     * @param array $events
     */
    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    /**
     *
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     *
     * @param array $array
     */
    public function setChannels($array)
    {
        $this->channels = $array;
    }

    /**
     *
     * @return array
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     *
     * @param array $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return array
     */
    public function getMailStatuses()
    {
        return $this->mailStatuses;
    }

    /**
     * @param array $mailStatuses
     */
    public function setMailStatuses($mailStatuses)
    {
        $this->mailStatuses = $mailStatuses;
    }

    /**
     *
     * @param array $event
     */
    public function evaluatenotify($event)
    {
        try {
            $model = $event->sender;
            if ($this->isNotify($model) === false) {
                return;
            }
            $this->notify($event);
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @param array $event
     */
    private function notify($event)
    {
        try {
            $model = $event->sender;
            $this->persistNotify($model);
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @param array $event
     */
    public function notifyreaded($event)
    {
        try {
            $model = $event->sender;
            $this->persistNotifyReaded($model);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @param array $event
     */
    public function notifyreadeddetail($event)
    {
        try {
            $model = $event->sender;
            $this->persistNotifyReaded($model, NotificationChannels::CHANNEL_READ_DETAIL);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @param array $event
     */
    public function saveOldAttributes($event)
    {
        try {
            $model                    = $event->sender;
            $this->modelOldAttributes = $model->attributes;
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * Method to persist Notification
     *
     * @param array $model
     */
    private function persistNotify($model)
    {
        //it's called for every update
        try {
            $validatori = false;
            if (property_exists($model, 'validatori')) {
                $validatori = $model->validatori;
            }

            if ($this->notifyModule != null) {
                foreach ($this->evaluateChannels() as $channel) {
                    if ($this->notifyModule->confirmEmailNotification) {
                        //used for the modal to choose if you want to send the email notification
                        $this->saveNotificationSendEmail(get_class($model), $channel,
                            $model->getAttributes()[$model->primaryKey()[0]]);
                    }

//                    $notify = $this->notifyModule->createModel('Notification')->findOne(['content_id' => $model->getAttributes()[$model->primaryKey()[0]],
//                    'channels' => $channel, 'class_name' => get_class($model) ]);
                    $notify = $this->notifyModule->createModel('Notification')->find()
                            ->leftJoin('notificationread', 'notificationread.notification_id = notification.id')
                            ->andWhere([
                                'content_id' => $model->getAttributes()[$model->primaryKey()[0]],
                                'channels' => $channel,
                                'class_name' => get_class($model)
                            ])
                            ->andWhere(['notificationread.notification_id' => null])->one();


                    if (empty($notify)) {
                        if ($this->isModelChanged($model)) {
                            $notify = $this->notifyModule->createModel('Notification');
                            if (\Yii::$app instanceof Application) {
                                $notify->user_id = Yii::$app->user->id;
                            } else {
                                $notify->user_id = 0;
                            }

                            //create notification for a network
                            $notify->content_id = $model->getAttributes()[$model->primaryKey()[0]];
                            $notify->channels   = $channel;
                            $notify->class_name = get_class($model);

                            if ($validatori) {
                                if (is_array($validatori)) {
                                    $validatori = reset($validatori);
                                }
                                if (strpos($validatori, 'user') === false) {
                                    $exploded = explode('-', $validatori);
                                    if (count($exploded) == 2) {
                                        $modelsClassname = ModelsClassname::find()->andWhere(['module' => $exploded[0]])->one();
                                        if ($modelsClassname) {
                                            $notify->models_classname_id = $modelsClassname->id;
                                            $notify->record_id           = $exploded[1];
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        $notify->updated_at = null;
                    }

                    if (!empty($notify)) {
                        if ($notify->save(false)) {
                            $this->saveNotificationContentLanguage($notify);
                        }
                    }
                }
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param $model
     * @return bool
     */
    private function isModelChanged($model)
    {
        $modelIsChanged = false;

        if (empty($this->modelOldAttributes)) {
            return true;
        }

        foreach ((Array) $this->modelOldAttributes as $oldAttribute => $value) {
            if (!in_array($oldAttribute,
                    ['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'])) {
                if ($model->attributes[$oldAttribute] != $value) {
                    $modelIsChanged = true;
                }
            }
        }

        return $modelIsChanged;
    }

    /**
     * Method to persist NotificationsRead.
     *
     * @param array $model
     * @param integer $channel
     */
    private function persistNotifyReaded($model, $channel = NotificationChannels::CHANNEL_READ)
    {

        try {
            if ($this->notifyModule != null) {
                $notify = $this->notifyModule->createModel('Notification')->findOne(['content_id' => $model->getAttributes()[$model->primaryKey()[0]],
                    'channels' => $channel, 'class_name' => get_class($model)]);
                if ($notify) {
                    /** @var NotificationsRead $notify_read */
                    $notify_read = $this->notifyModule->createModel('NotificationsRead');
                    $notify_load = $notify_read->findOne(['user_id' => Yii::$app->user->id, 'notification_id' => $notify->id]);
                    if ($notify_load) {
                        $notify_read = $notify_load;
                    }
                    $notify_read->user_id         = Yii::$app->user->id;
                    $notify_read->notification_id = $notify->id;
                    $notify_read->updated_at      = null;
                    $notify_read->save();
                }
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @return array
     */
    private function evaluateChannels()
    {

        if (in_array(NotificationChannels::CHANNEL_ALL, $this->channels)) {
            return [
                NotificationChannels::CHANNEL_MAIL,
                NotificationChannels::CHANNEL_IMMEDIATE_MAIL, // NOT USED
                NotificationChannels::CHANNEL_UI,
                NotificationChannels::CHANNEL_SMS,
                NotificationChannels::CHANNEL_READ,
                NotificationChannels::CHANNEL_READ_DETAIL,
                NotificationChannels::CHANNEL_FAVOURITES,
                NotificationChannels::CHANNEL_ALL];
        }
        return $this->channels;
    }

    /**
     *
     * @return boolean
     */
    private function isNotify($model)
    {
        $isnotify = true;

        if (!empty($this->conditions)) {
            foreach ($this->conditions as $key => $value) {
                $isnotify = $isnotify && ($model->$key == $value);
            }
        }
        return $isnotify;
    }

    /**
     * @param $modelClassName
     * @param $channel
     * @param $modelData
     * @return bool
     */
    public function saveNotificationSendEmail($modelClassName, $channel, $modelId, $bypassCheckPostParam = false)
    {
        $isSetPost = false;
        if (\Yii::$app instanceof Yii\console\Application) {
            $isPostCorrect = true;
        } else {
            $post          = \Yii::$app->request->post('saveNotificationSendEmail');
            $isPostCorrect = ($post && $post == 1);
            $isSetPost     = isset($post);
        }

        // On configuration we define models can notified only by post parameter.
        // For this class, if isPostCorrect is false, exit... do nothing
        $notificationOnlyOnPostParameter = in_array($modelClassName,$this->notifyModule->disableDefaultBehaviorClasses);
        if ($notificationOnlyOnPostParameter && !$isPostCorrect) {
            return true;
        }

        // if you validate the content from outside the update pge of the content, the modal is not shown and che record of notification-send.email is always created
        if ($bypassCheckPostParam || $isPostCorrect || !$isSetPost) {
            if ($channel == NotificationChannels::CHANNEL_MAIL) {
                /** @var NotificationSendEmail $notificationSendEmailModel */
                $notificationSendEmailModel = $this->notifyModule->createModel('NotificationSendEmail');
                $notificationSendEmail      = $notificationSendEmailModel::find()->andWhere([
                        'content_id' => $modelId,
                        'classname' => $modelClassName
                    ])->one();
                if (is_null($notificationSendEmail)) {
                    /** @var NotificationSendEmail $notificationSendEmail */
                    $notificationSendEmail             = $this->notifyModule->createModel('NotificationSendEmail');
                    $notificationSendEmail->content_id = $modelId;
                    $notificationSendEmail->classname  = $modelClassName;
                }

                $ok = $notificationSendEmail->save(false);
                return $ok;
            }
        }
        return true;
    }

    /**
     * @param $notify
     * @throws \yii\base\InvalidConfigException
     */
    public function saveNotificationContentLanguage($notify)
    {
        try {
            $notify_content_language = null;
            if ( !(\Yii::$app instanceof Yii\console\Application) ) {
                $notify_content_language = \Yii::$app->request->post('notify_content_language');
            }
            $modelsclassname         = ModelsClassname::find()
                    ->andWhere(['classname' => $notify->class_name])->one();
            if ($modelsclassname) {
                if (!empty($notify_content_language)) {
                    $notificationContentLanguage = NotificationContentLanguage::find()
                            ->andWhere(['models_classname_id' => $modelsclassname->id])
                            ->andWhere(['record_id' => $notify->content_id])->one();

                    if (empty($notificationContentLanguage)) {
                        $notificationContentLanguage                      = new NotificationContentLanguage();
                        $notificationContentLanguage->models_classname_id = $modelsclassname->id;
                        $notificationContentLanguage->record_id           = $notify->content_id;
                    }
                    $notificationContentLanguage->language = $notify_content_language;
                    $notificationContentLanguage->save(false);
                }
            }
        } catch (Exception $bex) {
            Yii::getLogger()->log($bex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }
    
}