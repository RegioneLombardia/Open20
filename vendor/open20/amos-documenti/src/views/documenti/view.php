<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\editors\likeWidget\LikeWidget;
use open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\ListTagsWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\web\View;
use open20\onlyoffice\widgets\EditorWidget;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();

/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$hidePubblicationDate = $controller->documentsModule->hidePubblicationDate;

$additionalButtons = [];
$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;

$moduleId = $controller->module->id;
if($documentsModule->enableMoveDoc && $scope['community'] && $documentsModule::getModuleName() == $moduleId){
    $additionalButtons[]  = \yii\helpers\Html::a(AmosDocumenti::t('amoscommunity', "Sposta"),'#modalMove', [
        'class' => 'open-modalMove',
        'data-toggle' => 'modal',
        'data-id' => $model->id,
    ]);
}

if (\Yii::$app->user->can('ADMIN')) {
    $layoutPublishedByWidget = $documentsModule->layoutPublishedByWidget['layoutAdmin'];
} else {
    $layoutPublishedByWidget = $documentsModule->layoutPublishedByWidget['layout'];
}

$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = [
    'label' => AmosDocumenti::t('amosdocumenti',
        Yii::$app->session->get('previousTitle')),
    'url' => Yii::$app->session->get('previousUrl')
];
$this->params['breadcrumbs'][] = $this->title;

// Tab ids
$idTabCard = 'tab-card';
$idClassifications = 'tab-classifications';
$idTabAttachments = 'tab-attachments';

$document = $model->getDocumentMainFile();
$documentCategory = $model->documentiCategorie;

$this->registerJs($js, View::POS_READY);

