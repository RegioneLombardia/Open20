<?php

use yii\widgets\Pjax;
use open20\amos\core\views\AmosLinkPager;
use open20\amos\comments\AmosComments;
use open20\amos\attachments\components\AttachmentsTable;
use open20\amos\attachments\FileModule;
use open20\amos\admin\AmosAdmin;


$currentAsset = $asset::register($this);
\open20\amos\comments\widgets\SelectCommentEvidence::registerJsWidget($currentAsset);
Pjax::begin([
    'id' => 'pjax-block-comments',
    'timeout' => 15000,
    'linkSelector' => false
]);
?>


<?php
$prev[-1] = 'bk-contribute';
foreach ($comments as $k => $comment) :
    /** @var \open20\amos\comments\models\Comment $comment */
    $prev[$k] = 'comment_id' . $comment->id;

    $classAdded = '';
    if($module->enableCommentInEvidence && $comment->in_evidence){
        $classAdded = 'added-to-evidence';
    }

    ?>
    <div class="media border-bottom mb-4 <?=$classAdded?>">
        <div class="avatar size-sm mr-2">
            <img src="<?= $comment->createdUserProfile->getAvatarUrl('square_small') ?>"
                 alt="<?= $comment->createdUserProfile->nomeCognome ?>">
        </div>
        <div id="comment_id<?= $comment->id ?>" class="media-body">
            <p class="mt-0 mb-2">
                <small><a href="/<?= AmosAdmin::getModuleName() ?>/user-profile/view?id=<?= $comment->createdUserProfile->id ?>"><?= $comment->createdUserProfile->nomeCognome ?></a>
                    <span class="text-muted"><?= \Yii::$app->formatter->asDatetime($comment->created_at) ?></span>
                </small>
                <?=  \open20\amos\comments\widgets\SelectCommentEvidence::widget([
                        'model' => $comment,
                        'module' => $module,
                        'asset' => $currentAsset,
                ])?>

            </p>
            <?= \Yii::$app->formatter->asHtml($comment->comment_text, [
                'HTML.SafeIframe' => true,
                'Attr.AllowedFrameTargets' => '_blank',
            ]); ?>
            <?php $commentAttachments = $comment->getCommentAttachmentsForItemView(); ?>
            <?php if (count($commentAttachments) > 0) { ?>
                <div class="p-3 lightgrey-bg-c1  rounded">
                    <p class="h6 text-uppercase">
                        <svg class="icon icon-xs">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_attach_file"></use>
                        </svg>
                        Allegati
                    </p>
                    <?php
                    foreach ($commentAttachments as $k => $v) {
                        $urlDelete = \yii\helpers\Url::to([
                            '/' . FileModule::getModuleName() . '/file/delete',
                            'id' => $v->id,
                            'item_id' => $comment->id,
                            'model' => get_class($comment),
                            'attribute' => 'commentAttachments'
                        ]); ?>
                        <span class="badge badge-pill badge-light px-3">
                            <a href="<?= $v->getWebUrl() ?>" data-toggle="tooltip" title="Scarica <?= $v->name ?>">
                                <?= $v->name ?>
                            </a>
                            <?php if (\Yii::$app->user->can('COMMENT_UPDATE', ['model' => $comment])) { ?>
                                <a href="<?= $urlDelete ?>" data-toggle="tooltip"
                                   title="<?= AmosComments::t('amoscomments', 'Cancella') ?>"
                                   data-confirm="<?= AmosComments::t('amoscomments', 'Sei sicuro di voler cancellare l\'allegato?') ?>">
                                    <span class="ml-1">
                                        <svg class="icon icon-xs">
                                            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_close"></use>
                                        </svg>
                                    </span>
                                    <span class="sr-only"><?= AmosComments::t('amoscomments', 'Cancella allegato') ?></span>
                                </a>
                            <?php } ?>
                        </span>
                    <?php } ?>
                </div>
            <?php } ?>

            <p class="mt-2">
                <small>
                    <?php if (\Yii::$app->user->can('COMMENT_UPDATE', ['model' => $comment])) { ?>
                        <a href="<?= "/" . AmosComments::getModuleName() . "/comment/update?id=" . $comment->id . "&noAttach=$no_attach&url=" . \yii\helpers\Url::current() . "#comment_id" . $comment->id ?>"
                           class="mr-3">
                            <?= AmosComments::t('amoscomments', 'Modifica') ?>
                        </a>
                    <?php } ?>
                    <?php if (\Yii::$app->user->can('COMMENT_DELETE', ['model' => $comment])) { ?>
                        <a class="text-danger"
                           href="<?= "/" . AmosComments::getModuleName() . "/comment/delete?id=" . $comment->id . "&url=" . \yii\helpers\Url::current() . "#" . $prev[$k - 1] ?>"><?= AmosComments::t('amoscomments', 'Elimina') ?>
                        </a>
                    <?php } ?>
                </small>
            </p>
        </div>
    </div>
<?php endforeach; ?>
<?php Pjax::end(); ?>