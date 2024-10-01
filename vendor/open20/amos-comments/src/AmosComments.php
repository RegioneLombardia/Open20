<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments
 * @category   CategoryName
 */

namespace open20\amos\comments;

use open20\amos\comments\base\PartecipantsNotification;
use open20\amos\comments\components\CommentComponent;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentDashboard;
use open20\amos\comments\models\CommentModeration;
use open20\amos\comments\models\CommentReply;
use open20\amos\core\components\AmosView;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\record\Record;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

/**
 * Class AmosComments
 * @package open20\amos\comments
 */
class AmosComments extends AmosModule implements ModuleInterface, BootstrapInterface
{
    public static $CONFIG_FOLDER = 'config';

    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\comments\controllers';
    public $newFileMode         = 0666;
    public $name                = 'Comments';
    public $layoutInverted      = false;
    

    /**
     * @var array $modelsEnabled
     */
    public $modelsEnabled           = [];
    public $modelsCustomMail           = [];
    public $modelsConfiguration     = [];
    public $maxCommentAttachments   = 5;
    public $enableMailsNotification = true;

    /**
     * @var bool $enableUserSendMailCheckbox If true enable the checkbox in the comments forms with the user can select if send or not the notify mail.
     */
    public $enableUserSendMailCheckbox = true;
    
    /**
     * @var bool $socialShare If true enable share on social of the comments.
     */
    public $socialShare         = true;

    /**
     * This is the html used to render the subject of the e-mail. In the view is available the variable $profile
     * that is an instance of 'open20\amos\admin\models\UserProfile'
     * @var string
     */
    public $htmlMailContentSubject = '@vendor/open20/amos-comments/src/views/comment/email/content_subject';
    //    public $htmlMailContentTitle = [
//        'open20\amos\news\models\News' => '@vendor/open20/amos-comments/src/views/comment/email/content_subject_news',
//        'open20\amos\discussioni\models\DiscussioniTopic' => '@vendor/open20/amos-comments/src/views/comment/email/content_subject_discussioni',
//        'open20\amos\documenti\models\Documenti' => '@vendor/open20/amos-comments/src/views/comment/email/content_subjcet_documenti'
//    ];

    public $htmlMailContentSubjectDefault = '@vendor/open20/amos-comments/src/views/comment/email/content_subject';

    /**
     * This is the html used to render the subject of the e-mail. In the view is available the variable $profile
     * that is an instance of 'open20\amos\admin\models\UserProfile'
     * @var string
     */
    public $htmlMailContentTitle = '@vendor/open20/amos-comments/src/views/comment/email/content_title';
    //    public $htmlMailContentTitle = [
//        'open20\amos\news\models\News' => '@vendor/open20/amos-comments/src/views/comment/email/content_title_news',
//        'open20\amos\discussioni\models\DiscussioniTopic' => '@vendor/open20/amos-comments/src/views/comment/email/content_title_discussioni',
//        'open20\amos\documenti\models\Documenti' => '@vendor/open20/amos-comments/src/views/comment/email/content_title_documenti'
//    ];

    /*
     * 
     */
    public $htmlMailContentTitleDefault = '@vendor/open20/amos-comments/src/views/comment/email/content_title';

    /**
     * This is the html used to render the message of the e-mail. In the view is available the variable $profile
     * that is an instance of 'open20\amos\admin\models\UserProfile'
     * @var string|array
     */
    public $htmlMailContent = '@vendor/open20/amos-comments/src/views/comment/email/content';
//    public $htmlMailContent = [
//        'open20\amos\news\models\News' => '@vendor/open20/amos-comments/src/views/comment/email/content_news',
//        'open20\amos\discussioni\models\DiscussioniTopic' => '@vendor/open20/amos-comments/src/views/comment/email/content_discussioni',
//        'open20\amos\documenti\models\Documenti' => '@vendor/open20/amos-comments/src/views/comment/email/content_documenti'
//    ];

    /*
     * 
     */
    public $htmlMailContentDefault = '@vendor/open20/amos-comments/src/views/comment/email/content';

    /**
     * Sets if the notify checkbox must be visible into the comments accordion
     * @var bool
     */
    public $displayNotifyCheckbox = true;

