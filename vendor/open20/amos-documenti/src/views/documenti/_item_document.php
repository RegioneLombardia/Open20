<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleSpinnerAsset;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();
$documentMainFile = $model->getDocumentMainFile();
$modelViewUrl = $model->getFullViewUrl();
$document = $model->getDocumentMainFile();
$documentPresent = ($document != null);
$documentLinkPresent = (!empty($model->link_document));
$visible = isset($statsToolbar) ? $statsToolbar : false;
$enableContentDuplication = $documentsModule->enableContentDuplication;
$enableCatImgInDocView = $documentsModule->enableCatImgInDocView;
$documentCategory = $model->documentiCategorie;
$isFolder = $model->is_folder;
$enableCategories = $documentsModule->enableCategories;
$modelTitleSpecialChars = htmlspecialchars($model->titolo);


$additionalButtons = [];
$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;

$controller = Yii::$app->controller;
$moduleId = $controller->module->id;
if ($documentsModule->enableMoveDoc && $scope['community'] && $documentsModule::getModuleName() == $moduleId && Yii::$app->getUser()->can('DocumentValidate', ['model' => $model]) ) {
    $additionalButtons[] = \yii\helpers\Html::a(AmosDocumenti::t('amoscommunity', "Sposta"), '#modalMove', [
        'class' => 'open-modalMove',
        'data-toggle' => 'modal',
        'data-id' => $model->id,
    ]);
}

$jsCount = <<<JS
    $('.link-document-id').click(function() {
        var idDoc = $(this).attr('data-key');
        $.ajax({
           url: 'increment-count-download-link?id='+idDoc,
           type: 'get',
           success: function (data) {
           }

      });
    })
JS;

$this->registerJs($jsCount);

$onlyofficeModule = AmosDocumenti::instance()->getModuleOnlyOffice();
if ($onlyofficeModule) {
    $iconoo = $onlyofficeModule->isValidExtension($document->type) ? true : false;
}
$assetBundle = ModuleSpinnerAsset::register($this);
?>

<div class="document-item-container d-flex border-bottom py-4 w-100">
    <div class="info-doc">
        <div>
            <?php if ($iconoo) { ?>
                <img class="only-office-image" src="<?= $assetBundle->baseUrl ?>/img/ONLYOFFICE_logo.png" title="Only Office" alt="Only Office">
            <?php } ?>
            <?= \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model); ?>
            <span class="text-muted small"><?= is_null($model->getDocumentMainFile() && empty($model->link_document)) ? AmosDocumenti::tHtml('amosdocumenti', 'Documento testuale') : $docExtension = strtoupper($document->type); ?>
                <?php if ($documentPresent): ?>
                    (<?= $model->documentMainFile->formattedSize ?>) - <?= AmosDocumenti::tHtml('amosdocumenti', 'File principale:') ?>
                <?php endif; ?>
             </span>

            <?php
            if ($documentPresent) {
                echo Html::tag('span', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'text-muted small']);
            } else {
                if ($documentLinkPresent) {
                    echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'File esterno')), ['class' => 'text-muted small']);
                }else{
                    echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'Documento testuale')), ['class' => 'text-muted small']);
                }
            }
            ?>

        </div>
        <?php if ($documentLinkPresent) : ?>
            <?= Html::a(
                $model->titolo,
                $model->link_document,
                [
                    'class' => 'link-list-title h5',
                    'target' => 'blank',
                    'title' => AmosDocumenti::t('amosdocumenti', 'Apri il link esterno al documento') . ' ' . $modelTitleSpecialChars
                ]
            )
            ?>
        <?php else : ?>
            <?= Html::a(
                $model->titolo,
                $modelViewUrl,
                [
                    'class' => 'link-list-title h5',
                    'title' => AmosDocumenti::t('amosdocumenti', 'Apri la scheda del documento') . ' ' . $modelTitleSpecialChars
                ]
            )
            ?>
        <?php endif; ?>
        <?php if ($model->status != \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) { ?>
            <i style='color:#777777'><?= "(" . AmosDocumenti::t('amosdocumenti', $model->status) . ")"; ?></i>
        <?php } ?>

        <?php if ($model->descrizione_breve) { ?>
            <p class="mb-0 m-t-5 text-muted">
                <?= htmlspecialchars($model->descrizione_breve) ?>
            </p>
        <?php } ?>
        <div class="small mb-0 m-t-10">
            <?= PublishedByWidget::widget([
                'model' => $model,
                'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '{targetAdv}' . ((!$isFolder && $enableCategories) ? '<label>Categoria:</label>&nbsp ' . ' <div><span class="nome-categoria"> ' . $documentCategory->titolo . '</span></div>' : '') . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
            ]) ?>
        </div>
        <div class="small mb-2">
            <?php 
            if(!empty($model->parent_id)){
                $stringa = \open20\amos\documenti\models\DocumentiCartellePath::getPath($model);
                echo AmosDocumenti::t(
                    'amosdocumenti',
                    '<strong>Percorso:</strong> '
                ) . $stringa . $model->titolo;
            }
            ?>
        </div>
        <div>
            <?php
            if ($documentPresent) {
                echo Html::a(
                    AmosDocumenti::t('amosdocumenti', 'Scarica'),
                    $document->getUrl(),
                    [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica il documento') . ' ' . $modelTitleSpecialChars,
                        'class' => 'm-r-10 small uppercase bold',
                    ]
                );
            }

            ?>


                <?= Html::a(
                    AmosDocumenti::t('amosdocumenti', '#detail'),
                    $modelViewUrl,
                    [
                        'class' => 'small uppercase bold',
                        'title' => AmosDocumenti::t('amosdocumenti', '#see_document_detail') . ' ' . $modelTitleSpecialChars
                    ]
                )
                ?>

        </div>
    </div>
    <div class="ml-auto doc-actions d-flex">
        <div>
            <?= NewsWidget::widget(['model' => $model]); ?>
        </div>
        <div class="" style="align-self: flex-end">
            <?= $model->getArchiving(true) ?>
        </div>
        <div>
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                'modelValidatePermission' => 'DocumentValidate',
                'additionalButtons' => $additionalButtons,
                'mainDivClasses' => 'manage-documents'
            ]) ?>
            
        </div>
        
    </div>
</div>
