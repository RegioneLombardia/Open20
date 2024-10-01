<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\forms\editors\Select;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType;
use kartik\widgets\SwitchInput;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var yii\widgets\ActiveForm $form
 * @var array $scope
 */

/** @var \open20\amos\documenti\controllers\DocumentiController $appController */
$appController = Yii::$app->controller;
/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();
$isFolder = $appController->documentIsFolder($model);
$enableDragAndDrop = $documentsModule && $documentsModule->enableDragAndDrop;
$enableCategories = $documentsModule->enableCategories;
$enableVersioning = $appController->documentsModule->enableDocumentVersioning;
$enablePublishUnpublishFolder = $appController->documentsModule->enablePublishUnpublishFolder;
$isNewVersion = !empty(\Yii::$app->request->get('isNewVersion')) ? \Yii::$app->request->get('isNewVersion') : false;
$offunusedfields = \Yii::$app->request->get('offunusedfields');
$hideUnusedFields = $offunusedfields ? 'display:none' : '';
$enableAnnualArchiving = $documentsModule->enableAnnualArchiving;
$enableCountdownUnpublishing = $documentsModule->enableCountdownUnpublishing;

$disableStandardWorkflow = $appController->documentsModule->disableStandardWorkflow;

/** @var AmosDocumenti $module */
$module = Yii::$app->getModule(AmosDocumenti::getModuleName());
$moduleSeo = Yii::$app->getModule('seo');
$hideSeoModuleClass = $documentsModule->hideSeoModule ? ' hidden' : '';

$moduleGroups = Yii::$app->getModule('groups');
$moduleCommunity = Yii::$app->getModule('community');
$moduleCwh = Yii::$app->getModule('cwh');
$moduleNotify = \Yii::$app->getModule('notify');

$enableGroupNotification = $documentsModule->enableGroupNotification;

// AGID FIELDS ENABLE
$enableAgid = $documentsModule->enableAgid;

$primoPiano = '';
$inEvidenza = '';
$enableComments = '';

/** @var \open20\amos\comments\AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule('comments');

$allowedExtensions = str_replace(' ', '', $documentsModule->whiteListFilesExtensions);

$showSectionScheduledSettings = ($module->hidePubblicationDate == false) || ($enableCountdownUnpublishing && !$isFolder) || ($enableAnnualArchiving && !$isFolder);
$commentsEnabled = (!is_null($commentsModule) && in_array($model->className(), $commentsModule->modelsEnabled));
if ($enableGroupNotification) {

    $modelSearchProfile = new UserProfileSearch();
    $dataProviderProfiles = $modelSearchProfile->search(\Yii::$app->request->get());
    $dataProviderProfiles->setSort([
        'defaultOrder' => [
            'nome' => SORT_ASC,
        ],
    ]);
    $dataProviderProfiles->pagination = false;
    $idCommunityMembers = implode(',', $dataProviderProfiles->keys);
    $js = <<< JS
        $(document).ready(function(){
            var selectedProfiles = [$idCommunityMembers];
            initialize();
            function setChecked() {
                $('#grid-members tbody tr').each(function() {
                    var valore = $(this).find('input').val();
                    var flag = 0;

                    for(var i=0; i < selectedProfiles.length; i++) {
                         if(selectedProfiles[i] == valore ) {
                             $(this).find('input').attr('checked', true);
                             $(this).addClass('success');
                             flag = 1;
                         }
                    }

                    if(flag == 0) {
                         $(this).removeClass('success');
                        $(this).find('input').removeAttr('checked');
                    }
                });
            }

               $(document).on('click','#grid-members .kv-row-checkbox', function() {
                var tr = $(this).closest('tr');
                var user_profile_id = $(tr).attr('data-key');
                if(this.checked) {
                    selectedProfiles.push(user_profile_id);
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'profile-'+user_profile_id,
                        name: 'selection-profiles[]',
                        value: user_profile_id
                    }).appendTo('form');
                }
                else {
                    //remove selection
                     for(var i = selectedProfiles.length - 1; i >= 0; i--) {
                        if(selectedProfiles[i] === user_profile_id) {
                           selectedProfiles.splice(i, 1);
                        }
                    }
                    $('#profile-'+user_profile_id).remove();
                }

          });

         $(document).on('pjax:end', function(data, status, xhr, options) {
            setChecked();
        });

         $('#grid-members .select-on-check-all').click(function(){
             if(!this.checked) {
                 for(var i=0; i < selectedProfiles.length; i++) {
                      $('#profile-'+selectedProfiles[i]).remove();
                      $('#grid-members tbody tr[data-key='+ selectedProfiles[i]+']').removeClass('success');
                 }
                 selectedProfiles = [];
             }
            else {
                 selectedProfiles = [$idCommunityMembers];
                 for(var j=0; j < selectedProfiles.length; j++) {
                      if($('#profile-'+selectedProfiles[j]).length == 0){
                         $('<input>').attr({
                           type: 'hidden',
                           id: 'profile-'+selectedProfiles[j],
                           name: 'selection-profiles[]',
                           value: selectedProfiles[j]
                       }).appendTo('form');
                         $('#grid-members tbody tr[data-key='+ selectedProfiles[j]+']').addClass('success');
                     }
                }
            }
         });

        function initialize(){
              for(var i=0; i < selectedProfiles.length; i++) {
                  $('<input>').attr({
                        type: 'hidden',
                        id: 'profile-'+selectedProfiles[i],
                        name: 'selection-profiles[]',
                        value: selectedProfiles[i]
                    }).appendTo('form');
                }
                setChecked();
        }

    });

JS;

    $this->registerJs($js);

    $RteToolbar = "undo redo | styleselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link";
}

\open20\amos\documenti\assets\ModuleSpinnerAsset::register($this);

$js = <<< JS
    $('form#doc-form').on('submit', function(e) {
        let data = $('form#doc-form').data('yiiActiveForm');
        if (data.submitting)
            $('#loading-modal').modal();
    })
JS;

$this->registerJs($js);

/** @var \open20\amos\report\AmosReport $reportModule */
$reportModule = Yii::$app->getModule('report');
$viewReportWidgets = (!is_null($reportModule) && in_array($model->className(), $reportModule->modelsEnabled));

