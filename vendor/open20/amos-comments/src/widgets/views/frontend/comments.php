<?php

use yii\widgets\Pjax;
use open20\amos\core\views\AmosLinkPager;
use open20\amos\comments\AmosComments;
use open20\amos\attachments\components\AttachmentsTable;
use open20\amos\attachments\FileModule;

$currentAsset = \open20\amos\comments\assets\FrontendAsset::register($this);

Pjax::begin([
    'id' => 'pjax-block-comments',
    'timeout' => 15000,
    'linkSelector' => false
]);
?>


<?php
$prev[-1] = 'bk-contribute';
foreach ($comments as $k => $comment):
    ?>
    <?php
    /** @var \open20\amos\comments\models\Comment $comment */
    $prev[$k] = 'comment_id'.$comment->id;
    ?>

    <div class="media border-bottom mb-4">
        <!--        <div class="avatar size-sm mr-2">
                    <img src="< ?= $comment->createdUserProfile->getAvatarUrl('square_small') ?>" alt="< ?= $comment->createdUserProfile->nomeCognome ?>">
                </div>-->
        <div id="comment_id<?= $comment->id ?>" class="media-body">
            <p class="mt-0 mb-2">

                <small>
                    <!--<a href="/admin/user-profile/view?id=< ?= $comment->createdUserProfile->id ?>">-->
                    <?= $comment->createdUserProfile->nomeCognome ?>
                    <!--</a>-->
                </small>
            </p>
            <p>
                <?= \Yii::$app->formatter->asHtml($comment->comment_text) ?>
            </p>
            <p>
                <span class="text-muted"><?= \Yii::$app->formatter->asDate($comment->created_at) ?></span>
                <span class="text-muted"><?= \Yii::$app->formatter->asTime($comment->created_at) ?></span>
            </p>
            <?php //$commentAttachments = $comment->getCommentAttachmentsForItemView(); ?>
            <?php /* if (count($commentAttachments) > 0) { ?>
              <p>Allegati</p>
              <?php
              foreach ($commentAttachments as $k => $v) {
              $urlDelete = \yii\helpers\Url::to([
              '/'.FileModule::getModuleName().'/file/delete',
              'id' => $v->id,
              'item_id' => $comment->id,
              'model' => get_class($comment),
              'attribute' => 'commentAttachments'
              ]);
              ?>
              <p>
              <a href="<?= $v->getWebUrl() ?>" title="<?= $v->name ?>">
              <?= $v->name ?>
              </a>
              <?php if (\Yii::$app->user->can('COMMENT_UPDATE', ['model' => $comment])) { ?>
              <a href="<?= $urlDelete ?>" title="<?= AmosComments::t('amoscomments', 'Cancella') ?>" data-confirm="<?=
              AmosComments::t('amoscomments', 'Sei sicuro di voler cancellare l\'allegato?')
              ?>">
              <span class="rounded-icon rounded-white">
              <svg class="icon icon-dark">
              <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#ic_delete"></use>
              </svg>
              </span>
              <span class="sr-only"><?= AmosComments::t('amoscomments', 'Cancella allegato') ?></span>
              </a>
              <?php } ?>
              </p>
              <?php
              }
              } */
            ?>
        </div>
    </div>
<?php endforeach; ?>
<?php Pjax::end(); ?>