    /**
     * Sets if the comments accordion must be opened by default
     * @var bool
     */
    public $accordionOpenedByDefault = true;

    /**
     * If true it enable the comments olny with the scope (in the community)
     * @var boolean $enableCommentOnlyWithScope
     */
    public $enableCommentOnlyWithScope = false;
    public $disableAutoDisplay         = ['amos\planner\models\PlanWork'];

    /**
     * If a true notify the context model (DiscussioniTopic) if is created a comment
     * @var bool $enableNotifyCommentForDiscussions
     */
    public $enableNotifyCommentForDiscussions = true;

    /**
     *
     * @var type 3 by SORT_DESC, 4 by SORT_ASC
     */
    public $orderDisplayComments = 3;

    /**
     *
     * @var type
     */
    public $disablePagination     = false;

    /**
     * Number of comments showed per page
     * @var int $paginationPageSize
     */
    public $paginationPageSize = 5;

    /**
     *
     * @var bool $enableModerator
     */
    public $enableModerator     = false;

    /**
     * @var bool
     */
    public $enableAttachmentInComment = true;


    private static $registerEvent = false;

    /**
     *
     * @var type
     */
    public $enableCanDoIt = true;

    /**
     * @var bool *
     */
    public $enableCommentInEvidence = false;

    /**
     *  Example:
     * [
     *  'open20\amos\documenti\models\Documenti',
     *  'open20\amos\news\models\News',
     *  'open20\amos\discussioni\models\Discussioni',
     * ]
     *
     * @var boolean
     */
    public $bellNotificationEnabledClasses = [
        'open20\amos\community\models\Community'
    ];

    /**
     * If notification bell is enabled by $bellNotificationEnabledClasses configuration,
     * deactivate the bell icon in comments section (default) and activate it in the view content.
     * @var bool $showBellIconInContentView
     */
    public $showBellIconInContentView = false;

    /**
     * If user has the option to enable/disable notifications for comments, when a comment is created
     * and the notification was disabled, re-enable the notification for the user.
     * @var bool $forceEnableCommentNotificationUserOnCreate
     */
    public $forceEnableCommentNotificationUserOnCreate = false;
    
    /**
     * @var string List of the allowed extensions for the upload of files.
     */
    public $whiteListFilesExtensions = ['csv', 'doc', 'docx', 'pdf', 'rtf', 'txt', 'xls', 'xlsx', 'odt'];

    /**
     * @var string List of the allowed mime types.
     */
    public $mimeTypes = '';

    /**
     * Option to disable the render of 'banner-cta' box for guest in CommentsWidget.
     * Enabled by default.
     * @var bool
     */
    public $disableBannerCtaForGuest = false;

    /**
     * Enable scan before sending a comment, if the comment contains a word in the blacklist,
     * the comment is not saved and shows an error message to the user.
     * It's applied for general comments and chat comments.
     * @var bool $enableBlacklistWordsFilter
     */
    public $enableBlacklistWordsFilter = false;

    /**
     * Enable blacklist for the models in the list.
     * @var string[] $blacklistModelsEnabled
     */
    public $blacklistModelsEnabled = [
        'open20\amos\news\models\News',
        'open20\amos\discussioni\models\DiscussioniTopic',
    ];

    /**
     * Enable comments moderation.
     * @var bool $enableModeration
     */
    public $enableModeration = false;

    /**
     * Enable comments moderation for the models in the list.
     * @var string[] $moderationModelsEnabled
     */
    public $moderationModelsEnabled = [
        'open20\amos\news\models\News',
        'open20\amos\discussioni\models\DiscussioniTopic',
    ];

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        \Yii::setAlias('@open20/amos/'.static::getModuleName().'/controllers', __DIR__.'/controllers/');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php'));
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return NULL;
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (self::$registerEvent == false) {
            self::$registerEvent = true;
            Event::on(AmosView::className(), AmosView::AFTER_RENDER_CONTENT, [new CommentComponent(), 'showComments']);
        }

