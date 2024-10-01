<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\commands
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\commands;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\base\ConfigurationManager;
use open20\amos\admin\models\UserProfile;
use open20\amos\comments\models\Comment;
use open20\amos\community\models\Community;
use open20\amos\core\interfaces\NotificationPersonalizedQueryInterface;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\builder\AMailBuilder;
use open20\amos\notificationmanager\base\builder\ContentMailBuilder;
use open20\amos\notificationmanager\base\builder\CachedContentMailBuilder;
use open20\amos\notificationmanager\base\builder\NewsletterBuilder;
use open20\amos\notificationmanager\base\BuilderFactory;
use open20\amos\notificationmanager\exceptions\NewsletterException;
use open20\amos\notificationmanager\models\base\NotificationSendEmail;
use open20\amos\notificationmanager\models\Newsletter;
use open20\amos\notificationmanager\models\NewsletterContents;
use open20\amos\notificationmanager\models\NewsletterContentsConf;
use open20\amos\notificationmanager\models\Notification;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\models\NotificationConfContent;
use open20\amos\notificationmanager\models\NotificationContentLanguage;
use open20\amos\notificationmanager\models\NotificationLanguagePreferences;
use open20\amos\notificationmanager\models\NotificationSchedule;
use open20\amos\notificationmanager\models\NotificationScheduleContent;
use open20\amos\notificationmanager\models\NotificationsConfOpt;
use open20\amos\notificationmanager\models\NotificationsRead;
use open20\amos\notificationmanager\utility\NotifyUtility;
use Exception;
use lajax\translatemanager\models\Language;
use Yii;
use yii\console\Controller;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Console;
use yii\helpers\VarDumper;
use yii\log\Logger;

/**
 * Class NotifierController
 * @package open20\amos\notificationmanager\commands
 */
class NotifierController extends Controller
{
    public $weekMails = false;
    public $dayMails = false;
    public $monthMails = false;
    public $immediateMails = false;
    public $disableSegmentation = false;

    const TYPE_OF_SECTION_NORMAL = 'normal';
    const TYPE_OF_SECTION_NETWORK = 'network';
    const TYPE_OF_SECTION_COMMENTS = 'comments';
    const TYPE_OF_SECTION_ALL = 'all';

    public static $countUsers;
    public static $exitSegmentation = 0;

