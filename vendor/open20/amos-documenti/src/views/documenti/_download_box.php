<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
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

if ($model->drive_file_id) {
    $this->registerJs($jsGoogleDrive);
}
$this->registerJs($js, \yii\web\View::POS_READY);
?>
<div class="download-file">
   
    <?php if (!$isFolder && $enableDocumentVersioning) : ?>
            <div class="select-container">
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
    <div class="action-document">
            
            
                <?php

                $btns = $this->render('_duplicate_btn', [
                    'model' => $model,
                    'isInIndex' => false
                ]);

           

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
                                    'class' => 'bk-btnImport btn btn-primary m-l-5'
                                ],

                            ]);
                        }
                    }
                }
                echo $btns;
                ?>
            
       
    </div>
        
    
    
</div>
