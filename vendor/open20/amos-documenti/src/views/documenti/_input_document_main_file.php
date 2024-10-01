<?php

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\onlyoffice\models\GestioneTemplate;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

$enabledGoogleDrive = $module && $module->enableGoogleDrive;
$enableDragAndDrop = $module && $module->enableDragAndDrop;
$onlyofficeModule = AmosDocumenti::instance()->getModuleOnlyOffice();

if ($onlyofficeModule) {
    $buttonDisalbe = !$onlyofficeModule->isValidExtension($model->documentMainFile->type) ? "disabled" : "";
    $new_files = $onlyofficeModule->tipiGestibili;
    $file_list = '';
    foreach ($new_files as $file) {
        $file_list .= $file . ", ";
    }
    $file_list = rtrim($file_list, ", ");
}

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();
$allowedExtensions = str_replace(' ', '', $documentsModule->whiteListFilesExtensions);

$js = <<<JS

    $('#type-main-document-id').change(function(){
        if($(this).val() ==='1'){
            $('#main-file-container').show();
            $('#link-document-container').hide();
            $('#onlyoffice-document-type').hide();
            $('#link-document-id').val('');           
        } else if($(this).val() ==='2') {
            $('#main-file-container').hide();
            $('#onlyoffice-document-type').hide();
            $('#link-document-container').show();
            $('.file-input .fileinput-remove').click();
        } else if($(this).val() ==='3') {
            $('#onlyoffice-document-type').show();
            $('#main-file-container').hide();
            $('#link-document-container').hide();
            $('#link-document-id').val('');
            $('.file-input .fileinput-remove').click();
        }
    });
JS;

$this->registerJs($js);
?>

<!-- Type main document -->
<?= $form->field($model, 'typeMainDocument')->widget(\kartik\select2\Select2::class, [
    'data' => $model->getTypeMainDocument(),
    'options' => [
        'id' => 'type-main-document-id',
        'disabled' => !$model->isNewRecord
    ]
])->label(AmosDocumenti::t('amosdocumenti', 'tipo di documento')) ?>

