<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\widgets\views\comments-widget
 * @category   CategoryName
 */

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsTable;
use open20\amos\comments\AmosComments;
use open20\amos\comments\assets\CommentsAsset;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentDashboard;
use open20\amos\comments\models\CommentModeration;
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\utility\CommentsUtility;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\AmosLinkPager;
use yii\web\View;
use yii\widgets\Pjax;
use open20\amos\news\models\News;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Url;

$asset = CommentsAsset::register($this);

/**
 * @var \open20\amos\comments\widgets\CommentsWidget $widget
 * @var \open20\amos\comments\models\Comment[] $comments
 * @var CommentReply[] $commentReplies
 * @var \yii\data\Pagination $pages
 * @var boolean|null $notificationUserStatus
 * @var \open20\amos\core\record\Record $model
 */
$js = <<<JS
    $('#comments_anchor').on('click', '.reply-to-comment', function (event) {
        event.preventDefault();
        Comments.reply($(this).data('comment_id'));
    }).on('click', '.comment-reply-btn-class', function (event) {
        event.preventDefault();
        if (typeof tinymce != 'undefined') {
            tinymce.triggerSave();
        }
        Comments.saveCommentReply($(this).data('comment_id'));
    });
JS;
$this->registerJs($js, View::POS_READY);

$class = $widget->model->className();

