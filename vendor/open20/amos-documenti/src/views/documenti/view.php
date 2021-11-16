<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\models\File;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\SortModelsUtility;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;
use open20\amos\attachments\utility\SortAttachmentsUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}
$idDoc =  $model->id;

/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$hidePubblicationDate = $controller->documentsModule->hidePubblicationDate;
$isFolder = $controller->documentIsFolder($model);
$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

// Tab ids
$idTabCard = 'tab-card';
$idClassifications = 'tab-classifications';
$idTabAttachments = 'tab-attachments';


$this->registerJs($js, View::POS_READY);

$jsCount = <<<JS
    $('#link-document-id').click(function() {
        $.ajax({
           url: 'increment-count-download-link?id=$idDoc',
           type: 'get',
           success: function (data) {
           }

      });
    })
JS;

$this->registerJs($jsCount);

$primoPiano = '';
$inEvidenza = '';

if ($model->status != Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
}

/** @var \open20\amos\report\AmosReport $reportModule */
$reportModule = Yii::$app->getModule('report');
$viewReportWidgets = (!is_null($reportModule) && in_array($model->className(), $reportModule->modelsEnabled));

?>

<div class="documents-view col-xs-12 nop">
    <div class="col-md-8 col-xs-12">
        <div class="col-xs-12 header-widget nop">
            <?= ItemAndCardHeaderWidget::widget([
                    'model' => $model,
                    'publicationDateField' => 'data_pubblicazione',
                    'showPrevalentPartnershipAndTargets' => true,
                ]
            ) ?>
            <?php
            $contextMenuWidgetConf = [
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl(),
                'modelValidatePermission' => 'DocumentValidate',
//                'labelModify' => AmosDocumenti::t('amosdocumenti', "New document version"),
//                'actionModify' => $controller->documentsModule->enableDocumentVersioning ? "/documenti/documenti/new-document-version?id=" . $model->id : "/documenti/documenti/update?id=" . $model->id,
//                'checkModifyPermission' => $controller->documentsModule->enableDocumentVersioning ? !(\Yii::$app->user->can('DOCUMENTI_UPDATE')) : true,
//                'confirmModify' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT')
            ];
            if ($model->is_folder) {
                $contextMenuWidgetConf['labelDeleteConfirm'] = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
            }
            ?>
            <?= ContextMenuWidget::widget($contextMenuWidgetConf) ?>
            <?php
            $moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
            if (\Yii::$app->user->can('ADMIN')) {
                $layoutPublishedByWidget = $moduleDocumenti->layoutPublishedByWidget['layoutAdmin'];
            } else {
                $layoutPublishedByWidget = $moduleDocumenti->layoutPublishedByWidget['layout'];
            }
            ?>
            <?= PublishedByWidget::widget([
                'model' => $model,
                'layout' => $layoutPublishedByWidget,
                'isTooltip' => true
            ]) ?>
            <?php if ($viewReportWidgets): ?>
                <?= \open20\amos\report\widgets\ReportFlagWidget::widget([
                    'model' => $model,
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-8 col-xs-12">
        <div class="header col-xs-12 nop">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
                <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
            </div>
        </div>

        <?php echo $this->render('_download_box', [
            'model' => $model,
            'isFolder' => $isFolder,
            'controller' => \Yii::$app->controller,
            'showNewVersionButton' => true
        ])?>

        <div class="text-content col-xs-12 nop">
            <?= $model->descrizione; ?>
        </div>
        <div class="widget-body-content col-xs-12 nop">
            <?php echo \open20\amos\core\forms\editors\likeWidget\LikeWidget::widget([
                'model' => $model,
            ]);
            ?>

            <?php if ($viewReportWidgets): ?>
                <?= \open20\amos\report\widgets\ReportDropdownWidget::widget([
                    'model' => $model,
                ]); ?>
            <?php endif; ?>
            <?php $baseUrl = (!empty(\Yii::$app->params['platform']['backendUrl']) ? \Yii::$app->params['platform']['backendUrl'] : '') ?>
            <?= \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_DROPDOWN,
                'configuratorId' => 'socialShare',
                'model' => $model,
                'url' => \yii\helpers\Url::to($baseUrl . '/documenti/documenti/public?id=' . $model->id, true),
                'title' => $model->title,
                'description' => $model->descrizione_breve,
//                'imageUrl'      => !empty($model->getNewsImage()) ? $model->getNewsImage()->getWebUrl('square_small') : '',
            ]); ?>
        </div>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="col-xs-12 attachment-section-sidebar nop" id="section-attachments">
            <?= Html::tag('h2', AmosIcons::show('paperclip', [], 'dash') . AmosDocumenti::t('amosdocumenti', '#attachments_title')) ?>
            <div class="col-xs-12">
                <?= AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'documentAttachments',
                    'viewDeleteBtn' => false,
                    'viewDownloadBtn' => true,
                    'viewFilesCounter' => true,
                    'enableSort' => true,
                    'viewSortBtns' => false,
                    'requireModalMoveFile' => AmosDocumenti::instance()->requireModalMoveFile
                ]) ?>
            </div>
        </div>
        <?php if (!empty(\Yii::$app->getModule('tag'))) { ?>
            <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">
                <?= Html::tag('h2', AmosIcons::show('tag', [], 'dash') . AmosDocumenti::t('amosdocumenti', '#tags_title')) ?>
                <div class="col-xs-12">
                    <?= \open20\amos\core\forms\ListTagsWidget::widget([
                        'userProfile' => $model->id,
                        'className' => $model->className(),
                        'viewFilesCounter' => true,
                    ]);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>


</div>
<?= Html::a(AmosDocumenti::t('amosdocumenti', '#go_back'), (\Yii::$app->request->referrer ?: Yii::$app->session->get('previousUrl')), [
    'class' => 'btn btn-secondary pull-left'
]) ?>
