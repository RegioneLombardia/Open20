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
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */
$documentsModule = AmosDocumenti::instance();
$moduleId = \Yii::$app->controller->id;
$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
$queryParams = \Yii::$app->request->getQueryParams();

if ($moduleId == 'documenti') {
    $modelViewUrl = \Yii::$app->urlManager->createAbsoluteUrl([
        '/documenti/documenti/' . \Yii::$app->controller->action->id,
        'parentId' => $model->id,
        'DocumentiSearch' => $queryParams['DocumentiSearch'],
    ]);
} else {
    $modelViewUrl = \Yii::$app->urlManager->createAbsoluteUrl([
        '/documenti/documenti/all-documents',
        'parentId' => $model->id,
        'DocumentiSearch' => $queryParams['DocumentiSearch'],
    ]);
}
$stringa = DocumentiCartellePath::getPath($model); 


$additionalButtons = [];

if ($documentsModule->enableMoveDoc && $scope['community'] && $documentsModule::getModuleName() == $moduleId && Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
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

?>

<div class="document-item-container item-folder flexbox-column border-bottom py-4 w-100">

    <div class="flexbox align-item-center">
        <?php
        if ($model->drive_file_id) { ?>
            <span class="icon icon-folder icon-sm mdi mdi-folder-google-drive"></span>
        <?php
        } else { ?>
            <span class="icon icon-folder icon-sm mdi mdi-folder"></span>
            <?php
        }
        ?>
        <span class="small uppercase"><?= AmosDocumenti::t('amosdocumenti', 'Cartella') ?></span>

        <div class="ml-auto doc-actions d-flex">
            <div>
                <?= NewsWidget::widget(['model' => $model]); ?>
            </div>
            <div>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                    'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                    'modelValidatePermission' => 'DocumentValidate',
					'additionalButtons' => $additionalButtons,
                    'labelDeleteConfirm' => AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder')
                ]) ?>
            </div>

        </div>
    </div>

    <?php
    $status = '';
    if ($model->status != \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) { ?>
        <?php  $status = "<small><i> (".AmosDocumenti::t('amosdocumenti', $model->status).")</i></small>"; ?>
    <?php }?>
    <?= Html::a(Html::tag('p', htmlspecialchars($model->titolo) . $status, ['class' => 'h5 title-one-line']),
        $modelViewUrl,
        [
            'class' => 'link-list-title',
            'title' => AmosDocumenti::t('amosdocumenti', 'Vai alla cartella ') . $model->titolo
        ]
    ); ?>


    <div class="small mb-0">
      
     <?= PublishedByWidget::widget([
            'model' => $model,
            'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '{targetAdv}' . ((!$isFolder && $enableCategories) ? '{category}' : '') . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
        ]) ?>
    </div>
    <div class="small mb-2">
        <?php 
            if(!empty($model->parent_id)){
                echo AmosDocumenti::t('amosdocumenti', '<strong>Percorso:</strong> ')
                . $stringa
                . $model->titolo;
            }
        ?>
    </div>
    <div>
        <?= Html::a(
            AmosDocumenti::t('amosdocumenti', 'Apri') ,
            $modelViewUrl,
            [
                'class' => 'read-more d-inline mr-2 uppercase bold small',
                'title' => AmosDocumenti::t('amosdocumenti', 'Apri la cartella ')
            ]
        )?>
    </div>
    
</div>