$jsCount = <<<JS
    $('#link-document-id').click(function() {
        $.ajax({
           url: 'increment-count-download-link?id=$model->id',
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
$viewReportWidgets = (
    !is_null($reportModule)
    && in_array($model->className(), $reportModule->modelsEnabled)
);

if($documentsModule->enableMoveDoc){
        echo $this->render('_move_document', [
            'parentId' => $model->parent_id,
    ]); 

}
?>

<div class="documents-view">
    <div class="row">
        <div class="col-xs-12 header-widget">
            <?= ItemAndCardHeaderWidget::widget([
                'model' => $model,
                'publicationDateField' => 'data_pubblicazione',
                'showPrevalentPartnershipAndTargets' => true,
            ]) ?>
            <div class="more-info-content">
                <?php
                if ($model->canShowCommentsNotificationBellWidget()) {
                    // Comments notification bell widget
                    echo \open20\amos\comments\widgets\CommentsBellNotificationWidget::widget([
                        'modelClassname' => $model->modelClassName,
                        'modelId' => $model->id,
                        'buttonClass' => $viewReportWidgets ? 'btn btn-outline-tertiary m-r-10' : ''
                    ]);
                }
                ?>
                <?php if ($viewReportWidgets) : ?>
                    <?= \open20\amos\report\widgets\ReportDropdownWidget::widget([
                        'model' => $model,
                    ]); ?>
                <?php endif; ?>
                <div class="m-l-10">
                    <?php
                    $contextMenuWidgetConf = [
                        'model' => $model,
                        'actionModify' => '/documenti/documenti/update?id=' . $model->id,
                        'actionDelete' => '/documenti/documenti/delete?id=' . $model->id,
                        'additionalButtons' => $additionalButtons,
                        'modelValidatePermission' => 'DocumentValidate',
                    ];

                    if ($isFolder) {
                        $contextMenuWidgetConf['labelDeleteConfirm'] = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
                    }

                    echo ContextMenuWidget::widget($contextMenuWidgetConf);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="document-wrapper">
        <div class="row">
            <div class="col-md-6">
                <div class="document-title flexbox">
                    <?php if ($documentsModule->enableCatImgInDocView) : ?>
                        <div class="documents-category-new-rl">
                            <?= Html::img($documentCategory->getAvatarUrl('square_small'), [
                                'class' => 'gridview-image',
                                'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')
                            ]); ?>
                            <p><?= $documentCategory->titolo ?></p>
                        </div>
                    <?php else : ?>
                        <div class="col-xs-12 nop">
                            <?php if ($documentsModule->showCategoriesInView) : ?>
                                <?= $model->getAttributeLabel('documenti_categorie_id') . ': ' . '<span class="badge-document badge badge-pill">' . $documentCategory->titolo .'</span>' ?>
                            <?php endif; ?>
                        </div>
                        <?= \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model); ?>

                    <?php endif; ?>
                    <div>
                        <?php
                        
                        if (!is_null($document)) {
                            $docName = $document->name;
                            if ($docName) {
                                $name = (strlen($docName) > 80)
                                    ? substr($docName, 0, 75) . '[...]'
                                    : $docName . '.' . $document->type;
                            } else {
                                $name = $model->titolo;
                            }
                            if($model->documentMainFile) {
                                echo Html::a(
                                    $name,
                                    $model->documentMainFile->getUrl(),
                                    ['class' => 'filename ', 'data-toggle' => 'tooltip', 'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file')]
                                );
                                echo Html::tag(
                                    'span',
                                    (' ('
                                        . $document->formattedSize) . ')',
                                    [
                                        'class' => 'text-muted small'
                                    ]
                                );
                            }
                            
                            if ($model->drive_file_id) {
                                echo Html::tag(
                                    'em',
                                    AmosDocumenti::t('amosdocumenti', '#google_drive_file')
                                );
                                if (!empty($model->drive_file_modified_at)) {
                                    echo Html::tag(
                                        'em',
                                        ' - '
                                        . AmosDocumenti::t('amosdocumenti', 'aggiornato')
                                        . ' '
                                        . \Yii::$app->formatter->asDatetime($model->drive_file_modified_at),
                                        [
                                            'id' => 'drive-file-modified-at-id',
                                            'style' => 'display:none'
                                        ]
                                    );
                                }
                            }
                        } else if (is_null($document) && empty($model->link_document)) {
                            echo Html::tag(
                                'em',
                                AmosDocumenti::t('amosdocumenti', 'Documento testuale')
                            );
                        } else if ($model->is_folder && $model->drive_file_id) {
                            echo Html::tag(
                                'em',
                                AmosDocumenti::t('amosdocumenti', '#sync_google_drive_folder')
                            );
                        } else {
                            if ($documentLinkPresent) {
                                echo Html::a(
                                    (strlen($model->link_document) > 80)
                                        ? AmosDocumenti::t('amosdocumenti', '#download_document_for_view')
                                        : $model->link_document,
                                    $model->link_document,
                                    [
                                        'target' => '_blank',
                                        'class' => 'btn btn-navigation-primary link-documents',
                                        'id' => 'link-document-id'
                                    ]
                                );
                            } else {
                                echo Html::a(
                                    AmosDocumenti::tHtml('amosdocumenti', 'Apri link esterno'),
                                    $model->link_document,
                                    [
                                        'title' => $model->link_document,
                                        'target' => '_blank',
                                        'data-key' => $model->id
                                    ]
                                );
                            }
                        }
                        ?>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <?php if($documentsModule->enableAnnualArchiving): ?>
                <div class="archiving text-right">
                     <?= $model->getArchiving(true) ?>
                </div>
                <?php endif; ?>
                <div class="download-box m-t-30">
                    <?php echo $this->render('_download_box', [
                        'model' => $model,
                        'isFolder' => $isFolder,
                        'controller' => \Yii::$app->controller,
                        'showNewVersionButton' => true
                    ]) ?>
                </div>
            </div>
        </div>

        <?php 
            $onlyofficeModule = Yii::$app->getModule('onlyoffice');
            $modelUserProfile = \open20\amos\admin\models\UserProfile::find()
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->one();
			
            if($onlyofficeModule && $onlyofficeModule->isValidExtension($model->documentMainFile->type)): ?>
                <div class="open-office m-t-20">
                    <?= EditorWidget::widget([
							'urlFile' => $model->documentMainFile->getWebUrl('original', true, false),
							'keyFile' => $model->documentMainFile->hash,
							'nameFile' => $model->documentMainFile->name . '.' . strtolower($model->documentMainFile->type),
							'options' => [
								'iframeHeight' => '700px',
								'fileTypeAuto' => strtolower($model->documentMainFile->type),
								'documentTypeAuto' => true,
							],
							'configForJs' => [
								'documentType' => $onlyofficeModule->getDocumentTypeByExtension($model->documentMainFile->type,true),
								'editorConfig' => [
									'mode' => 'view',
									'callbackUrl' => Url::to(['/documenti/onlyoffice/callback-api'], true),
									'customization' => [
										'forcesave' => false, 
									],
									
									'user' => [
										'id' => Yii::$app->user->id,
										'name' => ((!empty($modelUserProfile)) ? $modelUserProfile->nomeCognome : ''),
									],
								],
							]
						]) ?>
                </div>
        <?php endif; ?>
        
        <?php if(!empty($model->descrizione_breve)): ?>
        <div class="document-description m-t-20">
            <p class="text-uppercase"><strong><?= AmosDocumenti::t('amosdocumenti', 'descrizione_breve'); ?></strong></p>
            <?= $model->descrizione_breve; ?>
        </div>
        <?php endif; ?>
        <div class="document-description m-t-20">
            <p class="text-uppercase"><strong><?= $model->getAttributeLabel('descrizione'); ?></strong></p>
            <?= $model->descrizione; ?>
        </div>

        <div class="section-attachment " id="section-attachments">
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
        <div class="section-tags m-t-30" id="section-tags">
            <?= ListTagsWidget::widget([
                'userProfile' => $model->id,
                'className' => $model->className(),
                'viewFilesCounter' => true,
            ]);
            ?>
        </div>
    <?php } ?>

    <div class="footer-content">
        <div class="social-share-wrapper">
            <?= SocialShareWidget::widget([
                'mode' => SocialShareWidget::MODE_NORMAL,
                'configuratorId' => 'socialShare',
                'model' => $model,
                'url' => \yii\helpers\Url::to($baseUrl . '/documenti/documenti/public?id=' . $model->id, true),
                'title' => $model->title,
                'description' => $model->descrizione_breve,
            ]); ?>
        </div>

        <div class="widget-body-content">
            <?= LikeWidget::widget([
                'model' => $model,
            ]);
            ?>
        </div>
    </div>
</div>
