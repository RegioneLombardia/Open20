<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\StringUtils;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */


/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();

$modelViewUrl = $model->getFullViewUrl();
$document = $model->getDocumentMainFile();
$documentPresent = ($document != null);
$documentLinkPresent = (!empty($model->link_document));
$visible = isset($statsToolbar) ? $statsToolbar : false;
$enableContentDuplication = $documentsModule->enableContentDuplication;
$enableCatImgInDocView = $documentsModule->enableCatImgInDocView;
$documentCategory = $model->documentiCategorie;

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

<div class="document-item-container d-flex border-bottom py-4 w-100">
    <div class="info-doc">

        <?= Html::a(Html::tag('h5', htmlspecialchars($model->titolo)), $modelViewUrl, ['class' => 'link-list-title ']) ?>
        <?php if ($model->descrizione_breve) { ?>
            <p class="mb-0">
                <?= htmlspecialchars($model->descrizione_breve) ?>
            </p>
        <?php } ?>
        <div class="small mb-2">
            <?= PublishedByWidget::widget([
                'model' => $model,
                'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '{targetAdv}{category}' . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
            ]) ?>
        </div>
        <div>
            <?php
            if ($documentPresent) {
                echo Html::a(
                    AmosDocumenti::tHtml('amosdocumenti', 'Scarica'),
                    [
                        '/attachments/file/download/',
                        'id' => $document->id,
                        'hash' => $document->hash
                    ],
                    [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'text-uppercase font-weight-semibold mr-2',
                    ]
                );
            } else {
                if ($documentLinkPresent) {
                    echo Html::a(
                        AmosDocumenti::tHtml('amosdocumenti', 'Apri file'),
                        $model->link_document,
                        [
                            'title' => AmosDocumenti::t('amosdocumenti', 'Apri file'),
                            'class' => 'text-uppercase font-weight-semibold mr-1',
                            'target' => '_blank',
                            'data-key' => $model->id
                        ]
                    );
                }
            }

            ?>
            <span class="text-muted small">(.<?= $docExtension = strtolower($document->type); ?> - <?= $model->documentMainFile->size%1024 ?> Kb)</span>
        </div>

    </div>
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
                'mainDivClasses' => 'manage-documents'
            ]) ?>
        </div>
        <div>
            <?php
            echo Html::a(AmosIcons::show('search-in-file', ['class' => 'icon text-white p-2 rounded-circle bg-primary text-center'], 'am'), $modelViewUrl, ['class' => '', 'data-toggle' => 'tooltip', 'title' => 'Vedi dettaglio']);
            ?>
        </div>
    </div>
</div>