        if (!empty($this->bellNotificationEnabledClasses) && is_array($this->bellNotificationEnabledClasses)){
            foreach ($this->bellNotificationEnabledClasses as $modelClassName){
                Event::on($modelClassName, Record::EVENT_AFTER_INSERT, [new CommentComponent(), 'addCreatorEnableNotificationUser']);
            }
        }

    }

    /**
     * @param $model
     */
    public function countComments($model)
    {
        $query = Comment::find()
            ->joinWith('commentReplies', true, 'LEFT JOIN')
            ->andWhere(['context' => $model->className(), 'context_id' => $model->id])
            ->groupBy('comment.id');

        /** @var \open20\amos\comments\models\Comment $lastComment */
        $countComment = $query->count();
        $query        = Comment::find()
            ->joinWith('commentReplies', true, 'LEFT JOIN')
            ->andWhere(['context' => $model->className(), 'context_id' => $model->id])
            ->andWhere(['is not', CommentReply::tableName().'.id', null]);
        $countComment += $query->count();
        return $countComment;
    }

    /**
     * Configurazione per-modulo di varie opzioni
     * @param $classname
     * @param $action
     * @return bool|mixed
     */
    public function modelCanDoIt($classname, $action) {
        if(empty($classname) || empty($action)) {
            return false;
        }

        if(array_key_exists($classname, $this->modelsConfiguration)) {
            $config = $this->modelsConfiguration[$classname];

            if(array_key_exists($action, $config)) {
                return $config[$action];
            }
        }

        return true;
    }

    /**
     * Checks if moderation is enabled for the model.
     * @param $model
     * @return bool
     * @throws InvalidConfigException
     */
    public function checkModerationStatus($model): bool
    {
        if ($this->enableModeration && in_array(get_class($model), $this->moderationModelsEnabled)) {
            $isCommunityContent = false;
            if (method_exists($model, 'isNetworkContent')) {
                $isCommunityContent = $model->isNetworkContent('community');
            }
            $commentDashboard = CommentDashboard::find()->one();
            if (!$isCommunityContent) {
                // Platform contents
                if ($commentDashboard && $commentDashboard->moderation_platform) {
                    return true;
                }
            } else {
                // Community contents
                if ($commentDashboard && $commentDashboard->moderation_communities) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $modelClassname
     * @param $contentId
     * @param $value
     * @return void
     */
    public function saveCommentModeration($modelClassname, $contentId, $value = false)
    {
        $commentModeration = CommentModeration::findOne(['classname' => $modelClassname, 'content_id' => $contentId]);

        if (!$commentModeration) {
            $commentModeration = new CommentModeration();
            $commentModeration->classname = $modelClassname;
            $commentModeration->content_id = $contentId;
            $commentModeration->moderation = $value;
            $commentModeration->save(false);
        } else {
            $commentModeration->moderation = $value;
            $commentModeration->save(false);

            if ($value == false) {
                // Publish all comments of the content
                $comments = Comment::find()->andWhere([
                    'context' => $modelClassname,
                    'context_id' => $contentId,
                    'status' => Comment::STATUS_TOVALIDATE
                ])->all();
                $partecipantsnotify = new PartecipantsNotification();
                foreach ($comments as $comment) {
                    $comment->status = Comment::STATUS_PUBLISHED;
                    if ($comment->save(false)) {
                        $partecipantsnotify->partecipantAlert($comment);
                    }
                    // Publish all replies of the content
                    $commentReplies = CommentReply::find()->andWhere([
                        'comment_id' => $comment->id,
                        'status' => Comment::STATUS_TOVALIDATE
                    ])->all();
                    foreach ($commentReplies as $commentReply) {
                        $commentReply->status = Comment::STATUS_PUBLISHED;
                        if ($commentReply->save(false)) {
                            $partecipantsnotify->partecipantAlert($commentReply);
                        }
                    }
                }
            }
        }
    }

    /**
     * Return the moderation value of the context model
     * @param string $modelClassname
     * @param integer $modelId
     * @return bool
     */
    public function getContextModerationValue(string $modelClassname, int $modelId): bool
    {
        $moderationValue = false;

        $commentModeration = CommentModeration::findOne([
            'classname' => $modelClassname,
            'content_id' => $modelId
        ]);
        if ($commentModeration && $commentModeration->moderation) {
            $moderationValue = true;
        }

        return $moderationValue;
    }

}