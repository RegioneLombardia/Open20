<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\utility
 * @category   utility
 */

namespace open20\amos\comments\utility;

use open20\amos\comments\AmosComments;
use open20\amos\comments\base\PartecipantsNotification;
use open20\amos\comments\models\base\CommentNotificationUsers;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\record\ContentModel;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\ArrayUtility;
use open20\amos\core\utilities\Email;
use open20\amos\myactivities\AmosMyActivities;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;

/**
 *
 */
class CommentsUtility
{

    public static function setCommentNotificationUser($contextClassName, $contextId, $userId, $enable)
    {
        $ret = false;
        try {
            $model = self::getCommentNotificationUser($contextClassName, $contextId, $userId);
            if (empty($model)) {
                $model = new CommentNotificationUsers();
                $model->user_id = $userId;
                $model->context_model_class_name = $contextClassName;
                $model->context_model_id = $contextId;
            }
            $model->enable = $enable;

            $ret = $model->save(false);
        } catch (\Exception $e) {
            return false;
        }
        return $ret;
    }

    /**
     * @param $contextClassName
     * @param $contextId
     * @param $userId
     * @return CommentNotificationUsers|false
     */
    public static function getCommentNotificationUser($contextClassName, $contextId, $userId)
    {
        try {
            $model = CommentNotificationUsers::findOne(
                [
                    'user_id' => $userId,
                    'context_model_class_name' => $contextClassName,
                    'context_model_id' => $contextId,
                ]
            );
            if (!empty($model)) {
                return $model;
            }
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }

    public static function getCommentNotificationUserStatus($contextClassName, $contextId, $userId)
    {
        $model = self::getCommentNotificationUser($contextClassName, $contextId, $userId);

        if (!empty($model)) {
            return $model->enable;
        }

        return null;
    }

    public static function getAllCommentNotificationUserEnabled($contextClassName, $contextId)
    {
        $cnuList = CommentNotificationUsers::find()
            ->andWhere(['context_model_class_name' => $contextClassName])
            ->andWhere(['context_model_id' => $contextId])
            ->andWhere(['enable' => true])
            ->all();

        return ArrayHelper::map($cnuList,'id', 'user_id');
    }


    public static function scannAllModelsForAdjustBells($write = false)
    {
        /** @var AmosComments $commentsModule */
        $commentsModule = AmosComments::instance();

        if (!empty($commentsModule->bellNotificationEnabledClasses) && is_array($commentsModule->bellNotificationEnabledClasses)){
            foreach ($commentsModule->bellNotificationEnabledClasses as $modelClassName){
                echo "<br>";
                echo "<hr>";
                echo "<h1>$modelClassName</h1>";

                $cont = 0;
                /** @var ActiveQuery $query */
                $query = Comment::find()
                    ->select(new Expression('id, context, context_id, created_by'))
                    ->andWhere(['context' => $modelClassName])
                    ->groupBy(['context', 'context_id', 'created_by']);

                /** @var Record $model */
                foreach ($query->asArray()->all() as $element) {
//                    VarDumper::dump($element, 3, true);

                    // Costruisco il model del contesto!
                    $contextModel = $element['context']::findOne(['id' => $element['context_id']]);
                    // potrebbe essere stato eliminato il contesto...
                    if (!empty($contextModel)) {
                        // se essite il contensto perchè non eliminato allora lo tratto!
                        $model = self::getCommentNotificationUser(
                            $contextModel::className(),
                            $contextModel->id,
                            $contextModel->created_by
                        );
                        if (empty($model)) {
                            $model = new \open20\amos\comments\models\base\CommentNotificationUsers();
                            $model->user_id = $contextModel->created_by;
                            $model->context_model_class_name =  $contextModel::className();
                            $model->context_model_id = $contextModel->id;
                            $model->enable = true;
                            if ($write) {
                                $model->save(false);
                            }
                            $cont++;
                        }

                        // poi i creatori i creatori dei commenti controllo le campanelle
                        $model = self::getCommentNotificationUser(
                            $element['context'],
                            $element['id'],
                            $element['created_by']
                        );
                        if (empty($model)) {
                            $model = new \open20\amos\comments\models\base\CommentNotificationUsers();
                            $model->user_id = $element['created_by'];
                            $model->context_model_class_name = $element['context'];
                            $model->context_model_id = $element['id'];
                            $model->enable = true;
                            if ($write) {
                                $model->save(false);
                            }
                            $cont++;
                        }

                        // tratto le risposte a questo commento
                        $queryReply = CommentReply::find()
                            ->select(new Expression('created_by'))
                            ->andWhere(['comment_id' => $element['id']])
                            ->groupBy(['created_by']);

                        foreach ($queryReply->asArray()->all() as $arrayRisp) {
                            // poi i creatori i creatori dei commenti controllo le campanelle
                            $modelRisp = self::getCommentNotificationUser(
                                $element['context'],
                                $element['id'],
                                $arrayRisp['created_by']
                            );
                            if (empty($modelRisp)) {
                                $modelRisp = new \open20\amos\comments\models\base\CommentNotificationUsers();
                                $modelRisp->user_id = $arrayRisp['created_by'];
                                $modelRisp->context_model_class_name = $element['context'];
                                $modelRisp->context_model_id = $element['id'];
                                $modelRisp->enable = true;
                                if ($write) {
                                    $modelRisp->save(false);
                                }
                                $cont++;
                            }
                        }

                    }

                }
                echo 'aggiunte: ' . $cont . ' campanelline';
            }
        }

    }

    /**
     * @param string $new_text
     * @param string $old_text
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTaggedUserProfileIdsFromComment($new_text = null, $old_text = null)
    {
        $new_tagging_user_id = [];

        // get all user_id from old text and all user_id from new text
        $user_id_new = (array)Record::getMentionUserIdFromText($new_text);
        $user_id_old = (array)Record::getMentionUserIdFromText($old_text);

        // extract only different id user between new user id and old user id
        $new_tagging_user_id = array_diff($user_id_new, $user_id_old);


        // filtro gli utenti per quegli che hanno abilitato le notifiche per la tagatura degli utenti nei commenti
        foreach ($new_tagging_user_id as $key => $user_profile_id) {

            $user_profile = \open20\amos\admin\models\UserProfile::find()->andWhere(['id' => $user_profile_id])->one();

            // remove all user_profile with notify_taggin_user_in_content
            if ($user_profile && ($user_profile->notify_tagging_user_in_content == 0)) {
                unset($new_tagging_user_id[$key]);
            }
        }

        return $new_tagging_user_id;
    }

    /**
     * Checks if the user is a comments' validator.
     * If the context model is a community content, the user must be a community manager
     * else if it's a platform content, the user must be a generic comments' validator.
     * @param $contextModel
     * @return bool
     */
    public static function userIsCommentsValidator($contextModel): bool
    {
        $isCommunityContent = false;
        if (method_exists($contextModel, 'isNetworkContent')) {
            $isCommunityContent = $contextModel->isNetworkContent('community');
        }

        if (!$isCommunityContent) {
            return \Yii::$app->user->can(self::commentsValidatorRole());
        } else {
            $communityId = self::getCommunityIdFromContextModel($contextModel);
            if (!empty($communityId)) {
                $community = Community::findOne($communityId);
                if (!empty($community)) {
                    return $community->isCommunityManager(\Yii::$app->user->id);
                }
            }
        }

        return false;
    }

    /**
     * Retrieve the community id from the context model.
     * @param $contextModel
     * @return string|null
     */
    public static function getCommunityIdFromContextModel($contextModel)
    {
        if ($contextModel instanceof ContentModel) {
            /** @var ContentModel $destinatari */
            $destinatari = $contextModel->destinatari;
            if (!empty($destinatari)) {
                foreach ($destinatari as $network) {
                    if (strpos($network, 'community-') !== false) {
                        return substr($network, strlen('community-'));
                    }
                }
            }
        }
        return null;
    }

    /**
     * Comments validator role
     * @return string
     */
    public static function commentsValidatorRole(): string
    {
        return 'COMMENTDASHBOARD_ADMINISTRATOR';
    }

    /**
     * @param bool $isCommunityContent
     * @param $contextModel
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public static function notifyMyActivities(bool $isCommunityContent, $contextModel)
    {
        if (!$isCommunityContent) {
            // Update "my activities" bullet count for comments validators
            $validatorsUserIds = \Yii::$app->authManager->getUserIdsByRole(CommentsUtility::commentsValidatorRole());
            foreach ($validatorsUserIds as $userId) {
                CommentsUtility::updateBulletCountMyActivities($userId);
            }
        } else {
            // Update "my activities" bullet count for community managers
            $communityId = CommentsUtility::getCommunityIdFromContextModel($contextModel);
            if (!empty($communityId)) {
                $communityManagers = CommunityUserMm::find()
                    ->andWhere(['community_id' => $communityId])
                    ->andWhere(['role' => \open20\amos\community\models\base\CommunityUserMm::ROLE_COMMUNITY_MANAGER])
                    ->all();
                foreach ($communityManagers as $communityManager) {
                    CommentsUtility::updateBulletCountMyActivities($communityManager->user_id);
                }
            }
        }
    }

    /**
     * Update bullet count for My Activities widget
     * @param int $userId
     * @return void
     */
    public static function updateBulletCountMyActivities(int $userId)
    {
        /** @var \open20\amos\myactivities\AmosMyActivities $moduleMyActivities */
        $moduleMyActivities = \Yii::$app->getModule('myactivities');
        if ($moduleMyActivities) {
            if (class_exists('open20\\amos\\utility\\drivers\\bcDriverMyActivities')) {
                \open20\amos\utility\drivers\bcDriverMyActivities::updateBulletCountersTable(
                    $userId,
                    $moduleMyActivities::getModuleName(),
                    'open20\amos\myactivities\widgets\icons\WidgetIconMyActivities',
                    1,
                    false
                );
            }
        }
    }

    /**
     * Send notification to participants for comment publication
     * @param Comment|CommentReply $comment
     * @return void
     */
    public static function notifyParticipants($comment)
    {
        $participantNotify = new PartecipantsNotification();
        $participantNotify->partecipantAlert($comment);
    }

    /**
     * Notify validation to comment creator
     * @param Comment|CommentReply $comment
     * @return void
     */
    public static function sendValidationNotification($comment)
    {
        $creatorName = $comment->createdUserProfile->nomeCognome;
        if ($comment instanceof Comment) {
            $contextModel = $comment->contextModel;
            $commentAnchor = '#comment-' . $comment->id;
            $commentText = $comment->comment_text;
        } else if ($comment instanceof CommentReply) {
            $contextModel = $comment->comment->contextModel;
            $commentAnchor = '#comment-reply-' . $comment->id;
            $commentText = $comment->comment_reply_text;
        }
        $contextModelName = $contextModel->getGrammar()->getModelSingularLabel();
        $contextModelTitle = $contextModel->getTitle();
        $viewUrl = $contextModel->getFullViewUrl();
        $url = \Yii::$app->urlManager->createAbsoluteUrl([$viewUrl . $commentAnchor]);
        $contextLink = Html::a($contextModelTitle, $url);

        if (!empty(\Yii::$app->params['email-assistenza'])) {
            $from = \Yii::$app->params['email-assistenza'];
        } else {
            $from = 'assistenza@open20.it';
        }
        $subject = AmosComments::t('amoscomments', 'Commento validato');
        $message = AmosComments::t('amoscomments', 'Ciao {creatorName},<br>il tuo commento alla {contextModelName} {contextModelTitle} è stato approvato:<br>{commentText}', [
            'creatorName' => $creatorName,
            'contextModelName' => $contextModelName,
            'contextModelTitle' => $contextLink,
            'commentText' => $commentText
        ]);
        $to = [$comment->createdUserProfile->user->email];

        $email = new Email();
        $email->sendMail($from, $to, $subject, $message);
    }

    /**
     * Notify rejection to comment creator
     * @param Comment|CommentReply $comment
     * @return void
     */
    public static function sendRejectionNotification($comment)
    {
        $creatorName = $comment->createdUserProfile->nomeCognome;
        if ($comment instanceof Comment) {
            $contextModel = $comment->contextModel;
            $commentAnchor = '#comment-' . $comment->id;
            $commentText = $comment->comment_text;
        } else if ($comment instanceof CommentReply) {
            $contextModel = $comment->comment->contextModel;
            $commentAnchor = '#comment-reply-' . $comment->id;
            $commentText = $comment->comment_reply_text;
        }
        $contextModelName = $contextModel->getGrammar()->getModelSingularLabel();
        $contextModelTitle = $contextModel->getTitle();
        $viewUrl = $contextModel->getFullViewUrl();
        $url = \Yii::$app->urlManager->createAbsoluteUrl([$viewUrl . $commentAnchor]);
        $contextLink = Html::a($contextModelTitle, $url);

        if (!empty(\Yii::$app->params['email-assistenza'])) {
            $from = \Yii::$app->params['email-assistenza'];
        } else {
            $from = 'assistenza@open20.it';
        }
        $subject = AmosComments::t('amoscomments', 'Commento rifiutato');
        $message = AmosComments::t('amoscomments', 'Ciao {creatorName},<br>il tuo commento alla {contextModelName} {contextModelTitle} è stato rifiutato in quanto non rispetta i criteri di moderazione di piattaforma:<br>{commentText}<br>Per ulteriori informazioni contattare l\'assistenza.', [
            'creatorName' => $creatorName,
            'contextModelName' => $contextModelName,
            'contextModelTitle' => $contextLink,
            'commentText' => $commentText
        ]);
        $to = [$comment->createdUserProfile->user->email];

        $email = new Email();
        $email->sendMail($from, $to, $subject, $message);
    }

    /**
     * @return string
     */
    public static function getModerationInvolvedPlugins(): string
    {
        $plugins = [];
        $modelsEnabled = AmosComments::instance()->moderationModelsEnabled;
        foreach ($modelsEnabled as $modelEnabled) {
            $model = $modelEnabled::instance();
            $moduleName = ucfirst($model->getModelModuleName());
            $plugins[] = $moduleName;
        }
        if (!empty($plugins)) {
            if (count($plugins) > 1) {
                $plugins = implode(', ', $plugins);
            } else {
                $plugins = $plugins[0];
            }
        } else {
            $plugins = AmosComments::t('amoscomments', 'nessuno');
        }

        return $plugins;
    }

    /**
     * @return string
     */
    public static function getBlacklistInvolvedPlugins(): string
    {
        $plugins = [];
        $modelsEnabled = AmosComments::instance()->blacklistModelsEnabled;
        foreach ($modelsEnabled as $modelEnabled) {
            $model = $modelEnabled::instance();
            $moduleName = ucfirst($model->getModelModuleName());
            $plugins[] = $moduleName;
        }
        if (!empty($plugins)) {
            if (count($plugins) > 1) {
                $plugins = implode(', ', $plugins);
            } else {
                $plugins = $plugins[0];
            }
        } else {
            $plugins = AmosComments::t('amoscomments', 'nessuno');
        }

        return $plugins;
    }

    /**
     * @param string $contextModelClassname
     * @param int $contextModelId
     * @return void
     */
    public static function saveCommentNotificationUser(string $contextModelClassname, int $contextModelId)
    {
        $model = CommentsUtility::getCommentNotificationUser($contextModelClassname, $contextModelId, Yii::$app->user->id);
        if (!empty($model)) {
            /** @var AmosComments $moduleComments */
            $moduleComments = AmosComments::instance();
            $forceEnable = $moduleComments->forceEnableCommentNotificationUserOnCreate;
            if ($forceEnable && !$model->enable) {
                $model->enable = true;
                $model->save(false);
            }
        } else {
            $model = new CommentNotificationUsers();
            $model->user_id = Yii::$app->user->id;
            $model->context_model_class_name = $contextModelClassname;
            $model->context_model_id = $contextModelId;
            $model->enable = true;
            $model->save(false);
        }
    }

    /**
     * @return array
     */
    public static function getTitleNotificationBell(): array
    {
        return [
            'enabled' => AmosComments::t('amoscomments', 'Notifiche attivate per questo contenuto. Clicca per disattivarle'),
            'disabled' => AmosComments::t('amoscomments', 'Notifiche disattivate per questo contenuto. Clicca per attivarle')
        ];
    }
}