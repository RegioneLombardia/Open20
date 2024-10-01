<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\base\builder
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\base\builder;

use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\emailmanager\AmosEmail;
use open20\amos\notificationmanager\base\Builder;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\notificationmanager\utility\NotifyUtility;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Console;

/**
 * Class AMailBuilder
 * @package open20\amos\notificationmanager\base\builder
 */
abstract class AMailBuilder extends BaseObject implements Builder
{
    protected $isCli = null;    // settata da logOn

    /**
     * @var AmosNotify $notifyModule
     */
    public $notifyModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->notifyModule = AmosNotify::instance();
        parent::init();
    }

    protected function logOn($msg) {

        if(is_null($this->isCli)) {
            $this->isCli = (php_sapi_name() == 'cli');
        }

        if($this->isCli) {
            Console::stdout($msg . PHP_EOL);
        } elseif (false) {
            print $msg.'<br />'."\n";
        }

    }

    /**
     * @param array $userIds
     * @param array $resultset
     * @param bool $checkContentPubblication
     * @return bool
     */
    public function sendEmail(array $userIds, array $resultset, $checkContentPubblication = true)
    {
        $allOk = true;
        try {
            foreach ($userIds as $id) {
                $user = User::findOne($id);
                if (!is_null($user) && $user->status == User::STATUS_ACTIVE) {
                    /** @var NotificationConf $notificationConfModel */
                    $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
                    $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $id])->one();
                    $contentNotificationEnabled = $notificationconf->notify_content_pubblication;
                    $this->setUserLanguage($id);

                    $subject = $this->getSubject($resultset);
                    $message = $this->renderEmail($resultset, $user);
                    $email = new Email();
                    $from = '';
                    if (isset(Yii::$app->params['email-assistenza'])) {
                        // Use default platform email assistance
                        $from = Yii::$app->params['email-assistenza'];
                    }

                    $ok = false;
                    if($contentNotificationEnabled || $checkContentPubblication == false){
                        $ok = $email->sendMail($from, $user->email, $subject, $message);
                    }

                    if (!$ok) {
                        Yii::getLogger()->log("Errore invio mail da '$from' a '$user->email'", \yii\log\Logger::LEVEL_ERROR);
                        $allOk = false;
                    }
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
            $allOk = false;
        }
        return $allOk;
    }
    
    /**
     * @param array $userIds
     * @param array $resultset
     * @param bool $checkContentPubblication
     * @return bool
     */
    public function sendEmailLegacy(array $userIds, array $resultset, $checkContentPubblication = true)
    {
        $allOk = true;
        try {
            foreach ($userIds as $id) {
                $user = User::findOne($id);
                if (!is_null($user)) {
                    /** @var NotificationConf $notificationConfModel */
                    $notificationConfModel = $this->notifyModule->createModel('NotificationConf');
                    $notificationconf = $notificationConfModel::find()->andWhere(['user_id' => $id])->one();
                    $contentNotificationEnabled = $notificationconf->notify_content_pubblication;
                    $this->setUserLanguage($id);
                    $subject = $this->getSubject($resultset);
                    $message = $this->renderEmailLegacy($resultset, $user);
                    $email = new Email();
                    $from = '';
                    if (isset(Yii::$app->params['email-assistenza'])) {
                        // Use default platform email assistance
                        $from = Yii::$app->params['email-assistenza'];
                    }
                    
                    $ok = false;
                    if($contentNotificationEnabled || $checkContentPubblication == false){
                        $ok = $email->sendMail($from, [$user->email], $subject, $message);
                    }
                    
                    if (!$ok) {
                        Yii::getLogger()->log("Errore invio mail da '$from' a '$user->email'", \yii\log\Logger::LEVEL_ERROR);
                        $allOk = false;
                    }
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
            $allOk = false;
        }
        return $allOk;
    }
    
    /**
     * @param array $userIds
     * @param array $resultset
     * @param array $resultsetNetwork
     * @param array $resultsetComments
     * @param string $communityNameInSenderEmail
     * @return bool
     */
        public function sendEmailMultipleSections($userIds, $resultset, $resultsetNetwork, $resultsetComments, $communityNameInSenderEmail = '')
    {
        $allOk = true;
        /** @var AmosNotify $notifyModule */
        $notifyModule = AmosNotify::instance();

        try {
            foreach ($userIds as $id) {
                $user = User::findOne($id);
                if (!is_null($user)) {

                    $this->setUserLanguage($id);
                    $subject = $this->getSubject($resultset);
                    $message = $this->renderEmailMultipleSections($resultset, $resultsetNetwork, $resultsetComments, $user);
                    $mailModule = Yii::$app->getModule("email");
                    $mailModule->defaultLayout = 'layout_summary_notify';

                    $email = new Email();

                    $from = '';
                    if (isset(Yii::$app->params['email-assistenza'])) {
                        // Use default platform email assistance
                        $from = Yii::$app->params['email-assistenza'];
                        if ($notifyModule->enableCommunityNameInSenderEmail && !empty($communityNameInSenderEmail)) {
                            $from = NotifyUtility::getFromEmailWithCommunityName($from, $communityNameInSenderEmail);
                        }
                    }

                    $ok = $email->sendMail($from, [$user->email], $subject, $message);

                    if (!$ok) {
                        Yii::getLogger()->log("Errore invio mail da '$from' a '$user->email'", \yii\log\Logger::LEVEL_ERROR);
                        $allOk = false;
                    }
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
            $allOk = false;
        }
        return $allOk;
    }

    /**
     * @param array $userIds
     * @param array $resultset
     * @return bool
     */
    public function sendEmailUserNotify($userIds, $resultset)
    {
        $allOk = true;
        $this->logOn('AMailBuilder - inizio ');

        try {
            foreach ($userIds as $id) {
                $user = User::findOne($id);
                if (!is_null($user)) {
                    $this->setUserLanguage($id);
                    $subject = $this->getSubject($resultset);

                    $message = $this->renderEmailUserNotify($resultset, $user);

                    $mailModule = Yii::$app->getModule("email");
                    $mailModule->defaultLayout = 'layout_summary_notify';

                    $email = new Email();

                    $from = '';
                    if (isset(Yii::$app->params['email-assistenza'])) {
                        // Use default platform email assistance
                        $from = Yii::$app->params['email-assistenza'];
                    }
                    $this->logOn('AMailBuilder - prima della mail ' . $user->email);
                    //$this->logOn('AMailBuilder - prima della mail ' . $user->email . PHP_EOL . $message);//die();
                    $ok = $email->sendMail($from, [$user->email], $subject, $message);
                    if (!$ok) {
                        //$this->logOn("Errore invio mail da '$from' a '$user->email'");
                        Yii::getLogger()->log("Errore invio mail da '$from' a '$user->email'", \yii\log\Logger::LEVEL_ERROR);
                        $allOk = false;
                    }
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
            $allOk = false;
        }
        return $allOk;
    }

    /**
     * @param int $userId
     */
    protected function setUserLanguage($userId)
    {
        $lang = 'it-IT';
        $module = \Yii::$app->getModule('translation');
        if ($module && !empty($module->enableUserLanguage) && $module->enableUserLanguage == true) {
            /** @var \open20\amos\translation\AmosTranslation $module */
            $lang = $module->getUserLanguage($userId);
            $module->setAppLanguage($lang);
        }
        return $lang;
    }
    
    /**
     * @param bool $module
     * @param bool $view
     * @param bool $params
     * @return string
     */
    public function renderView($module = false, $view = false, $params = false) {
        $mailManager = AmosEmail::getInstance() ?: new AmosEmail('email', Yii::$app);

        return $mailManager->render($module, $view, $params);
    }
}
