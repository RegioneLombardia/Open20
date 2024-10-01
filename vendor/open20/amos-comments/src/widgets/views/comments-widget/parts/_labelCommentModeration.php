<?php

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\utility\CommentsUtility;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var Comment|CommentReply $comment
 * @var bool $isReply
 * @var bool $userIsCommentsValidator
 * @var bool $isCommentReply
 * @var string $status
 */

if ($status == Comment::STATUS_REJECTED) { ?>
    <div class="comment-deleted">
        <div class="label-comment-deleted">
            <span class="mdi mdi-close-circle-outline mdi-24px m-r-5"></span>
            <strong class="text-uppercase"><?= AmosComments::t('amoscomments', 'Commento cancellato'); ?></strong>
        </div>
        <?php if ($userIsCommentsValidator) { ?>
            <div class="data-comment-deleted">
                <span class="text-muted"><?= Yii::$app->getFormatter()->asDatetime($comment->created_at) ?></span>
            </div>
        <?php } ?>
    </div>
<?php } else if ($status == Comment::STATUS_TOVALIDATE) {
    if (isset($isReply) && $isReply) {
        $isCommentReply = true;
        /** @var CommentReply $comment */
        $redirectUrl = $comment->comment->contextModel->getFullViewUrl();
    } else {
        $isCommentReply = false;
        /** @var Comment $comment */
        $redirectUrl = $comment->contextModel->getFullViewUrl();
    } ?>
    <div class="header-comment-waiting">
        <div class="label-comment-waiting text-muted">
            <span class="mdi mdi-message-processing-outline mdi-24px m-r-5"></span>
            <strong class="text-uppercase"><?= AmosComments::t('amoscomments', 'Commento in attesa di validazione'); ?></strong>
        </div>

        <?php if ($userIsCommentsValidator) { ?>
            <div class="actions-comment-waiting">
                <?= Html::a('<span class="mdi mdi-check-circle"></span>', ['/comments/comment-dashboard/validate-comment',
                    'id' => $comment->id,
                    'isCommentReply' => $isCommentReply,
                    'redirectUrl' => $redirectUrl
                ], [
                    'class' => 'btn btn-success',
                    'title' => AmosComments::t('amoscomments', 'Valida commento'),
                ]) ?>
                <?= Html::a('<span class="mdi mdi-close-circle"></span>', ['/comments/comment-dashboard/reject-comment',
                    'id' => $comment->id,
                    'isCommentReply' => $isCommentReply,
                    'redirectUrl' => $redirectUrl
                ], [
                    'class' => 'btn btn-danger',
                    'title' => AmosComments::t('amoscomments', 'Rifiuta commento'),
                    'data-confirm' => AmosComments::t('amoscomments', 'Sei sicuro di voler rifiutare questo commento?'),
                ]) ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>