    /**
     * @var AmosNotify $notifyModule
     */
    public $notifyModule = null;
    public $debugSqlNotifications = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->notifyModule = AmosNotify::instance();
        parent::init();
    }

    /**
     * @param string $actionID
     * @return array|string[]
     */
    public function options($actionID)
    {
        return ['weekMails', 'dayMails', 'monthMails', 'immediateMails', 'disableSegmentation'];
    }

    /**
     * This action sends nightly mails.
     */
    public function actionMailChannel()
    {
        try {
//            $start = microtime(true);
//            Console::stdout('start -> ' . microtime(true) . PHP_EOL);
            if (!empty($this->disableSegmentation)) {
                $this->notifyModule->enableSegmentedSend = false;
            }
            $type = $this->evaluateOperations();

            $enableCachedNotificationContents = ($this->notifyModule->enableCachedNotificationContents && $type == NotificationsConfOpt::EMAIL_DAY);
            if ($this->notifyModule && $this->notifyModule->enableNotificationContentLanguage) {
                $this->mailChannelWithLanguage();
            } else {
                Console::stdout('Begin mail-channel ' . $type . PHP_EOL);
                $segmentation = $this->getSegmentation($type);


                // se sto creando un nuovo schedule resetto l'idice di segmentzione sul server
                if ($enableCachedNotificationContents) {
                    $notificationSchedule = NotificationSchedule::notificationScheduled();
                    if (empty($notificationSchedule)) {
                        $this->setSegmentationOffset($type, 0);
                        $segmentation = $this->getSegmentation($type);
                    }
                }

                $users = $this->loadUser($type, null, $segmentation['limit'], $segmentation['offset']);

                $factory = new BuilderFactory();
                if ($type == NotificationsConfOpt::EMAIL_IMMEDIATE) {
                    Console::stdout('BUILD ' . $type . PHP_EOL);
                    $builder = $factory->create(BuilderFactory::CONTENT_IMMEDIATE_MAIL_BUILDER);
                } else {
                    $builderType = BuilderFactory::CONTENT_MAIL_BUILDER;
                    if ($enableCachedNotificationContents) {
                        $builderType = BuilderFactory::CONTENT_MAIL_CACHED_BUILDER;
                    }
                    $builder = $factory->create($builderType);
                }

                /** @var AmosCwh $cwhModule */
                $cwhModule = Yii::$app->getModule('cwh');
                if ($enableCachedNotificationContents) {
                    $this->notifyUserCached($cwhModule, $users, $builder, $type, null, $segmentation['limit']);
                } else {
                    $this->notifyUserArray($cwhModule, $users, $builder, $type, null, $segmentation['limit']);
                }
                Console::stdout('End mail-channel ' . $type . PHP_EOL);
                $exit = $this->getReturnSegmentation($users, $type, $segmentation['limit']);

//                $end = microtime(true);
//                Console::stdout('end -> ' . ($end - $start) . PHP_EOL);

                return $exit;
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * This action sends newsletter mails.
     */
    public function actionNewsletterChannel()
    {
        try {
            Console::stdout('Begin newsletter-channel ' . PHP_EOL);
            $users = $this->loadUser(NotificationsConfOpt::NEWSLETTER);
            $factory = new BuilderFactory();
            $builder = $factory->create(BuilderFactory::NEWSLETTER_BUILDER);

            /** @var AmosCwh $cwhModule */
            $cwhModule = Yii::$app->getModule('cwh');
            $this->notifyUserArrayNewsletter($cwhModule, $users, $builder);
            Console::stdout('End newsletter-channel ' . PHP_EOL);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     */
    public function mailChannelWithLanguage()
    {
        $type = $this->evaluateOperations();
        $languages = Language::find()->andWhere(['status' => 1])->all();
        Console::stdout('Begin mail-channel ' . $type . PHP_EOL);
        foreach ($languages as $language) {
            Console::stdout('     ' . PHP_EOL);
            Console::stdout('#################' . PHP_EOL);
            Console::stdout('Lingua: ' . $language->language_id . " ###" . PHP_EOL);
            Console::stdout('#################' . PHP_EOL);

            $users = $this->loadUser($type, $language);

            $factory = new BuilderFactory();
            if ($type == NotificationsConfOpt::EMAIL_IMMEDIATE) {
                Console::stdout('BUILD ' . $type . PHP_EOL);
                $builder = $factory->create(BuilderFactory::CONTENT_IMMEDIATE_MAIL_BUILDER);
            } else {
                $builder = $factory->create(BuilderFactory::CONTENT_MAIL_BUILDER);
            }
            /** @var AmosCwh $cwhModule */
            $cwhModule = Yii::$app->getModule('cwh');
            $this->notifyUserArray($cwhModule, $users, $builder, $type, $language->language_id);
        }
        Console::stdout('End mail-channel ' . $type . PHP_EOL);
    }

    /**
     *
     */
    public function actionSMSChannel()
    {
        try {

        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param $notify_id
     * @param $reader_id
     * @param $type
     * @return void
     */
    protected function notifyReadFlag($notify_id, $reader_id, $type = null)
    {
        try {
            /** @var NotificationsRead $model */
            $model = $this->notifyModule->createModel('NotificationsRead');
            $model->notification_id = $notify_id;
            $model->user_id = $reader_id;
            $model->notification_type = $type;
            $model->save(false);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
    }

    /**
     *
     * @param string $schedule
     * @param string $language
     * @param int $limit
     * @param int $offset
     * @return array|null
     */
    public function loadUser($schedule, $language = null, $limit = 0, $offset = 0, $onlyMaxId = false, $user_ids = [])
    {
        $useSegmentation = ($this->notifyModule->enableSegmentedSend && ($limit != $offset));
        $enableCachedNotificationContents = $this->notifyModule->enableCachedNotificationContents;

//        $result = null;
//        try {
        $module = AmosNotify::getInstance();
        /** @var AmosAdmin $adminModule */
        $adminModule = Yii::$app->getModule(AmosAdmin::getModuleName());

        $query = new Query();
        $query->from(UserProfile::tableName());
        $query->innerJoin(User::tableName(), UserProfile::tableName() . '.user_id = ' . User::tableName() . '.id');
        $query->leftJoin(NotificationConf::tableName(),
            NotificationConf::tableName() . '.user_id = ' . UserProfile::tableName() . '.user_id');

        if ($this->notifyModule->enableNotificationContentLanguage) {
            $query->leftJoin('notification_language_preferences',
                'notification_language_preferences.user_id = ' . UserProfile::tableName() . '.user_id');
            $query->andWhere(['OR',
                ['notification_language_preferences.language' => $language->language_id],
                ['notification_language_preferences.language' => null]
            ]);
        }

        $query->andWhere(['OR',
            [NotificationConf::tableName() . '.notifications_enabled' => 1],
            [NotificationConf::tableName() . '.notifications_enabled' => NULL],
        ]);
        $query->andWhere([UserProfile::tableName() . '.deleted_at' => null]);
        $query->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere([User::tableName() . '.status' => User::STATUS_ACTIVE]);
        $checkPrivacy = false;
        if (
            $adminModule->confManager->isVisibleBox('box_privacy', ConfigurationManager::VIEW_TYPE_FORM) &&
            $adminModule->confManager->isVisibleField('privacy', ConfigurationManager::VIEW_TYPE_FORM)
        ) {
            $checkPrivacy = true;
            $query->andWhere([UserProfile::tableName() . '.privacy' => 1]);
        }

        // clone the query withou the filter for type o cron
        $queryConfCommunity = clone $query;

        if ($schedule == NotificationsConfOpt::NEWSLETTER) {
            // If the schedule is newsletter check if the user has the newsletter notifications enabled in his profile.
            $query->andWhere([NotificationConf::tableName() . '.notify_newsletter' => 1]);
        } else {
            // filter the query for type of cron
            if ($schedule == $this->notifyModule->defaultSchedule) {
                $query->andWhere(['or',
                    [NotificationConf::tableName() . '.email' => $schedule],
                    [NotificationConf::tableName() . '.email' => null]
                ]);
            } else {
                $query->andWhere([NotificationConf::tableName() . '.email' => $schedule]);
            }
        }

        $query->orderBy([UserProfile::tableName() . '.user_id' => SORT_ASC]);
        $query->select(UserProfile::tableName() . '.*');

        // query for network notificatiomn
        if (!empty($schedule)) {
            $queryConfCommunity
                ->orderBy([UserProfile::tableName() . '.user_id' => SORT_ASC . ', notificationconf_network.*'])
                ->select(UserProfile::tableName() . '.*')
                ->innerJoin('notificationconf_network', 'notificationconf_network.user_id = user_profile.user_id')
                ->andWhere(['IS NOT', 'record_id', null])
                ->andWhere(['IS NOT', 'models_classname_id', null])
                ->andWhere(['notificationconf_network.email' => $schedule]);
            if ($useSegmentation == true) {
                $queryConfCommunity->andWhere(['>', UserProfile::tableName() . '.user_id', $offset]);
                $queryConfCommunity->andWhere(['<=', UserProfile::tableName() . '.user_id', $limit]);
            }
            if ($checkPrivacy == true) {
                $queryConfCommunity->andWhere([UserProfile::tableName() . '.privacy' => 1]);
            }
        }

//            $queryConfCommunity->andWhere(['user_profile.user_id' => [1, 4, 5]]);
        if ($useSegmentation == true) {
            $query->andWhere(['>', UserProfile::tableName() . '.user_id', $offset]);
            $query->andWhere(['<=', UserProfile::tableName() . '.user_id', $limit]);
        }

        // per cached notification
        if ($enableCachedNotificationContents) {
            $notificationSchedule = NotificationSchedule::notificationScheduled();
            if ($notificationSchedule && $notificationSchedule->type == $schedule && $notificationSchedule->last_notified_user_id > 0) {
                $query->andWhere(['>', 'user_profile.user_id', $notificationSchedule->last_notified_user_id]);
                $queryConfCommunity->andWhere(['>', 'user_profile.user_id', $notificationSchedule->last_notified_user_id]);
            }
        }

//        $query->andWhere(['<', 'user_profile.user_id', 20]);
//        $queryConfCommunity->andWhere(['<', 'user_profile.user_id', 20]);
        if (!empty($user_ids)) {
            $queryConfCommunity->andWhere(['user_profile.user_id' => $user_ids]);
            $query->andWhere(['user_profile.user_id' => $user_ids]);
        }


        $union = $query->union($queryConfCommunity);

        $moduleUtility = \Yii::$app->getModule('utility');
        if ($moduleUtility && class_exists('open20\amos\utility\models\PlatformConfigs')) {
            $config = \open20\amos\utility\models\PlatformConfigs::getConfigValue('notify', 'send_email_summary_to');
            if ($config) {
                if (!empty($config->value)) {
                    $ids = explode(',', $config->value);
                    $query->andWhere(['user_profile.user_id' => $ids]);
                    $queryConfCommunity->andWhere(['user_profile.user_id' => $ids]);
                }
            }
        }
//   $query->andWhere(['user_profile.user_id' => [1,4,5]]);

        $queryTotal = new Query();
        $queryTotal->select('*')->from(['u' => $union])->orderBy(['user_id' => SORT_ASC]);
//        Console::stdout($queryTotal->createCommand()->rawSql.PHP_EOL);
        $result = $queryTotal->all();

//            Console::stdout('offset ->'. $offset.' limit ->'.$limit.PHP_EOL);
//            foreach($result as $a){
//                Console::stdout($a->id .PHP_EOL);
//                pr($a->attributes);
//            }

        if ($onlyMaxId == true) {
            $cloned = clone $union;
            $queryUnion = new Query();
            $queryUnion->select(new \yii\db\Expression("max(user_id) as counter"))
                ->from($cloned);
//            Console::stdout('####### COUNT #####' . $queryUnion->one()['counter'] . PHP_EOL);
            return $queryUnion->one()['counter'];
        }

//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
//        }

        return $result;
    }

    /**
     * @return int
     */
    protected function evaluateOperations()
    {
        $retValue = NotificationsConfOpt::EMAIL_DAY;

        if ($this->dayMails) {
            $retValue = NotificationsConfOpt::EMAIL_DAY;
        } elseif ($this->weekMails) {
            $retValue = NotificationsConfOpt::EMAIL_WEEK;
        } elseif ($this->monthMails) {
            $retValue = NotificationsConfOpt::EMAIL_MONTH;
        } elseif ($this->immediateMails) {
            $retValue = NotificationsConfOpt::EMAIL_IMMEDIATE;
        }

        return $retValue;
    }

    /**
     *
     */
    public function actionScopeMailChannel()
    {
        $comminities = [2634];

        if (\Yii::$app->getModule('community')) {
            foreach ($comminities as $comminity_id) {
                Console::stdout('Start scope-mail-channel for community:' . $comminity_id . PHP_EOL);
                $community = \open20\amos\community\models\Community::findOne(['id' => $comminity_id]);
                if (!is_null($community)) {
                    $users = $community->getCommunityUserMms()->select('user_profile.user_id, user_profile.created_at')->all();
                    /** @var AmosCwh $cwhModule */
                    $cwhModule = Yii::$app->getModule('cwh');
                    $cwhModule->scope = ['community' => $comminity_id];
                    $factory = new BuilderFactory();
                    $builder = $factory->create(BuilderFactory::CONTENT_MAIL_BUILDER);
                    $this->notifyUserArray($cwhModule, $users, $builder);
                }
                Console::stdout('End scope-mail-channel for community:' . $comminity_id . PHP_EOL);
            }

            $cwhModule->scope = null;
        }
    }

    /**
     * @param $cwhModule
     * @param $users
     * @param $builder
     * @param null $type
     * @param null $language
     * @param int $offset
     * @param null $channel
     * @throws \yii\base\InvalidConfigException
     */
    public function notifyUserCached($cwhModule, $users, $builder, $type = null, $language = null, $offset = 0, $channel = null, $simulate = false)
    {
        $type = NotificationsConfOpt::EMAIL_DAY;

        /** @var AmosNotify $notifyModule */
        $notifyModule = AmosNotify::instance();
        $moduleCwh = \Yii::$app->getModule('cwh');
        $orderClassnameSections = $this->notifyModule->orderEmailSummary;

        //OTTENGO I MODEL ABILITATI PER LE NOTIFICHE
        $modelsEnabled = $this->getModelsEnabled($moduleCwh);

        //SET IN CACHE MODULES CONFIGS (viewPaths)
        CachedContentMailBuilder::setModulesConfigsCache($modelsEnabled);

        //GET ALL CONTENT NOTIFICATIONS RECORD
        $notificationSchedule = NotificationSchedule::notificationScheduled();
        $query = $this->getAllNotificationRecordsQuery($modelsEnabled, null, null, $notificationSchedule);
        $res = $query->asArray()->all();

        // GET NOTIFICATION OF COMMENTS
        $queryComments = $this->getAllNotificationRecordsQuery($modelsEnabled, $channel, 'open20\amos\comments\models\Comment');
        $resComments = $queryComments->asArray()->all();

        //CREATE NOTIFICATION SCHEDULE IF IS FIRST EXECUTION
        if (!$simulate && empty($notificationSchedule)) {
            $maxUserIdToSend = $this->getMaxUserIdToSend($type);
            $segmentationInfos = $this->getSegmentation($type);
            $notificationSchedule = NotificationSchedule::createSchedule($res, $resComments, $type, $maxUserIdToSend, $segmentationInfos);
            if (count($res) == 0) {
                $notificationSchedule->status = NotificationSchedule::STATUS_DONE;
                $notificationSchedule->ended_at = date('Y-m-d H:i:s');
                $notificationSchedule->save(false);
                self::$exitSegmentation = 1;
//                Console::stdout('OUT'.PHP_EOL);
            }
        }

        //RENDER ALL CONTENTS AND SAVE THE HTML IN CACHE
        $this->renderContentsMemoized($res, $resComments, $orderClassnameSections, $notifyModule, $simulate, $notificationSchedule);

        //FIND CONTENTS MATCHING WITH USER -> COMPOSE EMAIL AND SEND
        $this->matchContentUser($users, $type, $cwhModule, $builder, $language, $offset, $notificationSchedule, $simulate);
//        Console::stdout(VarDumper::dumpAsString($res, 3, false) . "\n");
    }


    /**
     * @param $type
     * @return array|null
     */
    public function getMaxUserIdToSend($type)
    {
        return $this->loadUser($type, null, 0, 0, true);
    }


    /**
     * @param $modelsEnabled
     * @param null $channel
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getAllNotificationRecordsQuery($modelsEnabled, $channel = null, $classname = null, $notificationSchedule = null)
    {

        //Se è già creato il notifiation schedule evito di fare tutta la ricerca
        if ($notificationSchedule) {
            $query = $notificationSchedule->getNotifications();
            if (!empty($classname)) {
                $query->andWhere(['notification.class_name' => $classname]);
            } else {
                $query->andWhere(['!=', 'notification.class_name', 'open20\amos\comments\models\Comment']);
            }
        } else {
            $moduleCwh = \Yii::$app->getModule('cwh');
            $orderByField = $this->getOrderModelsToNotify();
            $channelInternal = (!is_null($channel) ? $channel : NotificationChannels::CHANNEL_MAIL);

            /** @var Notification $notificationModel */
            $notificationModel = $this->notifyModule->createModel('Notification');
            $notificationTable = $notificationModel::tableName();

            /** @var NotificationsRead $notificationReadModel */
            $notificationReadModel = $this->notifyModule->createModel('NotificationsRead');
            $notificationReadTable = $notificationReadModel::tableName();

            /** @var NotificationSendEmail $notificationSendEmailModel */
            $notificationSendEmailModel = $this->notifyModule->createModel('NotificationSendEmail');
            $notificationSendEmailTable = $notificationSendEmailModel::tableName();


            // GET ALL NEW CONTENTS TO NOTIFY
            // prendo tutte le notifiche che non sono state inserite in un uno schedule
            $query = $notificationModel::find()
                ->leftJoin($notificationReadTable,
                    [
                        $notificationTable . '.id' => new Expression($notificationReadTable . '.notification_id'),
                        $notificationReadTable . '.notification_type' => NotificationsConfOpt::EMAIL_DAY
                    ])
                ->andWhere([$notificationTable . '.channels' => $channelInternal])
                ->andWhere([$notificationReadTable . '.user_id' => null]);

            //tolgo i contenuti già notificati e presenti in uno schedule
            $query
                ->leftJoin('notification_schedule_content', 'notification.id = notification_schedule_content.notification_id')
                ->leftJoin('notification_schedule', 'notification_schedule.id = notification_schedule_content.notification_schedule_id')
                ->andWhere(['notification_schedule.type' => null]);

            // DATA MINIMA PARTENZA NOTIFICHE
            if (isset($this->notifyModule->batchFromDate)) {
                $query->andWhere(['>=', $notificationTable . '.created_at', strtotime($this->notifyModule->batchFromDate)]);
            }

            //PRENDO LE NOTIFICHE DI UN SOLO MODEL (commenti per il momento)
            if ($classname) {
                // Console::stdout($query->createCommand()->rawSql. PHP_EOL);
                $query->andWhere([$notificationTable . '.class_name' => $classname]);
            } else {

                //MOSTRO SOLO I MODELLI ABILITATI CON CWH
                if (!empty($modelsEnabled)) {
                    $query->andWhere([$notificationTable . '.class_name' => $modelsEnabled]);
                }

                // INVIA NOTIFICHE PREVIO MODALE DI CONFERMA
                if ($this->notifyModule->confirmEmailNotification) {
                    $query->innerJoin($notificationSendEmailTable,
                        $notificationTable . '.class_name = ' . $notificationSendEmailTable . '.classname AND ' . $notificationTable . '.content_id = ' . $notificationSendEmailTable . '.content_id');
                }

                // ORDINE NOTIFICHE
                if (!empty($orderByField)) {
                    $query->orderBy(new Expression('FIELD(class_name, ' . $orderByField . ') DESC, class_name'));
                } else {
                    $query->orderBy('class_name');
                }
            }
//                    Console::stdout($query->createCommand()->rawSql . PHP_EOL);
//                    Console::stdout('--------------------'. PHP_EOL);

        }

        return $query;
    }

    /**
     * @param $moduleCwh
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getModelsEnabled($moduleCwh)
    {
        $modelsEnabled = [];
        if ($moduleCwh) {
            $contentToNotNotify = $this->notifyModule->contentToNotNotify;
            $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()
                ->addSelect('classname')
                ->andWhere(['NOT IN', 'classname', $contentToNotNotify])
                ->column();
        }
        return $modelsEnabled;
    }

    /**
     * @param $res
     * @param $orderClassnameSections
     * @throws \yii\base\InvalidConfigException
     */
    public function renderContentsMemoized($res, $resComments, $orderClassnameSections, $notifyModule, $simulate, $schedule = null)
    {
        $langs = ['it-IT', 'en-GB'];
        $now = new \DateTime();
        $modelClassnameCommunityId = null;
        $modelClassnameCommunity = ModelsClassname::find()->andWhere(['table' => 'community'])->one();
        if ($modelClassnameCommunity) {
            $modelClassnameCommunityId = $modelClassnameCommunity->id;
        }

        //CICLO LE LINGUE DISPONIBILI
        foreach ($langs as $lang) {
            NotifyUtility::printConsole('=======>LINGUA ' . $lang . PHP_EOL);
            \Yii::$app->language = $lang;

            // RENDER TITOLI SEZIONI
            foreach ($orderClassnameSections as $classname) {
                $viewPath = CachedContentMailBuilder::getViewPathContentTitle($classname);
                CachedContentMailBuilder::renderContentTitleMemoized($viewPath, $classname);
            }

            //RENDER COMMENTI
            $renderedComments = [];
            foreach ($resComments as $commentNotification) {
                $comment_classname = $commentNotification['class_name'];
                $comment_content_id = $commentNotification['content_id'];
                $modelComment = $comment_classname::find()->andWhere(['id' => $comment_content_id])->one();
                if ($modelComment) {
                    $renderedComments[$modelComment->context][$modelComment->context_id][] = \Yii::$app->controller->renderPartial("@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/_content_comment_cached", ['comment' => $modelComment]);
                    NotifyUtility::printConsole('Comment ' . "$modelComment->context - $modelComment->context_id" . PHP_EOL);
                }
            }

            // CICLA NOTIFICHE E RENDER CONTENUTI
            $classCounts = [];
            foreach ($res as $notification) {
                $classname = $notification['class_name'];
                $content_id = $notification['content_id'];
                // trova gli oggetti
                if (!empty($classname) && class_exists($classname)) {
                    $modelContent = $classname::find()->andWhere(['id' => $content_id])->one();
                    if ($modelContent) {

                        //segno da non notificare se non è arrivata la data di pubblicazione
                        if (!$simulate) {
                            if (!empty($schedule) && method_exists($modelContent, 'getPublicatedFrom')) {
                                $publicatedFrom = $modelContent->getPublicatedFrom();
                                if (!empty($publicatedFrom)) {
                                    $datePubblicazioneStart = new \DateTime($publicatedFrom);
                                    if ($datePubblicazioneStart > $now) {
                                        NotificationScheduleContent::deleteAll(['notification_id' => $notification['id'], 'notification_schedule_id' => $schedule->id]);
                                    }
                                }
                            }
                        }

                        $modelClassname = ModelsClassname::find()->andWhere(['classname' => $classname])->asArray()->one();
                        if ($modelClassname) {
                            // -------- DEFAULT VIEW PATH ------
                            $isCommunityNetwork = !empty($notification['models_classname_id']) && $notification['models_classname_id'] == $modelClassnameCommunityId;
                            $viewPath = CachedContentMailBuilder::getViewPathContent($isCommunityNetwork, $classname);
                        }
                        //conto i contenuti per ogni classe
                        if (!isset($classCounts[$classname])) {
                            $classCounts[$classname] = 1;
                        } else {
                            $classCounts[$classname] += 1;
                        }

                        // GET NETWORK OBJECT COMMUNITY
                        $modelNetwork = null;
                        if (!empty($notification['models_classname_id']) && $notification['models_classname_id'] == $modelClassnameCommunityId) {
                            if (!in_array($notification['record_id'], CachedContentMailBuilder::$cacheCommunities)) {
                                $community = Community::findOne($notification['record_id']);
                                if ($community) {
                                    $modelNetwork = CachedContentMailBuilder::$cacheCommunities[$community->id] = $community;
                                }
                            }
                        }
                        CachedContentMailBuilder::renderContentItemsMemoized($viewPath, $notification, $modelContent, $modelNetwork, $notifyModule, $renderedComments, $modelClassnameCommunityId);
                    }
                }
            }

//            $arrayModelsComments[$notify->models_classname_id][$notify->record_id][$model->context][$model->context_id][] = $model;


            //RENDER ITEM EXTRA
            foreach ($classCounts as $classname => $count) {
                if ($count > 0) {
                    $viewPath = CachedContentMailBuilder::getViewPathContentItemExtra($classname);
                    if (!empty($viewPath)) {
                        CachedContentMailBuilder::renderContentItemExtra($viewPath, $classname);
                    }
                }
            }

            //RENDER TITLE NETWORK
//        $viewPath = "@vendor/open20/amos-" . AmosNotify::getModuleName() . "/src/views/email/content_title_network";
            $viewPathNetwork = CachedContentMailBuilder::getViewPathTitleNetwork();
            foreach (CachedContentMailBuilder::$cacheCommunities as $community) {
                CachedContentMailBuilder::renderTitleNetworkMemoized($viewPathNetwork, $community);
            }
        }
    }

    /**
     * @param $users
     * @param $type
     * @param $moduleCwh
     * @param $builder
     * @param $language
     * @param $offset
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function matchContentUser($users, $type, $moduleCwh, $builder, $language, $offset, $notificationSchedule, $simulate = false)
    {
        $useSegmentation = ($this->notifyModule->enableSegmentedSend && in_array($type, $this->notifyModule->segmentationEnabledFor) && $offset > 0);
        // try {
        if ($moduleCwh) {
            $contentToNotNotify = $this->notifyModule->contentToNotNotify;
            $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()
                ->addSelect('classname')
                ->andWhere(['NOT IN', 'classname', $contentToNotNotify])
                ->column();
        }

        /** @var Notification $notificationModel */
        $notificationModel = $this->notifyModule->createModel('Notification');
        $notificationTable = $notificationModel::tableName();
        $notificationSendEmailModel = $this->notifyModule->createModel('NotificationSendEmail');
        $notificationSendEmailTable = $notificationSendEmailModel::tableName();

        $connection = \Yii::$app->db;
        $typeOFnotification = [self::TYPE_OF_SECTION_ALL];

        foreach ((array)$users as $user) {
            $results = null;
            $query = null;

            $transaction = $connection->beginTransaction();
            $uid = $user['user_id'];

            $startUser = NotifyUtility::debugDurationMicrotime('debug_notify_elastic.txt', "USER $uid - ");
            NotifyUtility::printConsole('Start working on user ' . $uid . PHP_EOL);

            /** @var NotificationConf $notificationConfModel */
            $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
            /** @var NotificationConf $notificationconf */
            $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $uid])->one();
            $notify_editorial_staff = $user['notify_from_editorial_staff'];

            $isLanguageOk = $this->checkNotificationUserLanguage($uid, $language);
            if ($language == null || (!empty($language) && $isLanguageOk)) {


                if (!empty($notificationconf)) {
                    foreach ($typeOFnotification as $typeOfNotify) {
                        $query = $this->getNotifications($type, $typeOfNotify, $user);

                        //se le notifiche generali sono settate a 'type' non invio notifiche se non quelle delle community
                        if ($notificationconf->email != $type) {
                            $query->andWhere(['is not', 'notification.models_classname_id', null]);
                            $query->andWhere(['is not', 'notification.record_id', null]);
                        }

                        // Get the netowrks to not notify
                        $notificationNetworkConfDontNotify = NotifyUtility::getNetworkNotificationConf($uid,
                            $type);
                        $networkConfArray = [];
                        foreach ($notificationNetworkConfDontNotify as $networkConf) {
                            $networkConfArray[$networkConf->models_classname_id] = $networkConf->record_id;
                        }
                        $networkConfArray = $this->setOtherExclusions($networkConfArray, $uid);
                        if (!empty($networkConfArray)) {
                            foreach ($networkConfArray as $classname_id => $record_id) {

                                if (!empty($classname_id) && !empty($record_id) && is_numeric($record_id)) {
                                    $query->andWhere(['or',
                                        [
                                            'AND',
                                            ['models_classname_id' => $classname_id],
                                            ['!=', 'record_id', $record_id]
                                        ],
                                        ['and',
                                            ['!=', 'models_classname_id', $classname_id],
                                        ],
                                        ['or',
                                            ['IS', 'models_classname_id', null],
                                            ['IS', 'record_id', null],
                                        ],
                                    ]);
                                }
                            }
                        }

                        if (isset($this->notifyModule->batchFromDate)) {
                            $query->andWhere(['>=', $notificationTable . '.created_at', strtotime($this->notifyModule->batchFromDate)]);
                        }

                        if ($this->notifyModule->confirmEmailNotification) {
                            $query->innerJoin($notificationSendEmailTable,
                                $notificationTable . '.class_name = ' . $notificationSendEmailTable . '.classname AND ' . $notificationTable . '.content_id = ' . $notificationSendEmailTable . '.content_id');
                        }


                        if (isset($moduleCwh)) {
                            $andWhere = "";
                            $i = 0;
                            foreach ($modelsEnabled as $classname) {
                                //NotifyUtility::printConsole('enabled '.$classname.PHP_EOL);

                                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname,
                                    [
                                        'queryBase' => $classname::find(),
                                        'userId' => $uid
                                    ]);

                                //Skip content tha are not enabled in your profile
                                $skip = $this->skipContentNotifyConfig($notificationconf, $classname);
                                if ($skip) {
                                    continue;
                                }

                                $cwhActiveQuery::$userProfile = null; //reset user profile

                                /** if exist table news and module disable sending notification to certain types of news */
                                $tableNews = Yii::$app->db->schema->getTableSchema('news');
                                $tableCategoryNews = Yii::$app->db->schema->getTableSchema('news_categorie');
                                if (class_exists($classname) && $tableNews && $tableCategoryNews && isset($tableCategoryNews->columns['notify_category'])) {
                                    /** @var \open20\amos\news\AmosNews|null $newsModule */
                                    $newsModule = (class_exists('open20\amos\news\AmosNews') ? \open20\amos\news\AmosNews::instance()
                                        : null);
                                    $newsModelClassname = (!is_null($newsModule) ? $newsModule->model('News') : 'open20\amos\news\models\News');
                                    if ($classname == $newsModelClassname) {
                                        /** @var Notification $notificationModel */
                                        $notificationModel = $this->notifyModule->createModel('Notification');
                                        $newsNotNotificationNotToSend = $notificationModel::find()
                                            ->select($notificationTable . '.id')
                                            ->innerJoin('news',
                                                $notificationTable . ".content_id = news.id AND " . $notificationTable . ".class_name = '" . addslashes($classname) . "'")
                                            ->innerJoin('news_categorie',
                                                'news_categorie.id = news.news_categorie_id')
                                            ->andWhere(['notify_category' => 0]);
                                        $query->andWhere(['NOT IN', $notificationTable . '.id', $newsNotNotificationNotToSend]);
                                    }
                                }

                                $model = new $classname;
                                if ($model instanceof NotificationPersonalizedQueryInterface) {
                                    $queryModel = $model->getNotificationQuery($user, $cwhActiveQuery);
                                } else {
                                    $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                                }

                                if (!empty($language)) {
                                    $queryModel = $this->getNotificationContentLanguageQuery($queryModel,
                                        $classname, $language);
                                }


                                if (!is_null($notify_editorial_staff) && $notify_editorial_staff == 0) {
                                    // 1 - publication for all users
                                    $cwhConfigContent = CwhConfigContents::find()->andWhere(['classname' => $classname])->one();
                                    if ($cwhConfigContent) {
                                        $queryModel->innerJoin('cwh_pubblicazioni',
                                            'cwh_pubblicazioni.content_id = ' . $classname::tableName() . '.id AND cwh_pubblicazioni.cwh_config_contents_id = ' . $cwhConfigContent->id);
                                        $queryModel->andWhere([
                                            'OR',
                                            ['!=', 'cwh_pubblicazioni.cwh_regole_pubblicazione_id', 1],
                                            ['cwh_pubblicazioni.ignore_notify_editorial_staff' => 1],
                                        ]);
                                        //cwh_pubblicazioni.ignore_notify_editorial_staff di default è a false, è possibile settarlo a true con il checkbox nella cwh
                                        // se il flag è true ignora il flag notify_editorial_staff in user_profile}
                                    }
                                }

//                                        if ($typeOfNotify == self::TYPE_OF_SECTION_NORMAL && $classname == 'open20\\amos\\een\\models\\EenPartnershipProposal') {
//                                            NotifyUtility::printConsole($queryModel->createCommand()->rawSql . PHP_EOL);
//                                            NotifyUtility::printConsole('' . PHP_EOL);
//                                        }

                                if ($simulate) {
                                    $this->debugSqlNotifications[$uid]['models'][$classname] = $queryModel->createCommand()->rawSql;
                                }

                                $modelIds = $queryModel->select($classname::tableName() . '.id')->column();
                                if (!empty($modelIds)) {
                                    if ($i != 0) {
                                        $andWhere .= " OR ";
                                    }
                                    $andWhere .= '(' . $notificationTable . ".class_name = '" . addslashes($classname) . "' AND " . $notificationTable . '.content_id in (' . implode(',',
                                            $modelIds) . '))';
                                    $i++;
                                }
                                unset($cwhActiveQuery);
                            }
                            //NOTIFICATION WITHOU CHW
                            if ($typeOfNotify == self::TYPE_OF_SECTION_ALL && !empty($this->notifyModule->addNotificationWithoutCwh)) {
                                $queryCustom = $this->getNotifications($type, $typeOfNotify, $user);
                                foreach ($this->notifyModule->addNotificationWithoutCwh as $customModel => $method) {
                                    $queryCustomIds = $customModel::$method()->select('id');
                                    $queryCustom->andWhere([$notificationTable . '.class_name' => $customModel])
                                        ->andWhere([$notificationTable . '.content_id' => $queryCustomIds]);
                                    $resultsCustom[self::TYPE_OF_SECTION_ALL] = $queryCustom->all();
                                }
                            }
                            if (!empty($andWhere)) {
                                $query->andWhere($andWhere);
                            } else {
                                if (empty($resultsCustom)) {
                                    NotifyUtility::printConsole('End working on user without interest ' . $uid . PHP_EOL);
                                    $transaction->commit();
                                    continue 2;
                                }
                            }

//                          NotifyUtility::printConsole($query->createCommand()->rawSql . "\n");
                            //get notifications
                            $results[$typeOfNotify] = $query->all();
                            if (!empty($resultsCustom[$typeOfNotify]) && $typeOfNotify == self::TYPE_OF_SECTION_ALL) {
                                $results[$typeOfNotify] = \yii\helpers\ArrayHelper::merge($results[$typeOfNotify], $resultsCustom[$typeOfNotify]);
                            }

                            // get comments notification
                            $results[self::TYPE_OF_SECTION_COMMENTS] = $this->getNotifications($type,
                                self::TYPE_OF_SECTION_COMMENTS, $user)->all();

                            if ($simulate) {
                                $this->debugSqlNotifications[$uid]['generic'] = $query->createCommand()->rawSql;
                            }
                        }
                    }
                }
            }


            if (!empty($results) && (!empty($results['all']))) {
//                NotifyUtility::printConsole(VarDumper::dumpAsString($results, 3, false) . "\n");
                // COMPOSE EMAIL AND SEND
                $builder->sendEmailMultipleSectionsCached($uid, $results['all']);
                // set notification as read
                foreach ($results['all'] as $notify) {
                    /** @var Notification $notify */
                    if (!$simulate) {
                        $this->notifyReadFlag($notify->id, $uid, $type);
                    }
                }
                // set comment notifications as read
                $c = 0;
                foreach ($results[self::TYPE_OF_SECTION_COMMENTS] as $notifyComment) {
                    $c++;
                    if (!$simulate) {
                        $this->notifyReadFlag($notifyComment->id, $uid, $type);
                    }
                }
                NotifyUtility::printConsole('comments:' . $c . PHP_EOL);
            }

            // setto a che punto sono arrivato con l'invio
            if (!$simulate) {
                $notificationSchedule->last_notified_user_id = $uid;
                if ($notificationSchedule->isLastUserToSend($uid)) {
                    $notificationSchedule->status = NotificationSchedule::STATUS_DONE;
                    $notificationSchedule->ended_at = date('Y-m-d H:i:s');
                    self::$exitSegmentation = 1;
                }
                $notificationSchedule->save(false);
            }


            unset($query);
            unset($queryModel);

            $transaction->commit();
            $transaction = null;
            gc_collect_cycles();

//            NotifyUtility::debugDurationMicrotime('debug_notify_elastic.txt', 'USER', 'end', $startUser);
            NotifyUtility::printConsole('End working on user ' . $uid . PHP_EOL);
            NotifyUtility::printConsole('----------- ' . PHP_EOL);
        }

        if ($useSegmentation) {
            NotifyUtility::printConsole('---- OFFSET CRON ' . $offset . PHP_EOL);
            $this->setSegmentationOffset($type, $offset);
        }
        /* } catch (\Exception $e) {
             if (!is_null($transaction)) {
                 $transaction->rollBack();
             }
             throw $e;
         } catch (\Throwable $e) {
             if (!is_null($transaction)) {
                 $transaction->rollBack();
             }
             throw $e;
         }*/
    }


    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param ContentMailBuilder $builder
     * @throws \yii\base\InvalidConfigException
     */
    private function notifyUserArray($cwhModule, $users, $builder, $type = null, $language = null, $offset = 0)
    {
        $useSegmentation = ($this->notifyModule->enableSegmentedSend && in_array($type,
                $this->notifyModule->segmentationEnabledFor) && $offset > 0);
        $notifyLegacy = $this->notifyModule->enableLegacyNotify;

        if ($notifyLegacy) {
            $this->legacyNotifyUserArray($cwhModule, $users, $builder, $type, $offset);
        } else {

            $issetCwhModule = isset($cwhModule);
            $connection = \Yii::$app->db;
            $transaction = null;
            $typeOFnotification = [self::TYPE_OF_SECTION_NORMAL, self::TYPE_OF_SECTION_NETWORK];
            $contentToNotNotify = $this->notifyModule->contentToNotNotify;
            $results = [];
            $resultsCustom = [];
            $communityNameInSenderEmail = '';

            /** @var Notification $notificationModel */
            $notificationModel = $this->notifyModule->createModel('Notification');
            $notificationTable = $notificationModel::tableName();

            /** @var NotificationSendEmail $notificationSendEmailModel */
            $notificationSendEmailModel = $this->notifyModule->createModel('NotificationSendEmail');
            $notificationSendEmailTable = $notificationSendEmailModel::tableName();
            try {
                if ($issetCwhModule) {
                    $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()
                        ->addSelect('classname')
                        ->andWhere(['NOT IN', 'classname', $contentToNotNotify])
                        ->column();
                }

                foreach ($users as $user) {
                    $transaction = $connection->beginTransaction();
                    $uid = $user['user_id'];

                    $startUser = NotifyUtility::debugDurationMicrotime('debug_notify_legacy.txt', "USER $uid - ");
                    Console::stdout('Start working on user ' . $uid . PHP_EOL);

                    $isLanguageOk = $this->checkNotificationUserLanguage($uid, $language);
                    if ($language == null || (!empty($language) && $isLanguageOk)) {

                        /** @var NotificationConf $notificationConfModel */
                        $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
                        /** @var NotificationConf $notificationconf */
                        $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $uid])->one();
                        $notify_editorial_staff = $user['notify_from_editorial_staff'];

                        // if don't find notification conf, create the notification conf with default params
//                        if (empty($notificationconf)) {
//                            $ok = $this->notifyModule->setDefaultNotificationsConfs($uid);
//                            if ($ok) {
//                                $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $uid])->one();
//                            }
//                        }


                        if (!empty($notificationconf)) {
                            foreach ($typeOFnotification as $typeOfNotify) {
                                $query = $this->getNotifications($type, $typeOfNotify, $user);

                                //se le notifiche generali sono settate a 'type' non invio notifiche se non quelle delle community
                                if ($notificationconf->email != $type) {
                                    $query->andWhere(['is not', 'notification.models_classname_id', null]);
                                    $query->andWhere(['is not', 'notification.record_id', null]);
                                }

                                // Get the netowrks to not notify
                                $notificationNetworkConfDontNotify = NotifyUtility::getNetworkNotificationConf($uid,
                                    $type);


                                $networkConfArray = [];
                                foreach ($notificationNetworkConfDontNotify as $networkConf) {
                                    // $networkConfArray[$networkConf->models_classname_id] = $networkConf->record_id;
                                    $networkConfArray[] = $networkConf;
                                }


                                $networkConfArray = $this->setOtherExclusions($networkConfArray, $uid, true);


                                if (!empty($networkConfArray)) {
                                    foreach ($networkConfArray as $na) {

                                        if (!empty($na->models_classname_id) && !empty($na->record_id)) {
                                            $query->andWhere(['or',
                                                [
                                                    'AND',
                                                    ['models_classname_id' => $na->models_classname_id],
                                                    ['!=', 'record_id', $na->record_id]
                                                ],
                                                ['and',
                                                    ['!=', 'models_classname_id', $na->models_classname_id],
                                                ],
                                                ['or',
                                                    ['IS', 'models_classname_id', null],
                                                    ['IS', 'record_id', null],
                                                ],
                                            ]);
                                        }
                                    }
                                }


                                if ($this->notifyModule->confirmEmailNotification) {
                                    $query->innerJoin($notificationSendEmailTable,
                                        $notificationTable . '.class_name = ' . $notificationSendEmailTable . '.classname AND ' . $notificationTable . '.content_id = ' . $notificationSendEmailTable . '.content_id');
                                }


                                if (isset($this->notifyModule->batchFromDate)) {
                                    $query->andWhere(['>=', $notificationTable . '.created_at', strtotime($this->notifyModule->batchFromDate)]);
                                }

                                if ($issetCwhModule) {
                                    $andWhere = "";
                                    $i = 0;

                                    foreach ($modelsEnabled as $classname) {
                                        $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname,
                                            [
                                                'queryBase' => $classname::find(),
                                                'userId' => $uid
                                            ]);

                                        //Skip content tha are not enabled in your profile
                                        $skip = $this->skipContentNotifyConfig($notificationconf, $classname);
                                        if ($skip) {
                                            continue;
                                        }

                                        $cwhActiveQuery::$userProfile = null; //reset user profile

                                        /** if exist table news and module disable sending notification to certain types of news */
                                        $tableNews = Yii::$app->db->schema->getTableSchema('news');
                                        $tableCategoryNews = Yii::$app->db->schema->getTableSchema('news_categorie');
                                        if (class_exists($classname) && $tableNews && $tableCategoryNews && isset($tableCategoryNews->columns['notify_category'])) {
                                            /** @var \open20\amos\news\AmosNews|null $newsModule */
                                            $newsModule = (class_exists('open20\amos\news\AmosNews') ? \open20\amos\news\AmosNews::instance()
                                                : null);
                                            $newsModelClassname = (!is_null($newsModule) ? $newsModule->model('News') : 'open20\amos\news\models\News');
                                            if ($classname == $newsModelClassname) {
                                                /** @var Notification $notificationModel */
                                                $notificationModel = $this->notifyModule->createModel('Notification');
                                                $newsNotNotificationNotToSend = $notificationModel::find()
                                                    ->select($notificationTable . '.id')
                                                    ->innerJoin('news',
                                                        $notificationTable . ".content_id = news.id AND " . $notificationTable . ".class_name = '" . addslashes($classname) . "'")
                                                    ->innerJoin('news_categorie',
                                                        'news_categorie.id = news.news_categorie_id')
                                                    ->andWhere(['notify_category' => 0]);
                                                $query->andWhere(['NOT IN', $notificationTable . '.id', $newsNotNotificationNotToSend]);
                                            }
                                        }

                                        $model = new $classname;
                                        if ($model instanceof NotificationPersonalizedQueryInterface) {
                                            $queryModel = $model->getNotificationQuery($user, $cwhActiveQuery);
                                        } else {
                                            $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                                        }

                                        if (!empty($language)) {
                                            $queryModel = $this->getNotificationContentLanguageQuery($queryModel,
                                                $classname, $language);
                                        }


                                        if (!is_null($notify_editorial_staff) && $notify_editorial_staff == 0) {
                                            // 1 - publication for all users
                                            $cwhConfigContent = CwhConfigContents::find()->andWhere(['classname' => $classname])->one();
                                            if ($cwhConfigContent) {
                                                $queryModel->innerJoin('cwh_pubblicazioni',
                                                    'cwh_pubblicazioni.content_id = ' . $classname::tableName() . '.id AND cwh_pubblicazioni.cwh_config_contents_id = ' . $cwhConfigContent->id);
                                                $queryModel->andWhere([
                                                    'OR',
                                                    ['!=', 'cwh_pubblicazioni.cwh_regole_pubblicazione_id', 1],
                                                    ['cwh_pubblicazioni.ignore_notify_editorial_staff' => 1],
                                                ]);
                                                //cwh_pubblicazioni.ignore_notify_editorial_staff di default è a false, è possibile settarlo a true con il checkbox nella cwh
                                                // se il flag è true ignora il flag notify_editorial_staff in user_profile}
                                            }
                                        }

//                                        if ($typeOfNotify == self::TYPE_OF_SECTION_NORMAL && $classname == 'open20\\amos\\een\\models\\EenPartnershipProposal') {
//                                            Console::stdout($queryModel->createCommand()->rawSql . PHP_EOL);
//                                            Console::stdout('' . PHP_EOL);
//                                        }

                                        $modelIds = $queryModel->select($classname::tableName() . '.id')->column();
                                        if (!empty($modelIds)) {
                                            if ($i != 0) {
                                                $andWhere .= " OR ";
                                            }
                                            $andWhere .= '(' . $notificationTable . ".class_name = '" . addslashes($classname) . "' AND " . $notificationTable . '.content_id in (' . implode(',',
                                                    $modelIds) . '))';
                                            $i++;
                                        }
                                        unset($cwhActiveQuery);
                                    }
                                    if ($typeOfNotify == self::TYPE_OF_SECTION_NORMAL && !empty($this->notifyModule->addNotificationWithoutCwh)) {
                                        $queryCustom = $this->getNotifications($type, $typeOfNotify, $user);

                                        foreach ($this->notifyModule->addNotificationWithoutCwh as $customModel => $method) {
                                            $queryCustomIds = $customModel::$method()->select('id');
                                            $queryCustom->andWhere([$notificationTable . '.class_name' => $customModel])
                                                ->andWhere([$notificationTable . '.content_id' => $queryCustomIds]);
                                            $resultsCustom[self::TYPE_OF_SECTION_NORMAL] = $queryCustom->all();
                                        }
                                    }
                                    if (!empty($andWhere)) {
                                        $query->andWhere($andWhere);
                                    } else {
                                        if (empty($resultsCustom)) {
                                            Console::stdout('End working on user without interest ' . $uid . PHP_EOL);
                                            $transaction->commit();

                                            continue 2;
                                        }
                                    }


                                    $results[$typeOfNotify] = $query->all();
                                    if (!empty($resultsCustom[$typeOfNotify]) && $typeOfNotify == self::TYPE_OF_SECTION_NORMAL) {
                                        $results[$typeOfNotify] = \yii\helpers\ArrayHelper::merge($results[$typeOfNotify], $resultsCustom[$typeOfNotify]);
                                    }

                                }
                            }
                            // get comments notification
                            $results[self::TYPE_OF_SECTION_COMMENTS] = $this->getNotifications($type,
                                self::TYPE_OF_SECTION_COMMENTS, $user)->all();
                            Console::stdout(self::TYPE_OF_SECTION_COMMENTS . ': ' . count($results[self::TYPE_OF_SECTION_COMMENTS]) . PHP_EOL);


                            if (!empty($results) && (!empty($results[self::TYPE_OF_SECTION_NORMAL]) || !empty($results[self::TYPE_OF_SECTION_NETWORK]))) {
                                if ($this->notifyModule->enableCommunityNameInSenderEmail) {
                                    if ($type == NotificationsConfOpt::EMAIL_IMMEDIATE && empty($results[self::TYPE_OF_SECTION_NORMAL]) && !empty($results[self::TYPE_OF_SECTION_NETWORK])) {
                                        $communityNameInSenderEmail = $this->getCommunityNameInSenderEmail($results[self::TYPE_OF_SECTION_NETWORK]);
                                    }
                                }
                                $builder->sendEmailMultipleSections([$uid], $results['normal'], $results['network'],
                                    $results['comments'], $communityNameInSenderEmail);
                                Console::stdout('Contents notified: ' . (count($results['normal']) + count($results['network'])) . PHP_EOL);
                                foreach ($results as $result) {
                                    /** @var Notification $notify */
                                    foreach ($result as $notify) {
                                        $this->notifyReadFlag($notify->id, $uid, $type);
                                    }
                                }
                            }
                            unset($query);
                            unset($queryModel);
                        }
                    }

                    Console::stdout('End working on user ' . $uid . PHP_EOL);
                    Console::stdout('----------- ' . PHP_EOL);

                    $transaction->commit();
                    $transaction = null;
                    gc_collect_cycles();

                    NotifyUtility::debugDurationMicrotime('debug_notify_legacy.txt', 'USER', 'end', $startUser);

                }
                if ($useSegmentation) {
                    Console::stdout('---- OFFSET CRON ' . $offset . PHP_EOL);
                    $this->setSegmentationOffset($type, $offset);
                }
            } catch (\Exception $e) {
                if (!is_null($transaction)) {
                    $transaction->rollBack();
                }
                throw $e;
            } catch (\Throwable $e) {
                if (!is_null($transaction)) {
                    $transaction->rollBack();
                }
                throw $e;
            }
        }
    }

    protected function setOtherExclusions($networkConfArray, $user_id, $returnModel = false)
    {
        try {
            $moduleCommunity = \Yii::$app->getModule('community');
            if (!empty($moduleCommunity)) {
                $classnameId = \open20\amos\core\models\ModelsClassname::find()->andWhere(['classname' => 'open20\\amos\\community\\models\\Community'])->one();
                if (!empty($classnameId)) {
                    $class = 'open20\amos\community\models\CommunityUserMm';
                    $search = $class::find()->andWhere(['user_id' => $user_id])->andWhere(['or',
                        ['status' => 'GUEST'],
                        ['role' => 'GUEST']
                    ])->select('community_id')
                        ->all();
                    if (!empty($search)) {
                        foreach ($search as $communityUserMm) {
                            //  $networkConfArray[$classnameId->id] = $communityUserMm->community_id;
                            // $networkConfArray[$networkConf->models_classname_id] = $networkConf->record_id;
                            $notify = AmosNotify::instance()->createModel('NotificationconfNetwork');
                            $notify->models_classname_id = $classnameId->id;
                            $notify->record_id = $communityUserMm->community_id;
                            if ($returnModel) {
                                $networkConfArray[] = $notify;
                            } else {
                                $networkConfArray[$notify->models_classname_id] = $notify->record_id;
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
        return $networkConfArray;
    }

    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param NewsletterBuilder $builder
     * @param int|null $type
     * @param string|null $language
     * @throws NewsletterException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    private function notifyUserArrayNewsletter($cwhModule, $users, $builder, $type = null, $language = null)
    {
        if (!($builder instanceof NewsletterBuilder)) {
            throw new NewsletterException('$builder must be an instance of NewsletterBuilder');
        }

        $issetCwhModule = isset($cwhModule);
        $connection = \Yii::$app->db;
        $transaction = null;
        $typeOfNotify = self::TYPE_OF_SECTION_NORMAL;
        $results = [];

        /** @var Notification $notificationModel */
        $notificationModel = $this->notifyModule->createModel('Notification');
        $notificationTable = $notificationModel::tableName();

        /** @var NotificationSendEmail $notificationSendEmailModel */
        $notificationSendEmailModel = $this->notifyModule->createModel('NotificationSendEmail');
        $notificationSendEmailTable = $notificationSendEmailModel::tableName();

        /** @var NewsletterContentsConf $newsletterContentsConfModel */
        $newsletterContentsConfModel = $this->notifyModule->createModel('NewsletterContentsConf');
        $newsletterContentsConfTable = $newsletterContentsConfModel::tableName();

        try {
            $configurationsEnabled = $newsletterContentsConfModel::find()->addSelect('id')->orderBy(['order' => SORT_ASC])->column();

            /** @var Newsletter $newsletterModel */
            $newsletterModel = $this->notifyModule->createModel('Newsletter');

            /** @var ActiveQuery $queryNewsletters */
            $queryNewsletters = $newsletterModel::find();
            $queryNewsletters->andWhere(['status' => [Newsletter::WORKFLOW_STATUS_WAIT_SEND, Newsletter::WORKFLOW_STATUS_WAIT_RESEND]]);
            $newslettersToBeNotified = $queryNewsletters->all();

            foreach ($newslettersToBeNotified as $newsletter) {
                /** @var Newsletter $newsletter */
                if (!$newsletter->canBeSent() || !$newsletter->checkAllContentsPublished()) {
                    Console::stdout('Newsletter ' . $newsletter->id . ' cannot be sent.' . PHP_EOL);
                    continue;
                }

                $newsletterId = $newsletter->id;

                /** @var Notification $newsletterNotification */
                $newsletterNotification = $notificationModel::find()->andWhere([
                    'channels' => NotificationChannels::CHANNEL_NEWSLETTER,
                    'content_id' => $newsletterId,
                    'class_name' => $newsletterModel::className(),
                    'processed' => 0
                ])->one();

                Console::stdout('Start working on newsletter ' . $newsletterId . PHP_EOL);

                $builder->newsletter = $newsletter;
                $builder->newsletter->setSendingNewsletter();
                $countNotified = 0;

                foreach ($users as $user) {
                    $transaction = $connection->beginTransaction();
                    $uid = $user['user_id'];
                    Console::stdout('Start working on user ' . $uid . PHP_EOL);

                    /** @var NotificationConf $notificationConfModel */
                    $notificationConfModel = $this->notifyModule->createModel('NotificationConf');

                    /** @var NotificationConf $notificationconf */
                    $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $uid])->one();
                    $notify_editorial_staff = $user['notify_from_editorial_staff'];

                    $isLanguageOk = $this->checkNotificationUserLanguage($uid, $language);
                    if ($language == null || (!empty($language) && $isLanguageOk)) {
                        if (!empty($notificationconf) && $issetCwhModule) {

                            // Get newsletter notifications
                            $queryNotification = $this->getNotifications($type, $typeOfNotify, $user,
                                NotificationChannels::CHANNEL_NEWSLETTER);
                            $queryNotification->andWhere([$notificationTable . '.content_id' => $newsletterId]);
                            if ($this->notifyModule->confirmEmailNotification) {
                                $queryNotification->innerJoin($notificationSendEmailTable,
                                    $notificationTable . '.class_name = ' . $notificationSendEmailTable . '.classname AND ' . $notificationTable . '.content_id = ' . $notificationSendEmailTable . '.content_id');
                            }
                            if (isset($this->notifyModule->batchFromDate)) {
                                $queryNotification->andWhere(['>=', $notificationTable . '.created_at', strtotime($this->notifyModule->batchFromDate)]);
                            }

                            /** @var Notification $notification */
                            $notification = $queryNotification->one();

                            // Check if the current newsletter notification is to be notifies to this user.
                            if (is_null($notification)) {
                                continue;
                            }

                            $andWhere = [];
                            $i = 0;

                            /** @var NewsletterContents $emptyNewsletterContents */
                            $emptyNewsletterContents = $this->notifyModule->createModel('NewsletterContents');
                            $newsletterContentsTable = $emptyNewsletterContents::tableName();

                            /** @var ActiveQuery $query */
                            $query = $emptyNewsletterContents::find();
                            $query->innerJoinWith('newsletterContentsConf');
                            $query->andWhere([$newsletterContentsTable . '.newsletter_id' => $newsletterId]);
                            $query->orderBy([
                                $newsletterContentsConfTable . '.order' => SORT_ASC,
                                $newsletterContentsTable . '.order' => SORT_ASC
                            ]);

                            foreach ($configurationsEnabled as $configurationId) {

                                /** @var NewsletterContentsConf $newsletterContentsConf */
                                $newsletterContentsConf = $builder->getNewsletterContentsConf($configurationId);

                                /** @var string|Record $classname */
                                $classname = $newsletterContentsConf->classname;

                                // Get the ordered newsletter contents by content classname
                                $classnameContentIds = $builder->newsletter->getNewsletterContentsByContentClassnameQuery($classname)
                                    ->addSelect($newsletterContentsTable . '.content_id')->column();

                                /** @var ActiveQuery $queryBase */
                                $queryBase = $classname::find();
                                $queryBase->andWhere([$newsletterContentsConf->tablename . '.id' => $classnameContentIds]);
                                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname,
                                    [
                                        'queryBase' => $queryBase,
                                        'userId' => $uid
                                    ]);
                                $cwhActiveQuery::$userProfile = null; //reset user profile

                                /** if exist table news and module disable sending notification to certain types of news */
                                $tableNews = Yii::$app->db->schema->getTableSchema('news');
                                $tableCategoryNews = Yii::$app->db->schema->getTableSchema('news_categorie');
                                if (class_exists($classname) && $tableNews && $tableCategoryNews && isset($tableCategoryNews->columns['notify_category'])) {
                                    /** @var \open20\amos\news\AmosNews|null $newsModule */
                                    $newsModule = (class_exists('open20\amos\news\AmosNews') ? \open20\amos\news\AmosNews::instance()
                                        : null);
                                    $newsModelClassname = (!is_null($newsModule) ? $newsModule->model('News') : 'open20\amos\news\models\News');
                                    if ($classname == $newsModelClassname) {
                                        /** @var NewsletterContents $newsletterContentsModel */
                                        $newsletterContentsModel = $this->notifyModule->createModel('NewsletterContents');
                                        $newsNotNotificationNotToSend = $newsletterContentsModel::find()
                                            ->select($newsletterContentsTable . '.id')
                                            ->innerJoin('news', $newsletterContentsTable . ".content_id = news.id")
                                            ->innerJoin('news_categorie', 'news_categorie.id = news.news_categorie_id')
                                            ->andWhere(['notify_category' => 0])
                                            ->andWhere([$newsletterContentsTable . '.newsletter_contents_conf_id' => $configurationId]);
                                        $query->andWhere(['not in', $newsletterContentsTable . '.id', $newsNotNotificationNotToSend]);
                                    }
                                }

                                $model = new $classname;
                                if ($model instanceof NotificationPersonalizedQueryInterface) {
                                    $queryModel = $model->getNotificationQuery($user, $cwhActiveQuery);
                                } else {
                                    $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                                }

                                if (!empty($language)) {
                                    $queryModel = $this->getNotificationContentLanguageQuery($queryModel, $classname,
                                        $language);
                                }

                                if (!is_null($notify_editorial_staff) && $notify_editorial_staff == 0) {
                                    // 1 - publication for all users
                                    $cwhConfigContent = CwhConfigContents::find()->andWhere(['classname' => $classname])->one();
                                    if ($cwhConfigContent) {
                                        $queryModel->innerJoin('cwh_pubblicazioni',
                                            'cwh_pubblicazioni.content_id = ' . $classname::tableName() . '.id AND cwh_pubblicazioni.cwh_config_contents_id = ' . $cwhConfigContent->id);
                                        $queryModel->andWhere([
                                            'OR',
                                            ['!=', 'cwh_pubblicazioni.cwh_regole_pubblicazione_id', 1],
                                            ['cwh_pubblicazioni.ignore_notify_editorial_staff' => 1],
                                        ]);
                                    }
                                    //cwh_pubblicazioni.ignore_notify_editorial_staff di default è a false, è possibile settarlo a true con il checkbox nella cwh
                                    // se il flag è true ignora il flag notify_editorial_staff in user_profile
                                }

                                /** @var int[] $modelIds */
                                $modelIds = $queryModel->select($classname::tableName() . '.id')->column();
                                if (!empty($modelIds)) {
                                    if ($i == 0) {
                                        $andWhere[] = 'or';
                                    }
                                    $andWhere[] = [
                                        'and',
                                        ['=', $newsletterContentsTable . '.newsletter_contents_conf_id', (int)$configurationId],
                                        new Expression(
                                            \Yii::$app->db->quoteColumnName($newsletterContentsTable . '.content_id') . ' ' .
                                            (count($modelIds) > 1 ? 'IN (' . implode(', ', $modelIds) . ')' : '= ' . reset($modelIds))
                                        )
                                    ];
                                    $i++;
                                }
                                unset($cwhActiveQuery);
                                unset($queryModel);
                            }

                            if (!empty($andWhere)) {
                                $query->andWhere($andWhere);
                            } else {
                                Console::stdout('End working on user without interest ' . $uid . PHP_EOL);
                                $transaction->commit();
                                continue 2;
                            }
                            $results[$typeOfNotify] = $query->all();

                            unset($query);

                            if (!empty($results) && (!empty($results[self::TYPE_OF_SECTION_NORMAL]) || !empty($results[self::TYPE_OF_SECTION_NETWORK]))) {
                                $builder->sendEmailMultipleSections([$uid], $results[self::TYPE_OF_SECTION_NORMAL],
                                    $results[self::TYPE_OF_SECTION_NETWORK], []);
                                $this->notifyReadFlag($notification->id, $uid);
                                $countNotified++;
                            }
                        }
                    }

                    Console::stdout('End working on user ' . $uid . PHP_EOL);
                    Console::stdout('----------- ' . PHP_EOL);

                    $transaction->commit();
                    $transaction = null;
                    gc_collect_cycles();
                }

                Console::stdout('Newsletters notified to ' . $countNotified . ' users' . PHP_EOL);
                $newsletterNotification->processed = 1;
                $newsletterNotification->save(false);
                $builder->newsletter->setSentNewsletter();
                Console::stdout('End working on newsletter ' . $newsletterId . PHP_EOL);
                Console::stdout('----------- ' . PHP_EOL);
            }
        } catch (\Exception $e) {
            if (!is_null($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        } catch (\Throwable $e) {
            if (!is_null($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @param string $type
     * @param string $typeOfNotify
     * @param array $user
     * @param int|null $channel
     * @return ActiveQuery
     */
    private function getNotifications($type, $typeOfNotify, $user, $channel = null)
    {
        $orderByField = $this->getOrderModelsToNotify();
        $uid = $user['user_id'];
        $channelInternal = (!is_null($channel) ? $channel : NotificationChannels::CHANNEL_MAIL);

        /** @var Notification $notificationModel */
        $notificationModel = $this->notifyModule->createModel('Notification');
        $notificationTable = $notificationModel::tableName();

        /** @var NotificationConf $notificationConfModel */
        $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
        $notificationConfTable = $notificationConfModel::tableName();

        /** @var NotificationsRead $notificationReadModel */
        $notificationReadModel = $this->notifyModule->createModel('NotificationsRead');
        $notificationReadTable = $notificationReadModel::tableName();


        $query = $notificationModel::find()
            ->leftJoin($notificationReadTable,
                [$notificationTable . '.id' => new Expression($notificationReadTable . '.notification_id'), $notificationReadTable . '.user_id' => $uid])
            ->leftJoin($notificationConfTable,
                [$notificationTable . '.user_id' => new Expression($notificationConfTable . '.user_id')])
            ->andWhere([$notificationTable . '.channels' => $channelInternal])
            ->andWhere([$notificationReadTable . '.user_id' => null])
            ->andWhere(['>=', $notificationTable . ".created_at", new Expression("UNIX_TIMESTAMP('" . $user['created_at'] . "')")])
            ->andWhere(['>=', $notificationTable . ".created_at", new Expression('IF(' . $notificationConfTable . '.last_update_frequency is null, 0, UNIX_TIMESTAMP(' . $notificationConfTable . '.last_update_frequency))')]);

        if ($typeOfNotify == self::TYPE_OF_SECTION_ALL) {
            $query->orderBy('class_name');
        } else if ($typeOfNotify == self::TYPE_OF_SECTION_NORMAL) {
            $query->andWhere(['models_classname_id' => null, 'record_id' => null]);
            if ($channel != NotificationChannels::CHANNEL_NEWSLETTER) {
                if (!empty($orderByField)) {
                    $query->orderBy(new Expression('FIELD(class_name, ' . $orderByField . ') DESC, class_name'));
                } else {
                    $query->orderBy('class_name');
                }
            }
        } else if ($typeOfNotify == self::TYPE_OF_SECTION_NETWORK) {
            $query->andWhere(['IS NOT', 'models_classname_id', null])
                ->andWhere(['IS NOT', 'record_id', null]);
            if ($channel != NotificationChannels::CHANNEL_NEWSLETTER) {
                if (!empty($orderByField)) {
                    $query->orderBy(new Expression('models_classname_id ASC, record_id ASC, ' . 'FIELD(class_name, ' . $orderByField . ') DESC, class_name ASC'));
                } else {
                    $query->orderBy('models_classname_id ASC, record_id ASC, class_name ASC');
                }
            }
        } else if ($typeOfNotify == self::TYPE_OF_SECTION_COMMENTS) {
            $query = Notification::find()
                ->leftJoin(NotificationsRead::tableName(),
                    ['notification.id' => new Expression(NotificationsRead::tableName() . '.notification_id'), NotificationsRead::tableName() . '.user_id' => $uid])
                ->leftJoin($notificationConfTable,
                    [$notificationTable . '.user_id' => new Expression($notificationConfTable . '.user_id')])
                ->andWhere(['channels' => $channelInternal])
                ->andWhere([NotificationsRead::tableName() . '.user_id' => null])
                ->andWhere([Notification::tableName() . '.class_name' => \open20\amos\comments\models\Comment::className()])
                ->andWhere(['>=', Notification::tableName() . ".created_at", new Expression("UNIX_TIMESTAMP('" . $user['created_at'] . "')")])
                ->andWhere(['>=', $notificationTable . ".created_at", new Expression('IF(' . $notificationConfTable . '.last_update_frequency is null, 0, UNIX_TIMESTAMP(' . $notificationConfTable . '.last_update_frequency))')]);
        }
        return $query;
//        BY FIELD(class_name, 'open20\\amos\\events\\models\\Event', 'open20\\amos\\news\\models\\News', 'open20\\amos\\partnershipprofiles\\models\PartnershipProfiles', 'open20\\amos\\discussioni\\models\\DiscussioniTopic'), class_name
    }

    private function getOrderModelsToNotify()
    {
        $module = \Yii::$app->getModule('notify');
        $orderByField = '';
        if ($this->notifyModule && !empty($this->notifyModule->orderEmailSummary)) {
            $escapedClassnames = [];
            foreach ($this->notifyModule->orderEmailSummary as $classname) {
                $escapedClassnames [] = addslashes($classname);
            }
            $orderByField = ("'" . implode("','", array_reverse($escapedClassnames)) . "'");
        }
        return $orderByField;
    }

    /**
     * @param $queryModel
     * @param $classname
     * @param $language
     * @return mixed
     */
    private function getNotificationContentLanguageQuery($queryModel, $classname, $language)
    {
        $modelsclassname = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
        if ($modelsclassname) {
            $queryModel->leftJoin('notification_content_language',
                'notification_content_language.record_id = ' . $classname::tableName() . '.id')
                ->andWhere(['models_classname_id' => $modelsclassname->id])
                ->andWhere(['language' => $language]);
        }
//        if($modelsclassname->id == 6){
//            Console::stdout( $queryModel->createCommand()->rawSql . PHP_EOL);
//        }
//        if($modelsclassname->module == 'news'){
//            Console::stdout( $queryModel->createCommand()->rawSql . PHP_EOL);
//
//        }
        return $queryModel;
    }

    /**
     * @param $user_id
     * @param $language
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    private function checkNotificationUserLanguage($user_id, $language)
    {
        $languagePreferences = NotificationLanguagePreferences::find()->andWhere(['user_id' => $user_id])->all();
        if (count($languagePreferences) == 0) {
            $moduleTranslation = \Yii::$app->getModule('translations');
            if ($moduleTranslation) {
                $lang = $moduleTranslation->getUserLanguage($user_id);
                return $lang == $language;
            } else {
                return \Yii::$app->language == $language;
            }
        } else {
            foreach ($languagePreferences as $pref) {
//                Console::stdout( $pref->language .' check ' .$language . PHP_EOL);
                if ($pref->language == $language) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * @param AmosCwh|null $cwhModule
     * @param array $users
     * @param AMailBuilder $builder
     * @throws \yii\base\InvalidConfigException
     */
    private function legacyNotifyUserArray($cwhModule, $users, $builder, $type = null, $offset = 0)
    {
        $useSegmentation = ($this->notifyModule->enableSegmentedSend && in_array($type,
                $this->notifyModule->segmentationEnabledFor) && $offset > 0);
        $connection = \Yii::$app->db;
        $transaction = null;
        try {
            foreach ($users as $user) {
                $transaction = $connection->beginTransaction();
                $uid = $user['user_id'];

                /** @var NotificationConf $notificationConfModel */
                $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
                /** @var  $notificationconf NotificationConf */
                $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $uid])->one();
                Console::stdout('Start working on user ' . $uid . PHP_EOL);
                $notify_editorial_staff = $user['notify_from_editorial_staff'];

                if (!empty($notificationconf)) {
                    /** @var Notification $notificationModel */
                    $notificationModel = $this->notifyModule->createModel('Notification');
                    $orderByField = $this->getOrderModelsToNotify();
                    $query = $notificationModel::find()
                        ->leftJoin(NotificationsRead::tableName(),
                            ['notification.id' => new Expression(NotificationsRead::tableName() . '.notification_id'), NotificationsRead::tableName() . '.user_id' => $uid])
                        ->andWhere(['channels' => NotificationChannels::CHANNEL_MAIL])
                        ->andWhere([NotificationsRead::tableName() . '.user_id' => null])
                        ->andWhere(['>=', Notification::tableName() . ".created_at", new Expression("UNIX_TIMESTAMP('" . $user['created_at'] . "')")]);

                    //se le notifiche generali sono settate a 'type' non invio notifiche se non quelle delle community
                    if ($notificationconf->email != $type) {
                        $query->andWhere(['is not', 'notification.models_classname_id', null]);
                        $query->andWhere(['is not', 'notification.record_id', null]);
                    }
                    // Get the netowrks to not notify
                    $notificationNetworkConfDontNotify = NotifyUtility::getNetworkNotificationConf($uid, $type);
                    $networkConfArray = [];
                    foreach ($notificationNetworkConfDontNotify as $networkConf) {
                        $networkConfArray[$networkConf->models_classname_id] = $networkConf->record_id;
                    }
                    if (!empty($networkConfArray)) {
                        foreach ($networkConfArray as $classname_id => $record_id) {

                            if (!empty($classname_id) && !empty($record_id)) {
                                $query->andWhere(['or',
                                    [
                                        'AND',
                                        ['models_classname_id' => $classname_id],
                                        ['!=', 'record_id', $record_id]
                                    ],
                                    ['and',
                                        ['!=', 'models_classname_id', $classname_id],
                                    ],
                                    ['or',
                                        ['IS', 'models_classname_id', null],
                                        ['IS', 'record_id', null],
                                    ],
                                ]);
                            }
                        }
                    }

                    if ($this->notifyModule->confirmEmailNotification) {
                        $query->innerJoin(NotificationSendEmail::tableName(),
                            'notification.class_name = notification_send_email.classname AND notification.content_id = notification_send_email.content_id');
                    }

                    if (isset($this->notifyModule->batchFromDate)) {
                        $query->andWhere(['>=', Notification::tableName() . '.created_at', strtotime($this->notifyModule->batchFromDate)]);
                    }

                    if (isset($cwhModule)) {
                        $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()->addSelect('classname')->column();
                        $andWhere = "";
                        $i = 0;
                        foreach ($modelsEnabled as $classname) {
                            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery($classname,
                                [
                                    'queryBase' => $classname::find(),
                                    'userId' => $uid
                                ]);
                            $cwhActiveQuery::$userProfile = null; //reset user profile

                            /** if exist table news and module disable sending notification to certain types of news */
                            $tableNews = Yii::$app->db->schema->getTableSchema('news');
                            $tableCategoryNews = Yii::$app->db->schema->getTableSchema('news_categorie');
                            if (class_exists($classname) && $tableNews && $tableCategoryNews && isset($tableCategoryNews->columns['notify_category'])) {
                                if ($classname == \open20\amos\news\models\News::className()) {
                                    /** @var Notification $notificationModel */
                                    $notificationModel = $this->notifyModule->createModel('Notification');
                                    $newsNotNotificationNotToSend = $notificationModel::find()
                                        ->select('notification.id')
                                        ->innerJoin('news',
                                            "notification.content_id = news.id AND notification.class_name = '" . addslashes($classname) . "'")
                                        ->innerJoin('news_categorie', 'news_categorie.id = news.news_categorie_id')
                                        ->andWhere(['notify_category' => 0]);
                                    $query->andWhere(['NOT IN', 'notification.id', $newsNotNotificationNotToSend]);
                                }
                            }

                            $model = new $classname;
                            if ($model instanceof NotificationPersonalizedQueryInterface) {
                                $queryModel = $model->getNotificationQuery($user, $cwhActiveQuery);
                            } else {
                                $queryModel = $cwhActiveQuery->getQueryCwhOwnInterest();
                            }

                            if (!is_null($notify_editorial_staff) && $notify_editorial_staff == 0) {
                                // 1 - puublication for all users
                                $queryModel->innerJoin('cwh_pubblicazioni',
                                    'cwh_pubblicazioni.content_id = ' . $classname::tableName() . '.id AND cwh_pubblicazioni.cwh_config_contents_id = 1');
                                $queryModel->andWhere(['!=', 'cwh_pubblicazioni.cwh_regole_pubblicazione_id', 1]);
                            }
                            $modelIds = $queryModel->select($classname::tableName() . '.id')->column();
                            if (!empty($modelIds)) {
                                if ($i != 0) {
                                    $andWhere .= " OR ";
                                }
                                $andWhere .= '(' . Notification::tableName() . ".class_name = '" . addslashes($classname) . "' AND " . Notification::tableName() . '.content_id in (' . implode(',',
                                        $modelIds) . '))';
                                $i++;
                            }
                            unset($cwhActiveQuery);
                        }
                        if (!empty($andWhere)) {
                            $query->andWhere($andWhere);
                        } else {
                            Console::stdout('End working on user without interest' . $uid . PHP_EOL);
                            $transaction->commit();
                            $transaction = null;
                            continue;
                        }
                    }
                    if (!empty($orderByField)) {
                        $query->orderBy(new Expression('FIELD(class_name, ' . $orderByField . ') DESC, class_name'));
                    } else {
                        $query->orderBy('class_name');
                    }

//                                    Console::stdout($query->createCommand()->rawSql. PHP_EOL);
                    $result = $query->all();
                    if (!empty($result)) {
                        $builder->sendEmailLegacy([$uid], $result, false);
                        Console::stdout('Contents notified: ' . count($result) . PHP_EOL);
                    }
                    /** @var Notification $notify */
                    foreach ($result as $notify) {
                        $this->notifyReadFlag($notify->id, $uid, $type);
                    }
                    Console::stdout('End working on user ' . $uid . PHP_EOL);
                    unset($query);
                }

                Console::stdout('---- ' . PHP_EOL);
                $transaction->commit();
                $transaction = null;
                gc_collect_cycles();
            }
            if ($useSegmentation) {
                Console::stdout('---- OFFSET CRON ' . $offset . PHP_EOL);
                $this->setSegmentationOffset($type, $offset);
            }
        } catch (\Exception $e) {
            if (!is_null($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        } catch (\Throwable $e) {
            if (!is_null($transaction)) {
                $transaction->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @param $notificationconf
     * @param $classname
     * @return bool
     */
    public function skipContentNotifyConfig($notificationconf, $classname)
    {
        $skip = false;
        $modelsClassname = ModelsClassname::find()->andWhere(['classname' => $classname])->one();
        if ($modelsClassname) {
            $notifConfContent = $notificationconf->getNotificationConfContents()
                ->andWhere(['models_classname_id' => $modelsClassname->id])->one();
            if (!empty($notifConfContent)) {
                if ($notifConfContent->email == 0) {
                    Console::stdout('- X ' . "Disabled content " . $classname . PHP_EOL);
                    $skip = true;
                }
            }
        }
        return $skip;
    }

    protected function getSegmentationLogPath($type)
    {
        $nameLog = 'count_mail_' . $type . '.log';
        $path = \Yii::getAlias('@common/uploads') . '/' . $nameLog;
        return $path;
    }

    /**
     *
     * @param int $type
     * @return array
     */
    protected function getSegmentation($type)
    {
        $offset = 0;
        $limit = 0;
        $file = $this->getSegmentationLogPath($type);
        if (!empty($this->notifyModule) && $this->notifyModule->enableSegmentedSend == true) {
            if (in_array($type, $this->notifyModule->segmentationEnabledFor)) {
                if (!file_exists($file)) {
                    file_put_contents($file, 0);
                }
                $offset = file_get_contents($file);
                $limit = $this->notifyModule->segmentationOffset + $offset;
            }
        }
        return ['offset' => $offset, 'limit' => $limit];
    }

    /**
     *
     * @param int $type
     * @param int $offset
     */
    protected function setSegmentationOffset($type, $offset)
    {
        if (!empty($this->notifyModule) && $this->notifyModule->enableSegmentedSend == true && in_array($type,
                $this->notifyModule->segmentationEnabledFor)) {
            $path = $this->getSegmentationLogPath($type);
            file_put_contents($path, $offset);
        }
    }

    /**
     *
     * @param array $users
     * @param int $type
     * @return int
     */
    protected function getReturnSegmentation($users, $type, $limit)
    {
        if (self::$exitSegmentation) {
            $path = $this->getSegmentationLogPath($type);
            file_put_contents($path, 0);
            return 1;
        }
        if (empty(self::$countUsers[$type])) {
            self::$countUsers[$type] = $this->loadUser($type, null, 0, 0, true);
        }

//        Console::stdout('limit > count '.$limit.' '.self::$countUsers[$type].PHP_EOL);
        if ((empty($users) || count($users) == 0) && !empty($this->notifyModule) && in_array($type,
                $this->notifyModule->segmentationEnabledFor) && $limit > self::$countUsers[$type]) {
            $path = $this->getSegmentationLogPath($type);
            file_put_contents($path, 0);
            return 1;
        }
        return 0;
    }

    /**
     * Get single community name if $resultsNetwork contains only one community notification.
     * @param $resultsNetwork
     * @return string|null
     */
    protected function getCommunityNameInSenderEmail($resultsNetwork)
    {
        $notifications = $resultsNetwork;
        /** @var \open20\amos\community\AmosCommunity $communityModule */
        $communityModule = \Yii::$app->getModule('community');
        if ($communityModule) {
            $communityModelsClassname = ModelsClassname::findOne([
                'module' => $communityModule::getModuleName(),
                'table' => 'community',
            ]);
            if ($communityModelsClassname) {
                $modelsClassnameIds = [];
                $recordIds = [];
                /** @var Notification $notification */
                foreach ($notifications as $notification) {
                    $modelsClassnameIds[] = $notification->models_classname_id;
                    $recordIds[] = $notification->record_id;
                }
                if (!empty($modelsClassnameIds) && !empty($recordIds)) {
                    $uniqueModelsClassnameIds = array_unique($modelsClassnameIds);
                    $uniqueRecordIds = array_unique($recordIds);
                    if (count($uniqueModelsClassnameIds) == 1
                        && $uniqueModelsClassnameIds[0] == $communityModelsClassname->id
                        && count($uniqueRecordIds) == 1) {
                        $communityId = $uniqueRecordIds[0];
                        $community = Community::findOne($communityId);
                        if ($community && $community->name) {
                            return $community->name;
                        }
                    }
                }
            }
        }
        return null;
    }

}