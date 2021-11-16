<?php
/**
 * Lombardia Informatica S.p.A.
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
use open20\amos\core\controllers\CrudController;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

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
     * This method returns an array that contains the ids of the users to be notified.
     * @param Record $contextModel
     * @param string $contextModelClassName
     * @return array
     */
    protected function getRecipients($contextModel, $contextModelClassName)
    {
        $users = $this->getDiscussionsRecipients($contextModel);

        if (empty($users) && method_exists($contextModel, 'getRecipients')) {
            $users = $contextModel->getRecipients();
        }

        if (empty($users)) {
            $users = $this->getDefaultRecipients($contextModel, $contextModelClassName);
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
                $mail .= $this->renderContentTitle($contextModel, $model, $model_reply);
                $mail .= $this->renderContent($contextModel, $model, $model_reply, $user);
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