<?php if ($enabledGoogleDrive && !empty($model->drive_file_id)) { ?>
    <div class="documents-view">
        <?= $this->render('_download_box', [
            'model' => $model,
            'isFolder' => $isFolder,
            'controller' => \Yii::$app->controller,
            'showNewVersionButton' => false
        ]) ?>
    </div>
<?php } else { ?>
    <?php if ($enabledGoogleDrive) { ?>
        <?= \open20\amos\documenti\widgets\GoogleDriveWidget::widget([
            'model' => $model,
            'form' => $form
        ]); ?>
    <?php } ?>

    <?php if (!$isFolder) { ?>

        <?php if ($onlyofficeModule && !$model->isNewRecord) : ?>
            <div id="onlyoffice-document" class="col-xs-12 m-b-20 nop">
                <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Apri con OnlyOffice'), ['/documenti/documenti/onlyoffice-edit', 'id' => $model->id], ['class' => 'btn btn-outline-primary ' . $buttonDisalbe, 'title' => 'prova']); ?>
                <?php if ($buttonDisalbe) : ?>
                    <p><small><?= AmosDocumenti::t('amosdocumenti', 'Formato non supportato da onlyoffice. I formati supportati sono: ') . $file_list ?></small></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div id="container-document-mainfile" class="col-xs-12 nop">

            <div id="main-file-container" style="<?= $model->typeMainDocument == Documenti::MAIN_DOCUMENT_TYPE_FILE ? '' : 'display:none' ?>">
                <?= $form->field(
                    $model,
                    'documentMainFile'
                )->widget(AttachmentsInput::classname(), [
                    'options' => [
                        'multiple' => FALSE,
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 1,
                        'showRemove' => true,
                        'indicatorNew' => false,
                        'allowedPreviewTypes' => false,
                        'previewFileIconSettings' => false,
                        'overwriteInitial' => false,
                        'allowedFileExtensions' => explode(',', $allowedExtensions),

                        //'layoutTemplates' => false,
                    ],
                    'enableDragAndDrop' => $enableDragAndDrop,
                    'enableGoogleDrive' => $enabledGoogleDrive,
                ])->label(AmosDocumenti::t('amosdocumenti', '#image_field'))
                    ->hint(AmosDocumenti::t('amosdocumenti', 'Rappresenta il documento principale.</br> Le estensioni accettate sono: {whiteListFilesExtensions}', [
                        'whiteListFilesExtensions' => str_replace(',', ', ', $allowedExtensions)
                    ])); ?>

            </div>
            <?= $form->field($model, 'mainDocumentNumber')->hiddenInput(['id' => 'mainDocumentNumber'])->label(false) ?>
            <div id="link-document-container" style="<?= $model->typeMainDocument == Documenti::MAIN_DOCUMENT_TYPE_LINK ? '' : 'display:none' ?>">
                <?= $form->field($model, 'link_document')->textInput([
                    'maxlength' => true,
                    'placeholder' => AmosDocumenti::t('amosdocumenti', '#link_document_field_placeholder'),
                    'id' => 'link-document-id'
                ])
                    ->hint(AmosDocumenti::t('amosdocumenti', '#link_document_field_hint'))
                ?>
            </div>

            <?php if ($onlyofficeModule) : ?>
                <div id="onlyoffice-document-type" style="<?= $model->typeMainDocument == Documenti::MAIN_DOCUMENT_TYPE_ONLYOFFICE ? '' : 'display:none' ?>">
                    <?= $form->field($model, 'onlyOfficeNewFile')->widget(\kartik\select2\Select2::class, [
                        'data' => $model->getOnlyOfficeNewFiles(),
                        'options' => [
                            'id' => 'onlyoffice-file-id'
                        ],
                        'pluginOptions' => [
                            'placeholder' => 'Seleziona ...',
                        ]
                    ])->label(AmosDocumenti::t('amosdocumenti', '#onlyoffice_type_file'))
                    ?>

                    <!-- //depdrop -->

                    <?php if (!empty($model->onlyOfficeNewFile)) {
                        $datiTemplate = null;
                        $moduleOnlyOffice = \open20\onlyoffice\Module::instance();
                        if  (isset($moduleOnlyOffice->tipiGestibiliToTipiTemplate) && array_key_exists($model->onlyOfficeNewFile, $moduleOnlyOffice->tipiGestibiliToTipiTemplate)) {
                            $datiTemplate = ArrayHelper::map(GestioneTemplate::find()->select(['id', 'nome'])
                                ->andWhere(['tipo' => $moduleOnlyOffice->tipiGestibiliToTipiTemplate[$model->onlyOfficeNewFile]])
                                ->orderBy('nome')->asArray()->all(), 'id', 'nome');
                        }
                    }
                    else {
                        $datiTemplate = null;
                    } ?>

                    <?= $form->field($model, 'idTemplate')->widget(DepDrop::class, [
                        'type' => DepDrop::TYPE_SELECT2,
                        'data' => $datiTemplate,
                        'select2Options' => [
                            'pluginOptions' => [
                                'allowClear' => false
                            ]
                        ],
                        
                        'pluginOptions' => [
                            'depends' => [
                                'onlyoffice-file-id',
                            ],
                            'placeholder' => 'Seleziona ...',
                            'url' => Url::to(['template-by-tipo']),
                        ]
                    
                    ])->label(AmosDocumenti::t('amosdocumenti', 'Seleziona un template')) ?>
                </div>


            <?php endif; ?>

            <?php if (!empty($documento)) : ?>
                <?= $documento->filename ?>
                <?= Html::a(AmosIcons::show('download', ['class' => 'btn btn-tools-secondary']), ['/documenti/documenti/download-documento-principale', 'id' => $model->id], [
                    'title' => 'Download file',
                    'class' => 'bk-btnImport'
                ]); ?>
            <?php endif; ?>

        </div>
    <?php } ?>
<?php } ?>