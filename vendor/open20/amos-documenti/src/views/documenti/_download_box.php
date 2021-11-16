<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use open20\amos\documenti\utility\DocumentsUtility;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var bool $isFolder
 * @var \open20\amos\documenti\controllers\DocumentiController $controller
 * @var bool $showNewVersionButton
 */

$select2Id = 'all-document-versions-id';
$assetBundle = \open20\amos\documenti\assets\ModuleDocumentiAsset::register($this);
$js = "
$('#" . $select2Id . "').on('change', function(e) {
    e.preventDefault();
    var selectedValue = $(this).val();
    window.location.href = '/documenti/documenti/view?id=' + selectedValue;
});
";

$idModel = $model->id;
$jsGoogleDrive = <<<JS
      $.ajax({
           url: 'is-google-drive-document-modified?id=$idModel',
           type: 'get',
           success: function (data) {
               console.log(data);
              if(data == true){
                  $('#sync-file-button-id').show();
                  $('#drive-file-modified-at-id').hide();
              }else {
                  $('#sync-file-button-id').hide();
                  $('#drive-file-modified-at-id').show();
              }
           }

      });
JS;

/** @var DocumentiController $appController */
$appController = Yii::$app->controller;

/** @var AmosDocumenti $documentsModule */
$documentsModule = $appController->documentsModule;

$documentMainFile = $model->getDocumentMainFile();
$documentLinkPresent = (!empty($model->link_document));
$enableContentDuplication = $documentsModule->enableContentDuplication;
$foldersEnabled = $documentsModule->enableFolders;
$enableDocumentVersioning = $documentsModule->enableDocumentVersioning;
$documentCategory = $model->documentiCategorie;

if ($model->drive_file_id) {
    $this->registerJs($jsGoogleDrive);
}
?>
<div class="col-xs-12 download-file nop">
    <?php if (!$isFolder && $enableDocumentVersioning) : ?>
        <div class="col-xs-12">
            <?= Select2::widget([
                'model' => $model,
                'attribute' => 'version',
                'data' => ArrayHelper::map($model->allDocumentVersions, 'id', 'versionInfo'),
                'options' => [
                    'placeholder' => AmosDocumenti::t('amosdocumenti', 'Cambia versione'),
                    'id' => $select2Id,
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'multiple' => false,
                    'value' => $model->id
                ],
                //                'addon' => [
                //                    'prepend' => $model->getAttributeLabel('version')
                //                ]
            ]) ?>
        </div>
    <?php endif; ?>
    <div class="col-xs-12 action-document">
        <?php if ($documentsModule->enableCatImgInDocView): ?>
            <div class="documents-category-new-rl">
                <?= Html::img($documentCategory->getAvatarUrl('square_small'), [
                    'class' => 'gridview-image',
                    'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')
                ]); ?>
                <p><?= $documentCategory->titolo ?></p>
            </div>
        <?php else: ?>
            <?= DocumentsUtility::getDocumentIcon($model); ?>
        <?php endif; ?>
        <div>
            <?php
            if (!is_null($documentMainFile)) {
                echo Html::tag(
                    'p',
                    (
                    (strlen($documentMainFile->name) > 80)
                        ? substr($documentMainFile->name, 0, 75) . '[...]'
                        : $documentMainFile->name) . '.' . $documentMainFile->type,
                    ['class' => 'filename']
                );
                if ($model->drive_file_id) {
                    echo Html::tag('em', AmosDocumenti::t('amosdocumenti', 'Questo è un file presente su Google Drive'));
                    if (!empty($model->drive_file_modified_at)) {
                        echo Html::tag(
                            'em',
                            ' - ' . AmosDocumenti::t('amosdocumenti', 'aggiornato') . ' ' . \Yii::$app->formatter->asDatetime($model->drive_file_modified_at),
                            [
                                'id' => 'drive-file-modified-at-id',
                                'style' => 'display:none'
                            ]
                        );
                    }
                }
            } else if ($model->is_folder && $model->drive_file_id) {
                echo Html::tag('em', AmosDocumenti::t('amosdocumenti', 'Questa è una cartella sincronizzata con Google Drive'));
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
                }
            }
            ?>
        </div>
        <div class="col-xs-12 m-t-5 p-t-5">
            <?php

            $btns = $this->render('_duplicate_btn', [
                'model' => $model,
                'isInIndex' => false
            ]);

            if (!is_null($documentMainFile)) {
                $btns .= Html::a(AmosDocumenti::tHtml('amosdocumenti', 'Scarica il documento ') /*.
                    AmosIcons::show('download')*/,
                    [
                        '/attachments/file/download/', 'id' => $documentMainFile->id,
                        'hash' => $documentMainFile->hash
                    ],
                    [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'bk-btnImport pull-right btn btn-secondary m-r-5 m-b-5',
                    ]
                );
            }

            if ($model->drive_file_id) {
                $btns .= Html::a(
                    AmosIcons::show('refresh-sync-alert'),
                    [
                        '/documenti/documenti/sync-doc-file', 'id' => $model->id,
                    ],
                    [
                        'id' => 'sync-file-button-id',
                        'title' => AmosDocumenti::t('amosdocumenti', 'Sync drive file'),
                        'class' => 'bk-btnImport pull-right btn btn-icon',
                        'style' => 'display:none',
                        'data-confirm' => AmosDocumenti::t('amosdocumenti', "Vuoi sincronizzare il file con l'ultima versione aggiornata sul drive?")
                    ]
                );
            }

            if ($showNewVersionButton) {
                if (!$isFolder && $enableDocumentVersioning) {
                    if (Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model, 'newVersion' => true])) {
                        $btns .= \open20\amos\core\utilities\ModalUtility::addConfirmRejectWithModal([
                            'modalId' => 'new-document-version-modal-id-' . $model->id,
                            'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT'),
                            'btnText' => AmosDocumenti::t('amosdocumenti', 'New document version'),
                            'btnLink' => Yii::$app->urlManager->createUrl([
                                '/documenti/documenti/new-document-version',
                                'id' => $model['id']
                            ]),
                            'btnOptions' => [
                                'title' => AmosDocumenti::t('amosdocumenti', 'New document version'),
                                'class' => 'bk-btnImport pull-right btn btn-secondary m-r-5 '
                            ],

                        ]);
                    }
                }
            }
            echo $btns;
            ?>
        </div>
    </div>
</div>
