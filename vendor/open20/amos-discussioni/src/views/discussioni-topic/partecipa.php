<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\views\discussioni-topic
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\editors\likeWidget\LikeWidget;
use open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\ListTagsWidget;
use open20\amos\core\helpers\Html;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $model->titolo;

if ($model->status != DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA) {
    echo WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true,
    ]);
}

/** @var AmosDiscussioni $discussioniModule */
$discussioniModule = AmosDiscussioni::instance();

/** @var open20\amos\report\AmosReport $reportModule */
$reportModule = Yii::$app->getModule('report');

?>


<div class="discussioni-topic-view detail-discussion row">
    <!--parte sx-->
    <div class="col-sm-12">
        <div class="first-info">
            <div class="info-container m-b-20">
                <div>
                    <?= ItemAndCardHeaderWidget::widget([
                        'model' => $model,
                        'publicationDateField' => 'created_at',
                        'showPrevalentPartnershipAndTargets' => true,
                        'absoluteUrlAvatar' => true,
                    ]) ?>
                    <div>
                        <small><?= AmosDiscussioni::t('amosdiscussioni', '#published_at'); ?> <?= Yii::$app->formatter->asDatetime($model->created_at, 'humanalwaysdatetime') ?></small><br>
                        <small><?= AmosDiscussioni::t('amosdiscussioni', '#last_update'); ?>: <?= Yii::$app->formatter->asDatetime($model->updated_at) ?> </small>
                    </div>
                </div>
                <div class="more-info-content">
                    <?php
                    if ($model->canShowCommentsNotificationBellWidget()) {
                        // Comments notification bell widget
                        echo \open20\amos\comments\widgets\CommentsBellNotificationWidget::widget([
                            'modelClassname' => $model->modelClassName,
                            'modelId' => $model->id,
                        ]);
                    } ?>

                    <div class="m-l-10">
                        <?= ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/discussioni/discussioni-topic/update?id=" . $model->id,
                            'actionDelete' => "/discussioni/discussioni-topic/delete?id=" . $model->id,
                            'labelDeleteConfirm' => AmosDiscussioni::t('amosdiscussioni', '#ask_confirm_to_cancel_discussion'),
                            'modelValidatePermission' => 'DiscussionValidate'
                        ]) ?>
                    </div>

                </div>
            </div>

            <!--< ?= CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]) ?>
            < ?=
            \open20\amos\report\widgets\ReportFlagWidget::widget([
                'model' => $model,
            ])
            ?>-->
            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->discussionsTopicImage)) {
                $url = $model->discussionsTopicImage->getWebUrl('original', false, true);
            ?>
                <?= Html::img($url, [
                    'class' => 'img-responsive img-discussione',
                    'alt' => AmosDiscussioni::t('amosdiscussioni', 'Immagine della discussione')
                ]); ?>
            <?php
            }
            ?>



        </div>
        <div class="second-info m-t-20">
            <?= $model->testo ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php
        $tagsWidget = '';
        $tagsWidget = ListTagsWidget::widget([
            'userProfile' => $model->id,
            'className' => $model->className(),
            'viewFilesCounter' => true,
        ]);
        ?>

        <?= AttachmentsList::widget([
            'model' => $model,
            'attribute' => 'discussionsAttachments',
            'viewDeleteBtn' => false,
            'viewDownloadBtn' => true,
            'viewFilesCounter' => true,
        ]); ?>

        <div class="clearfix"></div>

        <div class="tag-container">
            <?= $tagsWidget ?>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="third-info">
            <div class="widget-body-content col-xs-12 nop">
                <?= LikeWidget::widget([
                    'model' => $model,
                ]);
                ?>
                <?php if (!$discussioniModule->disableReportFlag && !is_null($reportModule) && in_array($discussioniModule->model('DiscussioniTopic'), $reportModule->modelsEnabled)) : ?>
                    <?= \open20\amos\report\widgets\ReportDropdownWidget::widget([
                        'model' => $model,
                    ])
                    ?>
                <?php endif; ?>
            </div>
            <div class="social-share-wrapper">
                <?php
                echo SocialShareWidget::widget([
                    'mode' => SocialShareWidget::MODE_NORMAL,
                    'configuratorId' => 'socialShare',
                    'model' => $model,
                    'url' => Url::to($baseUrl . '/discussioni/discussioni-topic/public?id=' . $model->id, true),
                    'title' => $model->titolo,
                    'description' => $model->getDescription(true),
                ]);
                ?>
            </div>
            <?php if ((isset($discussioniModule->disableComments) && $discussioniModule->disableComments) && $model->close_comment_thread) : ?>
                <div class="closed-label col-xs-12">
                    <?= AmosDiscussioni::t('amosdiscussioni', '#discussion_closed') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>