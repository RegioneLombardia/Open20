<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\base
 * @category   CategoryName
 */

namespace open20\amos\comments\base;

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\utility\CommentsUtility;
use open20\amos\community\models\Community;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\notificationmanager\utility\NotifyUtility;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use open20\amos\comments\models\CommentNotificationUsers;
use open20\amos\admin\models\UserProfile;
use yii\helpers\VarDumper;

/**
 * Class PartecipantsNotification
 * @package open20\amos\comments\base
 */
class PartecipantsNotification extends BaseObject
{
    /**
     * @var AmosComments|null $commentsModule
     */
    protected $commentsModule = null;

    /**
     * @var CrudController $appController
     */
    protected $appController = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->commentsModule = Yii::$app->getModule(AmosComments::getModuleName());
        $this->appController  = \Yii::$app->controller;
    }

    /**
     * This method sends the mail notifications to the correct recipients.
     * @param $comment
     */
    public function partecipantAlert($comment)
    {
        $model_reply = null;

        if (!empty($this->commentsModule)) {
            if (!$this->commentsModule->enableMailsNotification) {
                return;
            }
        }

        $model = $comment;
        if ($comment instanceof CommentReply) {
            $model       = $comment->comment;
            $model_reply = $comment;
        }

        /** @var \open20\amos\core\record\Record $contextModelClassName */
        $contextModelClassName = $model->context;
        /** @var \open20\amos\core\record\Record $contextModel */
        $contextModel          = $contextModelClassName::findOne($model->context_id);

        $users = $this->getRecipients($contextModel, $contextModelClassName);

        $this->sendEmail($users, $contextModel, $model, $model_reply);
    }

    /**
     * This method sends the mail notifications of the chat
     * @param $userIds
     * @param Comment $comment
     */
    public function partecipantChatAlert($userIds, Comment $comment)
    {
//        /** @var \open20\amos\core\record\Record $contextModelClassName */
//        $contextModelClassName = $comment->context;
//        /** @var \open20\amos\core\record\Record $contextModel */
//        $contextModel          = $contextModelClassName::findOne($comment->context_id);
//        $this->sendEmail($userIds, $contextModel, $comment);

        if (!empty($userIds)) {
            foreach ($userIds as $userId) {
                /** @var User $user */
                $user = User::findOne($userId);
                if ($user->userProfile->isActive()) {
                    /** @var Community $communityModel */
                    $communityModel = $comment->context;
                    $communityId = $comment->context_id;
                    $community = $communityModel::findOne($communityId);
                    $from = \Yii::$app->params['email-assistenza'] ?? 'assistenza@open20.it';
                    $subject = \Yii::t('amoscomments', '#chat_message_subject', [
                        'communityName' => $community->name,
                    ]);
                    $message = \Yii::t('amoscomments', '#chat_message_mail_content', [
                        'communityUrl' => Yii::$app->urlManager->createAbsoluteUrl([
                            '/' . $community->getFullViewUrl()
                        ]),
                        'communityName' => $community->name,
                    ]);
                    $email = new Email();
                    $email->sendMail($from, [$user->email], $subject, $message);
                }
            }
        }
    }

    /**
     * Method to exclude users when notification taggin user in content is disabled
     * notify_tagging_user_in_content = 0
     *
     * @param array | custom type list | $users
     * @param model | open20\amos\comments\models\base\Comment | $comment
     *
     * @return array | custom type list | $users
     */
    private function extractTagginNotificationUsers($users, $comment){

        // get all taggin users from comment text
        $users_taggin_id = Record::getMentionUserIdFromText($comment->comment_text);

        // extract all user where notify_tagging_user_in_content is disabled and is mention in text
        $users_id = ArrayHelper::getColumn(
                UserProfile::find()
                    ->select('user_id')
                    ->andWhere(["notify_tagging_user_in_content" => 1])
                    ->andWhere(["id" => $users_taggin_id])
                    ->asArray()
                    ->all(),

            function ($element) {
                return $element['user_id'];
            }
        );

        return $users_id;
    }

    /**
     * This method returns an array that contains the ids of the users to be notified.
     * @param Record $contextModel
     * @param string $contextModelClassName
     * @return array
     */
    protected function getRecipients($contextModel, $contextModelClassName)
    {
        if (in_array($contextModelClassName, AmosComments::instance()->bellNotificationEnabledClasses)) {

            // if exist override of getRecipients on $contextModel use this method!
            if (method_exists($contextModel, 'getRecipients')) {
                $users = $contextModel->getRecipients();
            } else {
                $users = CommentsUtility::getAllCommentNotificationUserEnabled($contextModelClassName, $contextModel->id);
            }

        } else {
            $users = $this->getDiscussionsRecipients($contextModel);
        }

        if (empty($users)) {
            if ($contextModel->hasMethod('getRecipients')) {
                $users = $contextModel->getRecipients();
            } else {
                $users = $this->getDefaultRecipients($contextModel, $contextModelClassName);
            }
        }

        return $users;
    }

    /**
     * This method returns an array that contains the ids of the discussions users to be notified.
     * @param Record $contextModel
     * @return array
     */
    protected function getDiscussionsRecipients($contextModel)
    {
        $users = [];

        // If the context is discussion, the emails must be sent to the participants in the scope.
        if (Yii::$app->hasModule('discussioni') && ($contextModel instanceof \open20\amos\discussioni\models\DiscussioniTopic)) {
            /** @var \open20\amos\discussioni\AmosDiscussioni $moduleDiscussioni */
            $moduleDiscussioni = Yii::$app->getModule('discussioni');
            $session           = \Yii::$app->session;
            $moduleCwh         = \Yii::$app->getModule('cwh');
            $scope             = $moduleCwh->getCwhScope();

            if (!empty(\Yii::$app->params['isPoi']) && \Yii::$app->params['isPoi'] == true && !empty($scope) && !empty($scope['community'])
                && $scope['community'] == 2750) {
                $communityManager = \open20\amos\community\models\CommunityUserMm::find()->andWhere(['community_id' => 2750])->andWhere([
                    'role' => \open20\amos\community\models\Community::ROLE_COMMUNITY_MANAGER]);
                foreach ($communityManager->all() as $idComm) {
                    $user = User::findOne($idComm->user_id);
                    if (!is_null($user)) {
                        $users[] = $user->id;
                    }
                }
            } else {
                if ($moduleDiscussioni->hasProperty('notifyOnlyContributors') && !$moduleDiscussioni->notifyOnlyContributors) {
                    $moduleCwh = Yii::$app->getModule('cwh');
                    if (!is_null($moduleCwh)) {
                        /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
                        if (in_array(\open20\amos\discussioni\models\DiscussioniTopic::className(),
                                $moduleCwh->modelsEnabled)) {
                            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(\open20\amos\discussioni\models\DiscussioniTopic::className());
                            if (in_array($contextModel->regola_pubblicazione,
                                    \open20\amos\cwh\utility\CwhUtil::getNetworkCwhRuleIds())) {
                                $queryUsers = $cwhActiveQuery->getRecipients($contextModel->regola_pubblicazione,
                                    $contextModel->tagValues, $contextModel->destinatari);
                                $queryUsers->andWhere(['<>', \open20\amos\core\user\User::tableName().'.id', Yii::$app->user->id]); // Exclude logged user id
                                $users      = ArrayHelper::map($queryUsers->all(), 'id', 'id');
                            }
                        }
                    }
                }
            }
        }

        return $users;
    }

    /**
     * This method returns an array that contains the ids of the default users to be notified.
     * @param Record $contextModel
     * @param string $contextModelClassName
     * @return array
     */
    protected function getDefaultRecipients($contextModel, $contextModelClassName)
    {
        $users        = [$contextModel->created_by => $contextModel->created_by];
        $loggedUserId = Yii::$app->user->id;

        $comments = Comment::find()
            ->andWhere(['context_id' => $contextModel->id])
            ->andWhere(['context' => $contextModelClassName])
            ->andWhere(['<>', 'created_by', $loggedUserId]) // Exclude logged user id
            ->groupBy(['created_by'])
            ->all();

        foreach ($comments as $comment) {
            $users[$comment->created_by] = $comment->created_by;
            $commentReplies              = CommentReply::find()
                ->andWhere(['comment_id' => $comment->id])
                ->andWhere(['<>', 'created_by', $loggedUserId]) // Exclude logged user id
                ->groupBy(['created_by'])
                ->all();
            foreach ($commentReplies as $reply) {
                $users[$reply->created_by] = $reply->created_by;
            }
        }

        return $users;
    }

    /**
     * @param array $userIds
     * @param $model
     */
    private function sendEmail(array $userIds, $contextModel, $model, $model_reply = null)
    {
        try {
            $moduleNotify = \Yii::$app->getModule('notify');

            foreach ($userIds as $id) {
                $notifyComment = true;
                $user          = User::findOne($id);

                if (!empty($moduleNotify)) {
                    /** @var  $notificationConf  \open20\amos\notificationmanager\models\NotificationConf */
                    $notificationConf = \open20\amos\notificationmanager\models\NotificationConf::find()
                        ->andWhere(['user_id' => $id])
                        ->one();
                    if ($notificationConf->hasProperty('notifications_enabled') && ($notificationConf->notify_comments == 0
                        || $notificationConf->notifications_enabled == 0)) {
                        $notifyComment = false;
                    }
                }

                // If the user has disabled the notification for this content, the email is not sent.
                $commentNotificationUsers = CommentNotificationUsers::findOne([
                    'user_id' => $id,
                    'context_model_class_name' => $contextModel->className(),
                    'context_model_id' => $contextModel->id
                ]);
                if (!empty($commentNotificationUsers)) {
                    if (!$commentNotificationUsers->enable) {
                        $notifyComment = false;
                    }
                }
                if ($notifyComment) {

                    $subject = $this->getSubject($contextModel);
                    $message = $this->renderEmail($contextModel, $model, $model_reply, $user);
                    if (!is_null($user)) {
                        if ($user->userProfile->isActive()) {
                            $email = new Email();
                            $from  = '';
                            if (isset(Yii::$app->params['email-assistenza'])) {
                                //use default platform email assistance
                                $from = Yii::$app->params['email-assistenza'];
								if(AmosComments::instance()->modelsCustomMail[$contextModel->className()]){
									$from = AmosComments::instance()->modelsCustomMail[$contextModel->className()];
								}
                                if (method_exists($contextModel, 'isNetworkContent')) {
                                    if ($contextModel->isNetworkContent('community')) {
                                        /** @var \open20\amos\notificationmanager\AmosNotify $notifyModule */
                                        $notifyModule = \Yii::$app->getModule('notify');
                                        if ($notifyModule && isset($notifyModule->enableCommunityNameInSenderEmail) && $notifyModule->enableCommunityNameInSenderEmail) {
                                            $communityId = CommentsUtility::getCommunityIdFromContextModel($contextModel);
                                            if ($communityId) {
                                                $communityName = Community::findOne($communityId)->name;
                                                $from = NotifyUtility::getFromEmailWithCommunityName($from, $communityName);
                                            }
                                        }
                                    }
                                }
                            }
                            $email->sendMail($from, [$user->email], $subject, $message);
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

    /**
     * @param Record $contextModel
     * @return string
     */
    private function getSubject(Record $contextModel)
    {
        $content_subject = 'email'.DIRECTORY_SEPARATOR.'content_subject';

        if(method_exists($contextModel, 'subjectComment')){
            $content_subject = $contextModel->subjectComment();
        } else {
            if ($this->commentsModule) {
                if (is_array($this->commentsModule->htmlMailContentSubject)) {
                    $contextModelClassName = $contextModel->className();
                    if (!empty($this->commentsModule->htmlMailContentSubject[$contextModelClassName])) {
                        $content_subject = $this->commentsModule->htmlMailContentSubject[$contextModelClassName];
                    } else {
                        $content_subject = $this->commentsModule->htmlMailContentSubjectDefault;
                    }
                } else {
                    $content_subject = $this->commentsModule->htmlMailContentSubject;
                }
            }
        }

        $subject = $this->appController->renderMailPartial($content_subject, ['contextModel' => $contextModel]);

        return $subject;
    }

    /**
     * @param Record $contextModel
     * @param Comment $model
     * @param CommentReply $model_reply
     * @param User|null $user
     * @return string
     */
    private function renderEmail($contextModel, $model, $model_reply, $user = null)
    {
        $mail = '';
        try {
            if ($model != null) {
                if(method_exists($contextModel, 'contentComment')){
                    $content = $contextModel->contentComment();
                    $mail = $this->appController->renderMailPartial($content,
                        [
                            'model' => $model,
                            'contextModel' => $contextModel,
                            'model_reply' => $model_reply,
                            'user' => $user
                        ]);

                }else {

                    $mail .= $this->renderContentTitle($contextModel, $model, $model_reply);
                    $mail .= $this->renderContent($contextModel, $model, $model_reply, $user);
                }
            }
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }
        return $mail;
    }

    /**
     * @param Record|ModelLabelsInterface $model
     * @param Comment $modelComment
     * @param CommentReply $model_reply
     * @return string
     */
    private function renderContentTitle(ModelLabelsInterface $model, $modelComment, $model_reply)
    {
        $content = 'email'.DIRECTORY_SEPARATOR.'content_title';

        if ($this->commentsModule) {
            if (is_array($this->commentsModule->htmlMailContentTitle)) {
                $contextModelClassName = $model->className();
                if (!empty($this->commentsModule->htmlMailContentTitle[$contextModelClassName])) {
                    $content = $this->commentsModule->htmlMailContentTitle[$contextModelClassName];
                } else {
                    $content = $this->commentsModule->htmlMailContentTitleDefault;
                }
            } else {
                $content = $this->commentsModule->htmlMailContentTitle;
            }
        }

        $ris = $this->appController->renderMailPartial($content,
            [
            'model' => $model,
            'modelComment' => $modelComment,
            'model_reply' => $model_reply
        ]);

        return $ris;
    }

    /**
     * @param Record $contextModel
     * @param Record $model
     * @param CommentReply $model_reply
     * @param User|null $user
     * @return mixed
     */
    private function renderContent(Record $contextModel, Record $model, $model_reply, $user = null)
    {
        $content = 'email'.DIRECTORY_SEPARATOR.'content';

        if ($this->commentsModule) {
            if (is_array($this->commentsModule->htmlMailContent)) {
                $contextModelClassName = $contextModel->className();
                if (!empty($this->commentsModule->htmlMailContent[$contextModelClassName])) {
                    $content = $this->commentsModule->htmlMailContent[$contextModelClassName];
                } else {
                    $content = $this->commentsModule->htmlMailContentDefault;
                }
            } else {
                $content = $this->commentsModule->htmlMailContent;
            }
        }

        $ris = $this->appController->renderMailPartial($content,
            [
            'model' => $model,
            'contextModel' => $contextModel,
            'model_reply' => $model_reply,
            'user' => $user
        ]);

        return $ris;
    }
}