/** @var AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule(AmosComments::getModuleName());

ModalUtility::createAlertModal([
    'id' => 'ajax-error-comment-reply-modal-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', '#AJAX_ERROR_COMMENT_REPLY')
]);
ModalUtility::createAlertModal([
    'id' => 'empty-comment-reply-modal-id',
    'modalDescriptionText' => AmosComments::t('amoscomments', '#EMPTY_COMMENT_REPLY')
]);

$displayNotifyCheckBox = true;
$contextObject         = null;
if (isset($commentsModule->displayNotifyCheckbox)) {
    if (is_bool($commentsModule->displayNotifyCheckbox)) {
        $displayNotifyCheckBox = $commentsModule->displayNotifyCheckbox;
    }
}

$displayNotifyCheckBox = $displayNotifyCheckBox && $commentsModule->modelCanDoIt($class, 'displayNotifyCheckbox');

if ($commentsModule->enableModeration) {
    $commentModeration = CommentModeration::findOne([
        'classname' => get_class($model),
        'content_id' => $model->id
    ]);
    $commentDashboard = CommentDashboard::find()->one();
    if ($commentModeration && $commentDashboard) {
        $contextModel = $commentModeration->contextModel;
        $isCommunityContent = false;
        if (method_exists($contextModel, 'isNetworkContent')) {
            $isCommunityContent = $contextModel->isNetworkContent('community');
        }
        if ((!$isCommunityContent && $commentDashboard->moderation_platform) || ($isCommunityContent && $commentDashboard->moderation_communities)) {
            if ($commentModeration->moderation) {
                $moderationMessage = AmosComments::t('amoscomments', 'I commenti sono sottoposti a moderazione');
            }
        }
    }
}


?>

<div id="comments-loader" class="text-center hidden">
    <?= Html::img($asset->baseUrl . "/img/inf-circle-loader.gif", ['alt' => AmosComments::t('amoscomments', 'Loading')]) ?>
</div>
<div id="comments_anchor">
    <?php if (isset($moderationMessage)) { ?>
        <div class="alert alert-warning">
            <?= $moderationMessage ?>
        </div>
    <?php } ?>
    <div class="comment_content">


        <?php
        Pjax::begin([
            'id' => 'pjax-block-comments',
            'timeout' => 15000,
            'linkSelector' => false
        ]);
        ?>
        <?php if (!empty($comments)) { ?>
            <div class="comment-content-true">
                <div class="subtitle-comments">
                    <?=
                    (!empty($comments)) ? Html::tag('h3', $widget->options['lastCommentsTitle'], ['class' => 'subtitle-comments']) : ''
                    ?>


                    <?php
                    if (in_array($widget->model->className(), $commentsModule->bellNotificationEnabledClasses) && !$commentsModule->showBellIconInContentView) { ?>
                        <div class="subtitle-text-container">
                            <?php
                            if ($notificationUserStatus) {
                                $callToAction = Url::toRoute([
                                    '/comments/comment/comment-notification-user',
                                    'context' => $widget->model->className(),
                                    'contextId' => $widget->model->id,
                                    'enable' => 0,
                                ]); ?>
                                <a class="icon-link-black" href="<?= $callToAction ?>" title="Notifiche abilitate, clicca qui per disabilitarle">
                                    <span class="am am-notifications-add m-r-5" style="font-size: 24px;"></span>
                                </a>

                                <span class="m-r-5"> <?= AmosComments::t('amoscomments', '#able-notify') ?></span>
                                <a href="<?= $callToAction ?>"
                                   title="Notifiche abilitate, clicca qui per disabilitarle"> <?= AmosComments::t('amoscomments', '#disable-notify-link') ?></a>
                            <?php } else {
                                $callToAction = Url::toRoute([
                                    '/comments/comment/comment-notification-user',
                                    'context' => $widget->model->className(),
                                    'contextId' => $widget->model->id,
                                    'enable' => 1,
                                ]); ?>
                                <a class="icon-link-black" href="<?= $callToAction ?>"
                                   title="Notifiche disabilitate, clicca qui per abilitarle">
                                    <svg class="m-r-5" style="width:24px;height:24px" viewBox="0 0 24 24"><path fill="currentColor" d="M17.5 13A4.5 4.5 0 0 0 13 17.5A4.5 4.5 0 0 0 17.5 22A4.5 4.5 0 0 0 22 17.5A4.5 4.5 0 0 0 17.5 13M17.5 14.5A3 3 0 0 1 20.5 17.5A3 3 0 0 1 20.08 19L16 14.92A3 3 0 0 1 17.5 14.5M14.92 16L19 20.08A3 3 0 0 1 17.5 20.5A3 3 0 0 1 14.5 17.5A3 3 0 0 1 14.92 16M12 2C10.9 2 10 2.9 10 4C10 4.1 10 4.19 10 4.29C7.12 5.14 5 7.82 5 11V17L3 19V20H11.5A6.5 6.5 0 0 1 11 17.5A6.5 6.5 0 0 1 17.5 11A6.5 6.5 0 0 1 19 11.18V11C19 7.82 16.88 5.14 14 4.29C14 4.19 14 4.1 14 4C14 2.9 13.11 2 12 2M10 21C10 22.11 10.9 23 12 23C12.5 23 12.97 22.81 13.33 22.5A6.5 6.5 0 0 1 12.03 21Z"/></svg>
                                </a>

                                <span class="m-r-5"> <?= AmosComments::t('amoscomments', '#disable-notify') ?></span>
                                <a href="<?= $callToAction ?>" title="Notifiche disabilitate, clicca qui per abilitarle"> <?= AmosComments::t('amoscomments', '#able-notify-link') ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>

                </div>
                <?php
                $url                   = \Yii::$app->params['platform']['backendUrl'] . '/img/img_default.jpg';

                $baseUrl    = ((basename(Yii::getAlias('@app')) == 'backend') ? (!empty(\Yii::$app->params['platform']['backendUrl'])
                    ? \Yii::$app->params['platform']['backendUrl'] : "") : (!empty(\Yii::$app->params['platform']['frontendUrl'])
                    ? \Yii::$app->params['platform']['frontendUrl'] : ""));
                $currentUrl = str_replace('view?', 'public?', \yii\helpers\Url::current());
                foreach ($comments as $comment) :
                    if (empty($contextObject)) {
                        $classContext  = $comment->context;
                        $contextObject = $classContext::findOne($comment->context_id);

                        $image = open20\amos\comments\models\Comment::getImageContext($contextObject);
                        if (!is_null($image)) {
                            $url = \Yii::$app->params['platform']['backendUrl'] . $image->getWebUrl('square_large', false, true);
                        }
                    }
                    if ($comment->status == Comment::STATUS_TOVALIDATE) {
                        $isCommentToValidate = true;
                    } else {
                        $isCommentToValidate = false;
                    }

                    // $isCommentToValidate = ($status == Comment::STATUS_TOVALIDATE);
                    $classCommentToValidate = $isCommentToValidate ? 'comment-waiting' : 'no-comment-waiting';
                ?>
                    <?php
                    $createdUserProfile = $comment->createdUserProfile;
                    /** @var \open20\amos\comments\models\Comment $comment */
                    ?>
                    <div class="<?= $classCommentToValidate ?>">
                        <div class="answer media" id="comment-<?= $comment->id ?>">
                            <?php
                            if ($comment->status != Comment::STATUS_PUBLISHED) {
                                echo $this->render('parts/_labelCommentModeration', [
                                    'comment' => $comment,
                                    'status' => $comment->status,
                                    'userIsCommentsValidator' => CommentsUtility::userIsCommentsValidator($comment->contextModel),
                                ]);
                            } ?>
                            <?php if ($comment->status == Comment::STATUS_PUBLISHED || ($comment->status == Comment::STATUS_TOVALIDATE && (CommentsUtility::userIsCommentsValidator($comment->contextModel) || $comment->created_by == Yii::$app->user->id))) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php if (!empty($createdUserProfile)) { ?>
                                            <div class="media-left">
                                                <?= UserCardWidget::widget(['model' => $createdUserProfile, 'enableLink' => true]) ?>
                                            </div>
                                        <?php } ?>
                                        <?php $paddingBottom = $comment->status == Comment::STATUS_TOVALIDATE ? 'p-b-15' : ''; ?>
                                        <div class="answer-details media-body <?= $paddingBottom ?>">
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    <div>
                                                        <strong>
                                                            <?php
                                                            if (!empty($createdUserProfile)) {
                                                                if (isset(\Yii::$app->params['disableLinkContentCreator']) && (\Yii::$app->params['disableLinkContentCreator'] === true)) {
                                                                    echo $createdUserProfile;
                                                                } else {
                                                                    echo (!empty($createdUserProfile) ?
                                                                        Html::a(
                                                                            $createdUserProfile,
                                                                            [
                                                                                (\Yii::$app->user->isGuest ? '#' : '/' . AmosAdmin::getModuleName() . '/user-profile/view'),
                                                                                'id' => $createdUserProfile->id
                                                                            ]
                                                                        ) : '#### ####');
                                                                }
                                                            } else {
                                                                echo '#### ####';
                                                            } ?>
                                                        </strong>
                                                    </div>
                                                    <small class="text-muted"> <?= Yii::$app->getFormatter()->asDatetime($comment->created_at) ?></small>
                                                </div>
                                                <?php if ($widget->model->hasMethod('getCloseCommentThread') && !$widget->model->getCloseCommentThread()) {
                                                    echo ContextMenuWidget::widget([
                                                        'model' => $comment,
                                                        'actionModify' => "/" . AmosComments::getModuleName() . "/comment/update?id=" . $comment->id . "&url=" . \yii\helpers\Url::current(),
                                                        'disableModify' => $comment->status == Comment::STATUS_TOVALIDATE,
                                                        'actionDelete' => "/" . AmosComments::getModuleName() . "/comment/delete?id=" . $comment->id . "&url=" . \yii\helpers\Url::current(),
                                                        'mainDivClasses' => 'col-sm-1 col-xs-2 pull-right'
                                                    ]);
                                                } ?>

                                                <div class="clearfix"></div>
                                            </div>
                                            <p class="answer_text"><?= Yii::$app->getFormatter()->asRaw($comment->comment_text) ?></p>

                                            <?php
                                            if ($comment->status == Comment::STATUS_PUBLISHED) {
                                                if (!isset($commentsModule->socialShare) || $commentsModule->socialShare == true) {
                                                    echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                                                        'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_NORMAL,
                                                        'configuratorId' => 'socialShare',
                                                        'model' => $comment,
                                                        'url' => \yii\helpers\Url::to($baseUrl . $currentUrl, true),
                                                        'description' => '"' . $comment->comment_text . '"',
                                                        'imageUrl' => $url,
                                                        'isComment' => true,
                                                    ]);
                                                }
                                            }

                                            $commentAttachments = $comment->getCommentAttachmentsForItemView();
                                            if (count($commentAttachments) > 0) {
                                                if ($widget->model->hasMethod('getCloseCommentThread') && $widget->model->getCloseCommentThread()) {
                                                    echo AttachmentsTable::widget([
                                                        'model' => $comment,
                                                        'attribute' => 'commentAttachments',
                                                        'viewDeleteBtn' => false,
                                                    ]);
                                                } else {
                                                    echo AttachmentsTable::widget([
                                                        'model' => $comment,
                                                        'attribute' => 'commentAttachments',
                                                    ]);
                                                }
                                            }
                                            ?>
                                            <?php if ($comment->status == Comment::STATUS_PUBLISHED) { ?>
                                                <div class="answer-action">
                                                    <?php
                                                    $module = \Yii::$app->getModule('comments');
                                                    $replyComment = true;
                                                    if (!empty($module->enableCommentOnlyWithScope) && $module->enableCommentOnlyWithScope == true) {
                                                        $moduleCwh = \Yii::$app->getModule('cwh');
                                                        if (!is_null($moduleCwh)) {
                                                            $scope = $moduleCwh->getCwhScope();
                                                            if (!isset($scope['community'])) {
                                                                $replyComment = false;
                                                            }
                                                        }
                                                    }
                                                    if (Yii::$app->getUser()->can('COMMENT_CREATE', ['model' => $comment]) && $replyComment) {
                                                        if (!isset(Yii::$app->params['isPoi']) || !($widget->model->className() == News::className()
                                                            && $widget->model->id == 3126)) {
                                                    ?>
                                                            <?php
                                                            if ($widget->model->hasMethod('getCloseCommentThread') && !$widget->model->getCloseCommentThread()) :
                                                            ?>
                                                                <?=
                                                                Html::a(
                                                                    AmosComments::t('amoscomments', 'Reply'),
                                                                    'javascript:void(0);',
                                                                    [
                                                                        'class' => 'btn btn-secondary btn-xs reply-to-comment',
                                                                        'title' => AmosComments::t('amoscomments', 'Reply to comment'),
                                                                        'data-comment_id' => $comment->id
                                                                    ]
                                                                )
                                                                ?>
                                                    <?php
                                                            endif;
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <?php if ($replyComment) { ?>
                                                    <div id="bk-comment-reply-<?= $comment->id ?>" class="comment-reply-container hidden">
                                                        <?php
                                                        if (Yii::$app->getUser()->can('COMMENT_CREATE', ['model' => $comment])) {
                                                        ?>
                                                            <?php
                                                            if ($widget->model->hasMethod('getCloseCommentThread') && !$widget->model->getCloseCommentThread()) :
                                                            ?>
                                                                <?=
                                                                Html::label(
                                                                    AmosComments::t('amoscomments', 'Reply'),
                                                                    'comment-reply-area-' . $comment->id,
                                                                    ['class' => 'sr-only']
                                                                )
                                                                ?>
                                                            <?php endif; ?>
                                                        <?php } ?>
                                                        <?=
                                                        TextEditorWidget::widget([
                                                            'name' => 'comment-reply-area',
                                                            'value' => null,
                                                            'language' => substr(Yii::$app->language, 0, 2),
                                                            'options' => [
                                                                'id' => 'comment-reply-area-' . $comment->id,
                                                                'class' => 'comment-reply-input form-control'
                                                            ],
                                                            'clientOptions' => [
                                                                'placeholder' => $widget->options['commentReplyPlaceholder'],
                                                                'plugins' => $widget->plugins,
                                                                'toolbar' => $widget->toolbar,
                                                                'mobile' => $widget->rteMobile,
                                                                'buttonsHide' => [
                                                                    'image',
                                                                    'file'
                                                                ],
                                                            ],
                                                        ])
                                                        ?>
                                                        <?php
                                                        if ($commentsModule->modelCanDoIt($class, 'enableUserSendAttachment') && $commentsModule->enableAttachmentInComment) :
                                                        ?>
                                                            <?=
                                                            AttachmentsInput::widget([
                                                                'id' => 'commentReplyAttachments' . $comment->id,
                                                                'name' => 'commentReplyAttachments',
                                                                'model' => $widget->model,
                                                                'options' => [ // Options of the Kartik's FileInput widget
                                                                    'multiple' => true, // If you want to allow multiple upload, default to false
                                                                ],
                                                                'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                                                    'maxFileCount' => $commentsModule->maxCommentAttachments, // Client max files
                                                                    'showPreview' => true,
                                                                    'allowedFileExtensions' => $commentsModule->hasProperty(whiteListFilesExtensions) ? $commentsModule->whiteListFilesExtensions : '',
                                                                ]
                                                            ])
                                                            ?>
                                                        <?php endif; ?>
                                                        <?=
                                                        $this->render(
                                                            '_send_notify_checkbox',
                                                            [
                                                                'widget' => $widget,
                                                                'enableUserSendMailCheckbox' => $commentsModule->enableUserSendMailCheckbox,
                                                                'displayNotifyCheckBox' => $displayNotifyCheckBox,
                                                                'checkboxName' => 'send_reply_notify_mail',
                                                                'viewTypePosition' => CommentReply::VIEW_TYPE_POSITION
                                                            ]
                                                        )
                                                        ?>
                                                        <div class="clearfix"></div>
                                                        <div class="clear"></div>
                                                        <div class="bk-elementActions pull-right">
                                                            <?php
                                                            if ($widget->model->hasMethod('getCloseCommentThread') && !$widget->model->getCloseCommentThread()) :
                                                            ?>
                                                                <?=
                                                                Html::button(
                                                                    AmosComments::t('amoscomments', 'Reply'),
                                                                    [
                                                                        'id' => 'comment-reply-btn-' . $comment->id,
                                                                        'class' => 'btn btn-primary comment-reply-btn-class',
                                                                        'title' => AmosComments::t('amoscomments', 'Reply'),
                                                                        'data-comment_id' => $comment->id
                                                                    ]
                                                                )
                                                                ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Comment replies -->
                            <?php
                            $commentReplies = $comment->getCommentReplies()->orderBy(['created_at' => SORT_ASC])->all();
                            foreach ($commentReplies as $commentReply) {
                                /** @var CommentReply $commentReply */
                                if ($commentReply->status == Comment::STATUS_TOVALIDATE) {
                                    $isCommentToValidate = true;
                                } else {
                                    $isCommentToValidate = false;
                                }

                                // $isCommentToValidate = ($status == Comment::STATUS_TOVALIDATE);
                                $classCommentToValidate = $isCommentToValidate ? 'comment-waiting' : 'no-comment-waiting';
                            ?>
                                <div class="media col-xs-11 col-xs-offset-1 nop" id="comment-reply-<?= $commentReply->id ?>">
                                    <div class="<?= $classCommentToValidate ?>">
                                        <?php
                                        /** @var CommentReply $commentReply */
                                        if ($commentReply->status != Comment::STATUS_PUBLISHED) {
                                            echo $this->render('parts/_labelCommentModeration', [
                                                'comment' => $commentReply,
                                                'isReply' => true,
                                                'status' => $commentReply->status,
                                                'userIsCommentsValidator' => CommentsUtility::userIsCommentsValidator($commentReply->comment->contextModel),
                                            ]);
                                        }
                                        ?>
                                        <?php
                                        /** @var CommentReply $commentReply */
                                        if ($commentReply->status == Comment::STATUS_PUBLISHED || ($commentReply->status == Comment::STATUS_TOVALIDATE && (CommentsUtility::userIsCommentsValidator($commentReply->comment->contextModel) || $commentReply->created_by == Yii::$app->user->id))) { ?>
                                            <div class="m-t-30">
                                                <div class="media-left">
                                                    <?= UserCardWidget::widget(['model' => $commentReply->createdUserProfile, 'enableLink' => true]) ?>
                                                </div>
                                                <div class="media-body p-b-15">
                                                    <div class="col-xs-10 nop">
                                                        <div>
                                                            <strong>
                                                                <?= Html::a($commentReply->createdUserProfile, ['/' . AmosAdmin::getModuleName() . '/user-profile/view', 'id' => $commentReply->createdUserProfile->id]) ?>
                                                            </strong>
                                                        </div>
                                                        <small> <?= Yii::$app->getFormatter()->asDatetime($commentReply->created_at) ?></small>
                                                    </div>
                                                    <?php if ($widget->model->hasMethod('getCloseCommentThread') && !$widget->model->getCloseCommentThread()) { ?>
                                                        <?= ContextMenuWidget::widget([
                                                            'model' => $commentReply,
                                                            'actionModify' => "/" . AmosComments::getModuleName() . "/comment-reply/update?id=" . $commentReply->id . "&url=" . \yii\helpers\Url::current(),
                                                            'disableModify' => $commentReply->status == Comment::STATUS_TOVALIDATE,
                                                            'actionDelete' => "/" . AmosComments::getModuleName() . "/comment-reply/delete?id=" . $commentReply->id . "&url=" . \yii\helpers\Url::current(),
                                                            'mainDivClasses' => 'col-sm-1 col-xs-2 nop pull-right'
                                                        ]) ?>
                                                    <?php } ?>

                                                    <div class="clearfix"></div>

                                                    <p><?= Yii::$app->getFormatter()->asRaw($commentReply->comment_reply_text) ?></p>

                                                    <?php
                                                    if ($commentReply->status == Comment::STATUS_PUBLISHED) {
                                                        if (!isset($commentsModule->socialShare) || $commentsModule->socialShare == true) {
                                                            echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                                                                'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_NORMAL,
                                                                'configuratorId' => 'socialShare',
                                                                'model' => $commentReply,
                                                                'url' => \yii\helpers\Url::to($baseUrl . $currentUrl, true),
                                                                'description' => '"' . $commentReply->comment_reply_text . '"',
                                                                'imageUrl' => $url,
                                                                'isComment' => true,
                                                            ]);
                                                        }
                                                    }

                                                    $commentReplyAttachments = $commentReply->getCommentReplyAttachmentsForItemView();
                                                    if (count($commentReplyAttachments) > 0) {
                                                        if ($widget->model->hasMethod('getCloseCommentThread') && $widget->model->getCloseCommentThread()) {
                                                            echo AttachmentsTable::widget([
                                                                'model' => $commentReply,
                                                                'attribute' => 'commentReplyAttachments',
                                                                'viewDeleteBtn' => false,
                                                            ]);
                                                        } else {
                                                            echo AttachmentsTable::widget([
                                                                'model' => $commentReply,
                                                                'attribute' => 'commentReplyAttachments',
                                                            ]);
                                                        }
                                                    } ?>
                                                </div>
                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            <?php } ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!empty($pages)) { ?>
                    <?=
                    AmosLinkPager::widget([
                        'pagination' => $pages,
                        'showSummary' => true,
                    ]);
                    ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php Pjax::end(); ?>
    </div>
</div>