$reportFlagWidget = '';
if ($viewReportWidgets) {
    $reportFlagWidget = \open20\amos\report\widgets\ReportFlagWidget::widget([
        'model' => $model,
    ]);
}

// id 20683: CHECK/VALIDATE FIELDS "DATE PUBBLICAZIONE" ON FOCUSOUT AND KEYUP
$this->registerJs(
    <<<JS

$("#documenti-data_pubblicazione-disp").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("#documenti-data_rimozione-disp").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("#documenti-data_pubblicazione-disp").on("focusout", function () {
  validateDatePubblicazioneFields();
});

$("#documenti-data_rimozione-disp").on("focusout", function () {
  validateDatePubblicazioneFields();
});

window.validateDatePubblicazioneFields = function () {
  setTimeout(function () {
    $('#doc-form').yiiActiveForm('validateAttribute', 'documenti-data_pubblicazione');
    $('#doc-form').yiiActiveForm('validateAttribute', 'documenti-data_rimozione');
  }, 1000);
}
JS
);

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'], // important
    'id' => 'doc-form',
]);

echo WorkflowTransitionStateDescriptorWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
    'classDivMessage' => 'message',
    'viewWidgetOnNewRecord' => false,
]);
?>

    <div class="modal fade" id="loading-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle"><?= AmosDocumenti::t('amosdocumenti', '#loading') ?></h5>
                </div>
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only"><?= AmosDocumenti::t('amosdocumenti', '#loading') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="documenti-form ">
        <?= $this->render('boxes/box_custom_fields_begin', ['form' => $form, 'model' => $model]); ?>
        <div class="row">
            <!--documenti-->
            <?php if (!$isFolder) { ?>

                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Documenti') ?></h2>
                    <?= $this->render('boxes/box_custom_uploads_begin', [
                        'form' => $form,
                        'model' => $model,
                    ]); ?>
                    <div class="row">

                        <div class="col-xs-12">
                            <?= \open20\amos\documenti\widgets\DocumentMainFileInputWidget::widget([
                                'model' => $model,
                                'form' => $form,
                                'isFolder' => $isFolder,
                            ]); ?>
                        </div>

                    </div>
                    <?= $this->render('boxes/box_custom_uploads_end', ['form' => $form, 'model' => $model]); ?>
                </div>
            <?php } ?>

            <?php if (!$isFolder) : ?>
                <!--Allegati-->
                <div class="col-xs-12 section-form">
                    <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Allegati') ?></h2>
                    <?= $this->render('boxes/box_custom_uploads_begin', [
                        'form' => $form,
                        'model' => $model,
                    ]); ?>
                    <div class="row">

                        <div class="col-xs-12 ">
                            <?= $form->field($model, 'documentAttachments')->widget(AttachmentsInput::classname(), [
                                'options' => [ // Options of the Kartik's FileInput widget
                                    'multiple' => true, // If you want to allow multiple upload, default to false
                                ],
                                'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                    'maxFileCount' => 100, // Client max files
                                    'showPreview' => true,
                                    'showRemove' => true,
                                    'allowedFileExtensions' => explode(',', $allowedExtensions)
                                ],
                                'enableDragAndDrop' => $enableDragAndDrop,
                            ])->label(AmosDocumenti::t('amosdocumenti', 'document_attachments'))
                                ->hint(AmosDocumenti::t('amosdocumenti', 'Tieni premuto il tasto "MAIUSCOLO" mentre selezioni i file, per caricarne più di uno alla volta.</br> Le estensioni accettate sono: {whiteListFilesExtensions}', ['whiteListFilesExtensions' => $documentsModule->whiteListFilesExtensions])) ?>

                            <?= AttachmentsList::widget([
                                'model' => $model,
                                'attribute' => 'documentAttachments',
                                'enableSort' => true,
                                'requireModalMoveFile' => $documentsModule->requireModalMoveFile
                            ]) ?>
                        </div>
                    </div>
                    <?= $this->render('boxes/box_custom_uploads_end', ['form' => $form, 'model' => $model]); ?>
                </div>
            <?php endif; ?>


            <!--Informazioni di base-->
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Informazioni di base') ?></h2>
                <div class="row">

                    <div class="col-xs-12">
                        <?= $this->render('boxes/box_custom_fields_begin', ['form' => $form, 'model' => $model]); ?>

                        <!-- Titolo -->
                        <?= $form->field($model, 'titolo')->textInput([
                            'maxlength' => true,
                            'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_title_field_placeholder')
                        ])->hint(AmosDocumenti::t('amosdocumenti', 'Limite max: {x} caratteri', [
                            'x' => !empty(\Yii::$app->params['amosDocumentiFieldTitleMaxLength']) ? \Yii::$app->params['amosDocumentiFieldTitleMaxLength'] : Documenti::FIELD_TITOLO_MAX_LENGTH
                        ])); ?>
                    </div>
                    <?php if ($offunusedfields) { ?>
                        <div class="col-xs-12">
                            <?= $form->field($model, 'descrizione_breve')->textarea(['maxlength' => 160, 'rows' => 3, 'placeholder' => AmosDocumenti::t('amosdocumenti', 'Descrizione sintetica')])->label(AmosDocumenti::t('amosdocumenti', 'descrizione_breve')) ?>
                        </div>
                    <?php } ?>
                    <div class="col-xs-12">
                        <?php if (!$isFolder && !$offunusedfields) { ?>
                            <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_subtitle_field_placeholder')])->hint(AmosDocumenti::t('amosdocumenti', '#documents_subtitle_field_hint')) ?>
                        <?php } ?>
                    </div>

                    <?php $categories = DocumentsUtility::getDocumentiCategorie()->orderBy('titolo')->all();
                    $hideUnusedFieldsCategory = $hideUnusedFields;
                    if (count($categories) != 1 && $offunusedfields) {
                        $hideUnusedFieldsCategory = '';
                    } ?>
                    <div style="<?= $hideUnusedFieldsCategory ?>">
                        <!-- AGID FIELD -->
                        <?php if ($enableAgid) : ?>
                            <div class="col-xs-12">
                                <?=
                                $form->field($model, 'documenti_agid_content_type_id')->widget(Select::classname(), [
                                    'data' => ArrayHelper::map(\open20\amos\documenti\models\base\DocumentiAgidContentType::find()->asArray()->all(), 'id', 'name'),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => [
                                        'id' => 'documenti_agid_content_type_id',
                                        'multiple' => false,
                                        'placeholder' => 'Seleziona ...',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(AmosDocumenti::t('amosdocumenti', 'documenti_agid_content_type_id'));
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- AGID FIELD -->
                        <?php if ($enableAgid) : ?>
                            <div class="col-xs-12">
                                <?=
                                $form->field($model, 'documenti_agid_type_id')->widget(Select::classname(), [
                                    'data' => ArrayHelper::map(\open20\amos\documenti\models\base\DocumentiAgidType::find()->asArray()->all(), 'id', 'name'),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => [
                                        'id' => 'documenti_agid_type_id',
                                        'multiple' => false,
                                        'placeholder' => 'Seleziona ...',
                                        "value" => !empty($model) ? $model->documenti_agid_type_id : null
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(AmosDocumenti::t('amosdocumenti', 'documenti_agid_type_id'));
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!$isFolder && $enableCategories) : ?>
                            <div class="col-xs-12">
                                <?php
                                if (count($categories) == 1 && $model->isNewRecord) {
                                    $firstCategory = array_pop($categories);
                                    $model->documenti_categorie_id = $firstCategory->id;                                  
                                }
                                ?>
                                <?= $form->field($model, 'documenti_categorie_id')->widget(Select::className(), [
                                    'options' => [
                                        'placeholder' => AmosDocumenti::t('amosdocumenti', 'Digita il nome della categoria'),
                                        'id' => 'documenti_categorie_id-id',
                                        'disabled' => false
                                    ],
                                    'data' => ArrayHelper::map(DocumentsUtility::getDocumentiCategorie()->orderBy('titolo')->all(), 'id', 'titolo'),
                                    'pluginEvents' => [
                                        "change" => "function() {

                                            var id = $(this).val();
                                            var from_date = $('#documenti-data_pubblicazione').val();
                                            
                                            $.post('/documenti/documenti/get-category-annual-archiving',{id:id},function(data){
                                                $('#documenti-enable_annual_archiving').bootstrapSwitch('state', data.value);
                                            },'json');
                                            
                                            var enable_countdown = $('#documenti-enable_countdown_unpublishing').bootstrapSwitch('state');
                                            
                                            if(enable_countdown){
                                                $.post('/documenti/documenti/get-unpublishing-date',{id:id,from:from_date},function(data){
                                                    if(data.response){            

                                                        $('#documenti-data_rimozione-disp').val(data.value_format);
                                                        $('#documenti-data_rimozione').val(data.value);
                                                        var hint = $('.field-documenti-data_rimozione').find('.help-block span').attr('data-original-title');                  
                                                        var new_hint = hint.replace(/\d+/, data.years);
                                                        $('.field-documenti-data_rimozione').find('.help-block span').attr('data-original-title',new_hint);

                                                    }
                                                },'json');
                                            }

                                        }",
                                    ],
                                ]); ?>
                            </div>
                            <div class="col-xs-12 ">
                                <?= ($model->version) ? $form->field($model, 'version')->textInput(['disabled' => true]) : ''; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


            </div>

            <div style="<?= $hideUnusedFields ?>">


                <?php if (!$isFolder) { ?>

                    <!--Testi-->
                    <div class="col-xs-12 section-form">
                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Testi') ?></h2>
                        <div class="row">

                            <!-- AGID FIELD -->
                            <?php if ($enableAgid) : ?>
                                <div class="col-xs-12">
                                    <?=
                                    $form->field($model, 'object')->widget(TextEditorWidget::className(), [
                                        'clientOptions' => [
                                            'lang' => substr(Yii::$app->language, 0, 2),
                                            'plugins' => [
                                                "paste link",
                                            ],
                                            'toolbar' => $RteToolbar,
                                        ],
                                    ])->label(AmosDocumenti::t('amosdocumenti', 'object'));
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!$offunusedfields) { ?>
                                <div class="col-xs-12">
                                    <?= $form->field($model, 'descrizione_breve')->textarea(['maxlength' => 160, 'rows' => 3, 'placeholder' => AmosDocumenti::t('amosdocumenti', 'Descrizione sintetica')])->label(AmosDocumenti::t('amosdocumenti', 'descrizione_breve')) ?>
                                </div>
                            <?php } ?>
                            <!-- AGID FIELD -->
                            <?php if ($enableAgid) : ?>
                                <div class="col-xs-12">
                                    <?=
                                    $form->field($model, 'extended_description')->widget(TextEditorWidget::className(), [
                                        'clientOptions' => [
                                            'lang' => substr(Yii::$app->language, 0, 2),
                                            'plugins' => [
                                                "paste link",
                                            ],
                                            'toolbar' => $RteToolbar,
                                        ],
                                    ])->label(AmosDocumenti::t('amosdocumenti', 'extended_description'));
                                    ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php } ?>


                <!--Referenti: AGID-->
                <?php if ($enableAgid) : ?>
                    <div class="col-xs-12 section-form">
                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Referenti') ?></h2>
                        <div class="row">
                            <!-- AGID FIELD -->

                            <div class="col-xs-12 ">
                                <?=
                                $form->field($model, 'author')->textarea([
                                    'rows' => '3',
                                    'maxlength' => true,
                                    'placeholder' => AmosDocumenti::t('amosdocumenti', 'author'),
                                ])->label(AmosDocumenti::t('amosdocumenti', 'author'))
                                ?>
                            </div>

                            <!-- AGID FIELD -->

                            <div class="col-xs-12">
                                <?=
                                $form->field($model, 'agid_organizational_unit_content_type_office_id')->widget(Select::classname(), [
                                    'data' => ArrayHelper::map(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()->orderBy('name')
                                        /*->andWhere(['agid_organizational_unit_content_type_id' =>
                                                AgidOrganizationalUnitContentType::find()->select('id')->andWhere(['like', 'name', 'Uffici'])->one()->id
                                            ])->andWhere(['deleted_at' =>  null])*/
                                        ->asArray()->all(), 'id', 'name'),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => [
                                        'id' => 'agid_organizational_unit_content_type_office_id',
                                        'multiple' => false,
                                        'placeholder' => 'Seleziona ...',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(AmosDocumenti::t('amosdocumenti', 'agid_organizational_unit_content_type_office_id'));
                                ?>
                            </div>

                            <!-- AGID FIELD -->

                            <div class="col-xs-12">
                                <?=
                                $form->field($model, 'agid_organizational_unit_content_type_area_id')->widget(Select::classname(), [
                                    'data' => ArrayHelper::map(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()->orderBy('name')
                                        /*->andWhere(['agid_organizational_unit_content_type_id' =>
                                                AgidOrganizationalUnitContentType::find()->select('id')->andWhere(['like', 'name', 'Aree amministrative'])->one()->id
                                            ])->andWhere(['deleted_at' =>  null])*/
                                        ->asArray()->all(), 'id', 'name'),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => [
                                        'id' => 'agid_organizational_unit_content_type_area_id',
                                        'multiple' => false,
                                        'placeholder' => 'Seleziona ...',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(AmosDocumenti::t('amosdocumenti', 'agid_organizational_unit_content_type_area_id'));
                                ?>
                            </div>


                        </div>


                    </div>
                <?php endif; ?>


                <!--Date: AGID-->
                <?php if ($enableAgid) : ?>
                    <div class="col-xs-12 section-form">
                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Date') ?></h2>
                        <div class="row">
                            <!-- AGID FIELD -->

                            <div class="col-md-6 col-xs-12 ">
                                <?=
                                $form->field($model, 'start_date')->widget(DateControl::classname(), [])
                                    ->label(Yii::t('amosdocumenti', 'Data inizio'));
                                ?>
                            </div>

                            <!-- AGID FIELD -->

                            <div class="col-md-6 col-xs-12">
                                <?=
                                $form->field($model, 'end_date')->widget(DateControl::classname(), [])
                                    ->label(Yii::t('amosdocumenti', 'Data fine'));
                                ?>
                            </div>

                            <!-- AGID FIELD -->

                            <div class="col-xs-12">
                                <?=
                                $form->field($model, 'dates_and_intermediate_stages')->widget(TextEditorWidget::className(), [
                                    'clientOptions' => [
                                        'lang' => substr(Yii::$app->language, 0, 2),
                                        'plugins' => [
                                            "paste link",
                                        ],
                                        'toolbar' => $RteToolbar,
                                    ],
                                ])->label(AmosDocumenti::t('amosdocumenti', 'dates_and_intermediate_stages'));
                                ?>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>

                <!--Protocollo: AGID-->
                <?php if ($enableAgid) : ?>
                    <div class="col-xs-12 section-form">
                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Protocollo') ?></h2>
                        <div class="row">
                            <!-- AGID FIELD -->

                            <div class="col-xs-12 ">
                                <?=
                                $form->field($model, 'protocol')->textInput([
                                    'maxlength' => true,
                                    'placeholder' => AmosDocumenti::t('amosdocumenti', 'Inserisci il protocollo')
                                ])
                                    // ->hint(AmosDocumenti::t('amosdocumenti', 'protocol'))
                                    ->label(AmosDocumenti::t('amosdocumenti', 'protocol'));
                                ?>
                            </div>


                            <!-- AGID FIELD -->

                            <div class="col-xs-12 ">
                                <?=
                                $form->field($model, 'protocol_date')->widget(DateControl::classname(), [])
                                    ->label(Yii::t('amosdocumenti', 'Data protocollo'));
                                ?>
                            </div>


                        </div>
                    </div>
                <?php endif; ?>


                <?php if (!$isFolder) { ?>
                    <!--altre informazioni -->
                    <div class="col-xs-12 section-form">
                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', 'Altre informazioni') ?></h2>
                        <div class="row">
                            <!-- AGID FIELD -->
                            <?php if ($enableAgid) : ?>
                                <div class="col-xs-12 ">
                                    <?=
                                    $form->field($model, 'distribution_license')->textInput([
                                        'maxlength' => true,
                                        'placeholder' => AmosDocumenti::t('amosdocumenti', 'distribution_license')
                                    ])
                                        ->label(AmosDocumenti::t('amosdocumenti', 'distribution_license'))
                                    ?>
                                </div>
                            <?php endif; ?>

                            <!-- AGID FIELD -->
                            <?php if ($enableAgid) : ?>
                                <div class="col-xs-12">
                                    <?=
                                    $form->field($model, 'further_information')->widget(TextEditorWidget::className(), [
                                        'clientOptions' => [
                                            'lang' => substr(Yii::$app->language, 0, 2),
                                            'plugins' => [
                                                "paste link",
                                            ],
                                            'toolbar' => $RteToolbar,
                                        ],
                                    ])->label(AmosDocumenti::t('amosdocumenti', 'further_information'));
                                    ?>
                                </div>
                            <?php endif; ?>
                            <!-- AGID FIELD -->
                            <?php if ($enableAgid) : ?>
                                <div class="col-xs-12">
                                    <?=
                                    $form->field($model, 'regulatory_requirements')->widget(TextEditorWidget::className(), [
                                        'clientOptions' => [
                                            'lang' => substr(Yii::$app->language, 0, 2),
                                            'plugins' => [
                                                "paste link",
                                            ],
                                            'toolbar' => $RteToolbar,
                                        ],
                                    ])->label(AmosDocumenti::t('amosdocumenti', 'regulatory_requirements'));
                                    ?>
                                </div>
                            <?php endif; ?>
                            <!-- AGID FIELD -->
                            <?php if ($enableAgid) : ?>
                                <div class="col-xs-12 ">
                                    <?=
                                    $form->field($model, 'help_box')->widget(TextEditorWidget::className(), [
                                        'clientOptions' => [
                                            'lang' => substr(Yii::$app->language, 0, 2),
                                            'plugins' => [
                                                "paste link",
                                            ],
                                            'toolbar' => $RteToolbar,
                                        ],
                                    ])->label(AmosDocumenti::t('amosdocumenti', 'help_box'));
                                    ?>
                                </div>
                            <?php endif; ?>
                            <!-- AGID FIELD -->
                            <?php if (!$enableAgid) : ?>

                                <?php if (!$isFolder) : ?>
                                    <div class="col-xs-12 ">
                                        <?= $form->field($model, 'descrizione')->widget(TextEditorWidget::className(), [
                                            'clientOptions' => [
                                                'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_description_field_placeholder'),
                                                'lang' => substr(Yii::$app->language, 0, 2),
                                                'plugins' => [
                                                    "paste link",
                                                ],
                                                'toolbar' => $RteToolbar,
                                            ],
                                        ]) ?>
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>
                        </div>
                    </div>
                <?php } ?>


                <!--modalità pubblicazione-->
                <?php
                $showReceiverSection = false;

                $moduleCwh = \Yii::$app->getModule('cwh');
                isset($moduleCwh) ? $showReceiverSection = true : null;

                $moduleTag = \Yii::$app->getModule('tag');
                isset($moduleTag) ? $showReceiverSection = true : null;
                $hidden = '';
                if (Yii::$app->getModule('documenti')->hideInterestArea == true) {
                    $hidden = 'hidden';
                }

                ?>
                <?php if ($showReceiverSection) : ?>
                    <div class="col-xs-12 section-form" <?= $hidden ?>>
                        <div class="section-modalita-pubblicazione">
                            <?= Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#tags_title'), ['class' => 'subtitle-form']) ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?=
                                    \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                                        'model' => $model,
                                        'moduleCwh' => $moduleCwh,
                                        'scope' => $scope,
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!--opzioni visibilità-->
                <?php if (Yii::$app->user->can('DOCUMENTI_PUBLISHER_FRONTEND')): ?>
                
                    <div class="col-xs-12 section-form">
                        <div class="row">
                            <?php if (Yii::$app->getModule('documenti')->params['site_publish_enabled']):
                                if ($model->isNewRecord)
                                    $model->primo_piano = 0;

                            ?>
                                <div class="col-md-6 col-xs-12">
                                    <?= $form->field($model, 'primo_piano')->widget(Select::class, [
                                        'auto_fill' => true,
                                        'data' => [
                                            '0' => AmosDocumenti::t('amosdocumenti', '#only_registered_users'),
                                            '1' => AmosDocumenti::t('amosdocumenti', '#all_users'),
                                        ],
                                        'options' => [
                                            'value' => (isset($enableAgid) && true == $enableAgid) ? 1 : $model->primo_piano,
        //                                    'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                                            'disabled' => false,
                                            'tabindex' => 0,
                                            'onchange' => '
                                                        if($(this).val() == 1) $(\'#documenti-in_evidenza\').prop(\'disabled\', false);
                                                        if($(this).val() == 0) {
                                                            $(\'#documenti-in_evidenza\').prop(\'disabled\', true);
                                                            $(\'#documenti-in_evidenza\').val(0);
                                                        }
                                                        ',
                                        ]
                                        ])->hint($isFolder ? AmosDocumenti::t('amosdocumenti', '#primo_piano_hint_folder') : AmosDocumenti::t('amosdocumenti', '#primo_piano_hint'));
                                    ?>
                                </div>    
                            <?php endif; ?>

                            <?php if (Yii::$app->getModule('documenti')->params['site_featured_enabled']): ?>
                                <div class="col-md-6 col-xs-12">
                                    <?= $form->field($model, 'in_evidenza')->widget(Select::class, [
                                        'auto_fill' => true,
                                        'data' => [
                                            '0' => 'No',
                                            '1' => 'Si',
                                        ],
                                        'options' => [
                                            'tabindex' => 0,
                                            'value' => (isset($enableAgid) && true == $enableAgid) ? 1 : $model->in_evidenza,
                                            //'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                                            'disabled' => (isset($enableAgid) && true == $enableAgid) ? false : ($model->primo_piano == 1 ? false : true)
                                        ]
                                    ])->hint($isFolder ? AmosDocumenti::t('amosdocumenti', '#evidenza_hint_folder') : AmosDocumenti::t('amosdocumenti', '#evidenza_hint'));
                                    ?>
                                </div>                     
                            <?php endif; ?>
                        </div>
                    </div>
                        
                <?php endif; ?>

                <?php if($showSectionScheduledSettings) : ?>
                    <!--pubblicazione programmata-->
                    <div class="col-xs-12 section-form" style="<?= $hideUnusedFields ?>">

                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', '#scheduled_settings') ?></h2>

                        <?php if ($module->hidePubblicationDate == false): ?>
                            <div class="row">

                                <?php
                                    $endPublicationDateHint = ($model->is_folder ?
                                        AmosDocumenti::t('amosdocumenti', '#folder_end_publication_date_hint') :
                                        ($module->enableCountdownUnpublishing ? AmosDocumenti::t('amosdocumenti', '#end_publication_date_hint',['tooltipDataRimozione' => '<span class="mdi mdi-clock-time-eight" title="' . AmosDocumenti::t('amosdocumenti', '#tooltip_end_publication_date',['years'=>$model->getUnpublishingYears()]) . '" data-toggle="tooltip"></span>']) : AmosDocumenti::t('amosdocumenti', '#end_publication_date_hint', ['tooltipDataRimozione' => '']))
                                    );
                                    $tooltipDataPubblicazione = ($model->is_folder ?
                                        '<span class="mdi mdi-information-outline" title="' . AmosDocumenti::t('amosdocumenti', '#tooltip_folder_start_publication_date') . '" data-toggle="tooltip"></span>' :
                                        '<span class="mdi mdi-information-outline" title="' . AmosDocumenti::t('amosdocumenti', '#tooltip_start_publication_date') . '" data-toggle="tooltip"></span>'
                                    );
                                    $startPublicationDateHint = ($model->is_folder ?
                                        AmosDocumenti::t('amosdocumenti', '#folder_start_publication_date_hint', ['tooltipDataPubblicazione' => $tooltipDataPubblicazione]) :
                                        AmosDocumenti::t('amosdocumenti', '#start_publication_date_hint', ['tooltipDataPubblicazione' => $tooltipDataPubblicazione])
                                    );

                                    if($documentsModule->dateFormatDocumenti === DateControl::FORMAT_DATE) {
                                        $dateFormat = 'Y-MM-d';
                                    } else {
                                        $dateFormat = 'Y-MM-d HH:mm:ss';
                                    }
                                    if(!$model->isNewRecord) {
                                        $model->data_rimozione = Yii::$app->getFormatter()->asDateTime($model->data_rimozione, $dateFormat);
                                        $model->data_pubblicazione = Yii::$app->getFormatter()->asDateTime($model->data_pubblicazione, $dateFormat);
                                    }
                                    if (in_array($model->data_rimozione, ['9999-12-31', '9999-12-31 00:00:00'])) {
                                        $model->data_rimozione = null;
                                    }
                                ?>

                                <div class="col-md-6 col-xs-12">
                                    <?= $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
                                            'type' => $documentsModule->dateFormatDocumenti,
                                            'widgetOptions' => [
                                                'options' => [
                                                    'placeholder' => \Yii::t('amosdocumenti', 'Pubblicazione immediata'),
                                                ],
                                                'pluginEvents' => [
                                                    "changeDate" => "function () { validateDatePubblicazioneFields(); }",
                                                ],
                                            ],
                                        ])->hint($startPublicationDateHint);
                                    ?>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <?= $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
                                            'type' => $documentsModule->dateFormatDocumenti,
                                            'widgetOptions' => [
                                                'options' => [
                                                    'placeholder' => \Yii::t('amosdocumenti', 'Sempre pubblica'),
                                                ],
                                                'pluginEvents' => [
                                                    "changeDate" => "function () { validateDatePubblicazioneFields(); $('#doc-form').yiiActiveForm('validateAttribute', 'documenti-date_start_archiving');}",
                                                ],
                                            ]
                                        ])->hint($endPublicationDateHint);
                                    ?>
                                </div>

                            </div>
                        <?php endif; ?>

                        <div class="row">

                            <?php if($enableCountdownUnpublishing && !$isFolder): ?>
                                <div class="col-md-6 col-xs-12 d-flex">

                                    <?= Html::tag('label',AmosDocumenti::t('amosdocumenti', '#count_down_unpublishing_document'),['class'=>'control-label']) ?>

                                    <?= $form->field($model, 'enable_countdown_unpublishing')->widget(SwitchInput::class, [
                                            'options'=>['style'=>'width:100px'],
                                            'pluginOptions' => [
                                                    'size' => 'medium',
                                                    'onColor' => 'success',
                                                    'offColor' => 'danger',
                                                    'onText' => AmosDocumenti::t('amosdocumenti','#comments_ok'),
                                                    'offText' => AmosDocumenti::t('amosdocumenti','#comments_no'),
                                            ],
                                            'pluginEvents'=>[
                                                "switchChange.bootstrapSwitch" => "function(event,state) {
    
                                                    if(state){
                                                        $('.field-documenti-data_rimozione').find('.help-block span').show();
    
                                                        var id = $('#documenti_categorie_id-id').val();
                                                        var from_date = $('#documenti-data_pubblicazione').val();
                                                        $.post('/documenti/documenti/get-unpublishing-date',{id:id,from:from_date},function(data){
                                                            if(data.response){            
    
                                                                $('#documenti-data_rimozione-disp').val(data.value_format);
                                                                $('#documenti-data_rimozione').val(data.value);
                                                                var hint = $('.field-documenti-data_rimozione').find('.help-block span').attr('data-original-title');                  
                                                                var new_hint = hint.replace(/\d+/, data.years);
                                                                $('.field-documenti-data_rimozione').find('.help-block span').attr('data-original-title',new_hint);
    
                                                            }
                                                        },'json');
                                                    }else{
                                                         $('.field-documenti-data_rimozione').find('.help-block span').hide();
                                                    }
    
                                                }",
                                            ],
                                        ])->label(false);
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if($enableAnnualArchiving && !$isFolder): ?>
                                <div class="col-md-6 col-xs-12 d-flex">

                                    <?= Html::tag('label',AmosDocumenti::t('amosdocumenti', '#annual_archiving_document'),['class'=>'control-label']) ?>

                                    <?= $form->field($model, 'enable_annual_archiving')->widget(SwitchInput::class, [
                                            'options'=>['style'=>'width:100px'],
                                            'pluginOptions' => [
                                                    'size' => 'medium',
                                                    'onColor' => 'success',
                                                    'offColor' => 'danger',
                                                    'onText' => AmosDocumenti::t('amosdocumenti','#comments_ok'),
                                                    'offText' => AmosDocumenti::t('amosdocumenti','#comments_no'),
                                            ],
                                            'pluginEvents'=>[
                                                "switchChange.bootstrapSwitch" => 'function(event,state) {
    
                                                    if(state){
                                                        $(".archiving-options").show();
                                                    }else{
                                                        $(".archiving-options").hide();
                                                    }
    
                                                }',
                                            ],
                                        ])->label(false);
                                    ?>
                                </div>
                            <?php endif; ?>

                        </div>

                    </div>
                <?php endif; ?>

                <!--opzioni archiviazione-->
                <?php if($enableAnnualArchiving && !$isFolder): ?>
                    <div class="col-xs-12 section-form archiving-options" style="display:<?= $model->enable_annual_archiving ? 'block' : 'none'?>">

                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', '#archiving_options') ?></h2>

                        <div class="row">

                            <div class="col-md-4 col-xs-12">

                                <?= $form->field($model, 'year')->textInput(['maxlength' => true])->hint(AmosDocumenti::t('amosdocumenti', '#year_hint')) ?>
                                
                            </div>

                            <div class="col-md-4 col-xs-12">

                                <?= $form->field($model, 'date_start_archiving')->widget(DateControl::className(), [
                                        'type' => DateControl::FORMAT_DATE,
                                        'widgetOptions' => [
                                            'options' => [
                                                'placeholder' => AmosDocumenti::t('amosdocumenti', '#now'),
                                            ],
                                            'pluginEvents' => [

                                            ],
                                        ]
                                    ])->hint('')
                                ?>
                                
                            </div>

                            <div class="col-md-4 col-xs-12">

                                <?= $form->field($model, 'stop_archiving')->widget(SwitchInput::class, [
                                        'options'=>['style'=>'width:100px'],
                                        'pluginOptions' => [
                                                'size' => 'medium',
                                                'onColor' => 'success',
                                                'offColor' => 'danger',
                                                'onText' => AmosDocumenti::t('amosdocumenti','#comments_ok'),
                                                'offText' => AmosDocumenti::t('amosdocumenti','#comments_no'),
                                        ],
                                        'pluginEvents'=>[
                                            "switchChange.bootstrapSwitch" => 'function(event,state) {

                                                if(state){

                                                }else{

                                                }

                                            }',
                                        ],
                                    ])->hint(AmosDocumenti::t('amosdocumenti', '#stop_archiving_hint'));
                                ?>
                                
                            </div>

                        </div>

                    </div>
                <?php endif; ?>
                
                <!--opzioni commento-->
                <?php if(!$isFolder && $commentsEnabled) : ?>
                    <div class="col-xs-12 section-form">
                        <h2 class="subtitle-form"><?= AmosDocumenti::t('amosdocumenti', '#options') ?></h2>
                        <div class="row">

                                <?php $model->comments_enabled = '1'; //default enable comment
                                if($commentsEnabled) : ?>

                                    <div class="col-md-6 col-xs-12">

                                        <?= $form->field($model, 'comments_enabled')->widget(SwitchInput::class, [
                                            'options'=>['style'=>'width:100px'],
                                            'pluginOptions' => [
                                                    'size' => 'medium',
                                                    'onColor' => 'success',
                                                    'offColor' => 'danger',
                                                    'onText' => AmosDocumenti::t('amosdocumenti','#comments_ok'),
                                                    'offText' => AmosDocumenti::t('amosdocumenti','#comments_no'),
                                            ],
                                            'pluginEvents'=>[
                                                "switchChange.bootstrapSwitch" => 'function(event,state) {
    
                                                    if(state){
    
                                                    }else{
    
                                                    }
    
                                                }',
                                            ],
                                            ]);
                                        ?>

                                    </div>

                                <?php else: ?>
                                    <?= $form->field($model, 'comments_enabled')->hiddenInput()->label(false); ?>
                                <?php endif; ?>

                            <?php if ($moduleNotify && !empty($moduleNotify->enableNotificationContentLanguage) && $moduleNotify->enableNotificationContentLanguage): ?>
                            <div class="col-xs-12">
                                <?= \open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget::widget(['model' => $model]) ?>
                            </div>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <div class="row">
                <div class="col-md-6 col-xs-12 ">
                    <?= $this->render('boxes/box_custom_fields_end', ['form' => $form, 'model' => $model]); ?>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 section-form">
                <div style="<?= $hideUnusedFields ?>">

                    <?php if (isset($moduleSeo)) : ?>
                        <div class="<?= $hideSeoModuleClass ?>">
                            <?= AccordionWidget::widget([
                                'items' => [
                                    [
                                        'header' => AmosDocumenti::t('amosdocumenti', 'Search Engine Optimization'),
                                        'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                                            'contentModel' => $model,
                                        ]),
                                    ],
                                ],
                                'headerOptions' => ['tag' => 'h2'],
                                'options' => (Yii::$app->user->can('ADMIN') || Yii::$app->user->can('CAPOREDATTORECMS')) ? [] : ['style' => 'display:none;'],
                                'clientOptions' => [
                                    'collapsible' => true,
                                    'active' => 'false',
                                    'icons' => [
                                        'header' => 'ui-icon-amos mdi mdi-chevron-down',
                                        'activeHeader' => 'ui-icon-amos mdi mdi-chevron-up ',
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="col-xs-12">
                    <?= RequiredFieldsTipWidget::widget(['containerClasses' => 'note_asterisk']) ?>
                </div>

                <div class="col-xs-12">
                    <!-- MANCA EMAIL DI NOTIFICA TAB -->
                    <?php
                    if ($enableGroupNotification && !$model->is_folder) {
                        $emailNotify = '';
                        $emailNotify .= Html::tag('p', AmosDocumenti::t('amosdocumenti', '#email_notification_text1'));
                        $emailNotify .= Html::tag('p', AmosDocumenti::t('amosdocumenti', '#email_notification_text2'));

                        if (!empty($moduleGroups) && !empty($moduleCommunity) && !empty($moduleCwh)) {
                            $entityId = null;
                            $this->params['idUserprofileCommunity'] = [];

                            if (isset($moduleCommunity)) {
                                $dataProvider = new \yii\data\ActiveDataProvider([
                                    'query' => \open20\amos\groups\models\Groups::getGroupsByParent(),
                                ]);
                            } else {
                                $dataProvider = new \yii\data\ActiveDataProvider([
                                    'query' => \open20\amos\groups\models\Groups::find()->andWhere(0),
                                ]);
                            }

                            \yii\widgets\Pjax::begin(['id' => 'pjax-container', 'timeout' => 2000, 'clientOptions' => ['data-pjax-container' => 'grid-members']]);
                            $pjaxContent = \open20\amos\core\views\AmosGridView::widget([
                                'dataProvider' => $dataProviderProfiles,
                                'id' => 'grid-members',
                                'columns' => [
                                    'nomeCognome',
                                    [
                                        'class' => '\kartik\grid\CheckboxColumn',
                                        'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
                                        'name' => 'element-profiles',
                                        //  'checkboxOptions' => function ($model, $key, $index, $column) {
                                        //                                $idUserProfileComunity = $this->params['idUserprofileCommunity'];
                                        //                                return ['value' => $model->id,
                                        //                                    'checked' => true,
                                        //                                ];
                                        //                            }
                                    ],
                                ],

                            ]);
                            \yii\widgets\Pjax::end();

                            $emailNotify .= Html::tag(
                                'div',
                                Html::tag(
                                    'div',
                                    Html::tag('h2', AmosDocumenti::t('amosdocumenti', 'Utenti'), ['class' => 'subtitle-form']),
                                    ['class' => 'col-xs-12']
                                ) . $pjaxContent,
                                ['class' => 'col-xs-12 col-lg-6']
                            );

                            $emailNotify .= Html::tag(
                                'div',
                                Html::tag(
                                    'div',
                                    Html::tag('h2', AmosDocumenti::t('amosdocumenti', 'Gruppi'), ['class' => 'subtitle-form']),
                                    ['class' => 'col-xs-12']
                                ) .
                                \open20\amos\core\views\AmosGridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'columns' => [
                                        'name',
                                        'description',
                                        [
                                            'attribute' => 'numberGroupMembers',
                                            'label' => AmosDocumenti::t('amosdocumenti', '#number_group_members'),
                                        ],
                                        [
                                            'class' => '\kartik\grid\CheckboxColumn',
                                            'rowSelectedClass' => \kartik\grid\GridView::TYPE_SUCCESS,
                                            'name' => 'selection-groups',
                                            // you may configure additional properties here
                                        ],
                                    ],

                                ]),
                                ['class' => 'col-xs-12 col-lg-6']
                            );
                        }
                    }
                    /*echo AccordionWidget::widget([
                            'items' => [
                            [
                            'header' => AmosDocumenti::t('amosdocumenti', '#settings_email_notify'),
                            'content' => $emailNotify,
                            ]
                            ],
                            'headerOptions' => ['tag' => 'h2'],
                            'clientOptions' => [
                            'collapsible' => true,
                            'active' => 'false',
                            'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                            ],
                            ]);*/
                    ?>
                </div>
            </div>
            <?php
            $closeButtonText = ($enableVersioning && !$model->isNewRecord && $isNewVersion)
                ? AmosDocumenti::t('amosdocumenti', '#CANCEL_NEW_VERSION')
                : AmosDocumenti::t('amosdocumenti', 'Annulla');

            $statusToRenderToHide = $model->getStatusToRenderToHide();

            $daValidareDescription = $model->is_folder
                ? AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni la cartella in "richiesta di pubblicazione"')
                : AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni il documento in "richiesta di pubblicazione"');

            $validatoDescription = $model->is_folder
                ? AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni la cartella "pubblicato"')
                : AmosDocumenti::t('amosdocumenti', 'le modifiche e mantieni il documento "pubblicato"');

            $draftButtons = [];
            if ($disableStandardWorkflow == false) {
                $draftButtons = [
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE => [
                        'button' => Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Salva'), ['class' => 'btn btn-primary']),
                        'description' => $daValidareDescription,
                    ],
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO => [
                        'button' => Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Salva'), ['class' => 'btn btn-primary']),
                        'description' => $validatoDescription,
                    ],
                    'default' => [
                        'button' => Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Salva in bozza'), ['class' => 'btn btn-primary']),
                        'description' => AmosDocumenti::t('amosdocumenti', 'potrai richiedere la pubblicazione in seguito'),
                    ],
                ];
            }

            $enableDataConfirm = $enablePublishUnpublishFolder && !$model->is_folder;

            $closeButtonUrl = !empty(\Yii::$app->request->get('urlRedirect')) ? \Yii::$app->request->get('urlRedirect') : $appController->getFormCloseUrl($model);
            echo \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
                // parametri ereditati da verioni precedenti del widget WorkflowTransition
                'form' => $form,
                'model' => $model,
                'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
                'viewWidgetOnNewRecord' => true,
                'enableDataConfirm' => $enableDataConfirm,
                'closeButton' => Html::a($closeButtonText, $closeButtonUrl, ['class' => 'btn btn-outline-primary']),

                // fisso lo stato iniziale per generazione pulsanti e comportamenti
                // "fake" in fase di creazione (il record non e' ancora inserito nel db)
                'initialStatusName' => "BOZZA",
                'initialStatus' => $model->getWorkflowSource()->getWorkflow(Documenti::DOCUMENTI_WORKFLOW)->getInitialStatusId(),

                'statusToRender' => $statusToRenderToHide['statusToRender'],
                'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],

                'draftButtons' => $draftButtons,
            ]);
            ?>
        </div>
    </div>

<?php //echo Html::a(AmosDocumenti::t('amosdocumenti','#go_back'), \Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']);
?>
<?php if (!$enablePublishUnpublishFolder) { ?>
    <div>
        <?= $this->render('_modal_publish_folder', ['model' => $model]) ?>
    </div>
<?php } ?>
<?php ActiveForm::end(); ?>


<?php
$script = <<< JS
	$(document).ready(function(){
        var execBeforeSubmit = 1; // Per chiamare un solo evento beforesubmit
        
        $("#documenti_agid_content_type_id").ready(function(){
            if( $("#documenti_agid_content_type_id").val() && $("#documenti_agid_content_type_id").val().length != 0){
                var url = "/documenti/documenti/get-documenti-agid-type-by-content-type";
                var data = "documenti_agid_content_type_id=" + $("#documenti_agid_content_type_id").val() + "&documenti_agid_type_id=" + $("#documenti_agid_type_id").val();

                ajaxPostCall(url, data, selectOptionDocumentiAgidType);
            }
        });

        $("#documenti_agid_content_type_id").change(function(){

            $("#documenti_agid_type_id option").remove();
            $('#documenti_agid_type_id').append('<option value="">Seleziona ...</option>').trigger('change');

            var url = "/documenti/documenti/get-documenti-agid-type-by-content-type";
            var data = "documenti_agid_content_type_id=" + $("#documenti_agid_content_type_id").val() + "&documenti_agid_type_id=" + $("#documenti_agid_type_id").val();

            ajaxPostCall(url, data, selectOptionDocumentiAgidType);
        });
 
		
        if(typeof selected_file_ids !== 'undefined'){
                // Aggiunta del parametro nascosto per indicare al validatore che i file ci sono se caricati da databank (altrimenti non li vede)
                $('#doc-form').on('beforeSubmit', function(e) {
                        if(execBeforeSubmit) {
                                execBeforeSubmit = 0;
                                e.preventDefault();
                                if('documentMainFile' in selected_file_ids){
                                        $('#mainDocumentNumber').val(selected_file_ids['documentMainFile'].length);
                                }
                        }
                });
        }    

    });

    function selectOptionDocumentiAgidType(data){
        // remove old select options and set new select options
        $('#documenti_agid_type_id option').remove();
        $('#documenti_agid_type_id').append(JSON.parse(data));
    }

    function ajaxPostCall(url, data, functionExecute){
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: url,
            data: data,
            // contentType: "application/json; charset=utf-8",
            // dataType: "json",
            dataType: "html",
            success: function (data, textStatus, jqXHR) {
                functionExecute(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "/n" + thrownError + "/n" + xhr.responseText);
                /**
                * dettaglio errore
                * xhr.responseText
                */
                alert(xhr.status + " " + thrownError);
                result = xhr.status + " " + thrownError;
            }
        });
    }
JS;

$this->registerJs($script);
