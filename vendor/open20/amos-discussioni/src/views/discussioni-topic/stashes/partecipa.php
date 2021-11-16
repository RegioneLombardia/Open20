<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\views\discussioni-topic
 * @category   CategoryName
 */

use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\attachments\components\AttachmentsTableWithPreview;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\forms\ShowUserTagsWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\icons\AmosIcons;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\InteractionMenuWidget;
use \open20\amos\discussioni\models\DiscussioniTopic;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $model->titolo;

if($model->status != DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA) {
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
}

?>

<div class="discussioni-topic-view">
    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="col-xs-12 header-widget nop">
                <?= ItemAndCardHeaderWidget::widget([
                        'model' => $model,
                        'publicationDateField' => 'created_at',
                        'showPrevalentPartnershipAndTargets' => true,
                    ]
                ) ?>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/discussioni/discussioni-topic/update?id=" . $model->id,
                    'actionDelete' => "/discussioni/discussioni-topic/delete?id=" . $model->id,
                    'modelValidatePermission' => 'DiscussionValidate'
                ]) ?>
                <?= CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]) ?>
                <?=
                \open20\amos\report\widgets\ReportFlagWidget::widget([
                    'model' => $model,
                ])
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-xs-12">
            <div class="header col-xs-12 nop">
                <?php
                $url = '/img/img_default.jpg';
                if (!is_null($model->discussionsTopicImage)) {
                    $url = $model->discussionsTopicImage->getUrl('original', false, true);
                    ?>
                    <?= Html::img($url, [
                        'class' => 'img-responsive',
                        'alt' => AmosDiscussioni::t('amosdiscussioni', 'Immagine della discussione')
                    ]); ?>
                    <?php
                }
                ?>
                <div class="title col-xs-12">
                    <h2 class="title-text"><?= $model->titolo ?></h2>
                </div>
            </div>
            <div class="text-content col-xs-12 nop">
                <?= $model->testo ?>
            </div>
            <div class="widget-body-content col-xs-12 nop">
                <?= \open20\amos\report\widgets\ReportDropdownWidget::widget([
                    'model' => $model
                ])
                ?>
                <?php $baseUrl = (!empty(\Yii::$app->params['platform']['backendUrl']) ? \Yii::$app->params['platform']['backendUrl'] : '') ?>
                <?= \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                    'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_DROPDOWN,
                    'configuratorId' => 'socialShare',
                    'model' => $model,
                    'url' => \yii\helpers\Url::to($baseUrl . '/discussioni/discussioni-topic/view?id=' . $model->id, true),
                    'title' => $model->titolo,
                    'description' => $model->getDescription(true),
    //                'imageUrl'      => !empty($model->getDiscussionsTopicImage()) ? $model->getDiscussionsTopicImage()->getWebUrl('square_small') : '',
                ]); ?>
            </div>
        </div>
        <div class="col-md-4 col-xs-12">
            <div class="col-xs-12 attachment-section-sidebar nop" id="section-attachments">
                <?= Html::tag('h2', AmosIcons::show('paperclip', [], 'dash') . AmosDiscussioni::t('amosdiscussioni', '#attachments_title')) ?>
                <div class="col-xs-12">
                    <?= AttachmentsList::widget([
                        'model' => $model,
                        'attribute' => 'discussionsAttachments',
                        'viewDeleteBtn' => false,
                        'viewDownloadBtn' => true,
                        'viewFilesCounter' => true,
                    ]) ?>
                </div>
            </div>
            <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">
                <?= Html::tag('h2', AmosIcons::show('tag', [], 'dash') . AmosDiscussioni::t('amosdiscussioni', '#tags_title')) ?>
                <div class="col-xs-12">
                    <?= \open20\amos\core\forms\ListTagsWidget::widget([
                        'userProfile' => $model->id,
                        'className' => $model->className(),
                        'viewFilesCounter' => true,
                    ]);
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
