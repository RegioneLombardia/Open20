<?php

/**
 * Lombardia Informatica S.p.A.
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
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\forms\editors\Select;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var yii\widgets\ActiveForm $form
 * @var array $scope
 */

/** @var \open20\amos\documenti\controllers\DocumentiController $appController */
$appController = Yii::$app->controller;
$isFolder = $appController->documentIsFolder($model);
$enableCategories = AmosDocumenti::instance()->enableCategories;
$enableVersioning = $appController->documentsModule->enableDocumentVersioning;
$isNewVersion = !empty(\Yii::$app->request->get('isNewVersion')) ? \Yii::$app->request->get('isNewVersion') : false;

$disableStandardWorkflow = $appController->documentsModule->disableStandardWorkflow;

$moduleGroups = Yii::$app->getModule('groups');
$moduleCommunity = Yii::$app->getModule('community');
$moduleCwh = Yii::$app->getModule('cwh');
$moduleNotify = \Yii::$app->getModule('notify');

$enableGroupNotification = AmosDocumenti::instance()->enableGroupNotification;

// AGID FIELDS ENABLE
$enableAgid = AmosDocumenti::instance()->enableAgid;

$primoPiano = '';
$inEvidenza = '';
$enableComments = '';

/** @var \open20\amos\comments\AmosComments $commentsModule */
$commentsModule = Yii::$app->getModule('comments');

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
    
}

/** @var \open20\amos\report\AmosReport $reportModule */
$reportModule = Yii::$app->getModule('report');
$viewReportWidgets = (!is_null($reportModule) && in_array($model->className(), $reportModule->modelsEnabled));

$reportFlagWidget = '';
if ($viewReportWidgets) {
    $reportFlagWidget = \open20\amos\report\widgets\ReportFlagWidget::widget([
        'model' => $model,
    ]);
}

//$_SESSION['upload_token'] = $_GET['oauthToken'];
//$GoogleDriveManager = new open20\amos\documenti\utility\GoogleDriveDocument(['model' => $model]);

//$adapter = $GoogleDriveManager->prepareAdapter();
//if(!empty($adapter)){
//    pr($adapter->listContents());
//}

//if ($_SERVER['REQUEST_METHOD'] == 'POST' && $GoogleDriveManager->client->getAccessToken()) {
//// We'll setup an empty 1MB file to upload.
//    DEFINE("TESTFILE", 'testfile-small.txt');
//    if (!file_exists(TESTFILE)) {
//        $fh = fopen(TESTFILE, 'w');
//        fseek($fh, 1024 * 1024);
//        fwrite($fh, "!", 1);
//        fclose($fh);
//    }
//// This is uploading a file directly, with no metadata associated.
//    $file = new Google_Service_Drive_DriveFile();
//    $result = $service->files->create(
//        $file,
//        array(
//            'data' => file_get_contents(TESTFILE),
//            'mimeType' => 'application/octet-stream',
//            'uploadType' => 'media'
//        )
//    );
//}

//$GoogleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager(['model' => $model, 'useServiceAccount' => true]);
//pr($GoogleDriveManager->getList('', true));

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


<?php 
/** CONTENT PAGE SCRIPTS */

$script = <<< JS

	$(document).ready(function(){

        $("#documenti_agid_content_type_id").ready(function(){

            if( $("#documenti_agid_content_type_id").val().length != 0){

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
?>

<div class="documenti-form">
    <?=$this->render('boxes/box_custom_fields_begin', ['form' => $form, 'model' => $model]);?>
    <div class="row">  
        <!--documenti e allegati-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form">Documenti e allegati</h2>
            <?=$this->render('boxes/box_custom_uploads_begin', [
                    'form' => $form,
                    'model' => $model,
            ]);?>
            <div class="row">
                <div class="col-md-6">
                    <?=\open20\amos\documenti\widgets\DocumentMainFileInputWidget::widget([
                        'model' => $model,
                        'form' => $form,
                        'isFolder' => $isFolder,
                    ]);?>
                </div>
                <?php if (!$isFolder): ?>
                    <div class=" col-md-6  col-xs-12 ">
                        <?=$form->field($model, 'documentAttachments')->widget(AttachmentsInput::classname(), [
                            'options' => [ // Options of the Kartik's FileInput widget
                                'multiple' => true, // If you want to allow multiple upload, default to false
                            ],
                            'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 100, // Client max files
                                'showPreview' => false,
                            ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'document_attachments'))->hint(AmosDocumenti::t('amosdocumenti', '#attachments_field_hint'))?>

                                            <?=AttachmentsList::widget([
                            'model' => $model,
                            'attribute' => 'documentAttachments',
                            'enableSort' => true,
                            'requireModalMoveFile' => AmosDocumenti::instance()->requireModalMoveFile
                        ])?>
                    </div>
                <?php endif;?>
            </div>
            
            <?=$this->render('boxes/box_custom_uploads_end', ['form' => $form, 'model' => $model]);?>
        </div>
		<!--nome-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form">Nome</h2>
            <div class="row">
                <div class="col-md-6">
                    <?=$this->render('boxes/box_custom_fields_begin', ['form' => $form, 'model' => $model]);?>
                    <?=$form->field($model, 'titolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_title_field_placeholder')])->hint(AmosDocumenti::t('amosdocumenti', '#documents_title_field_hint'))?>
                </div>
                <div class="col-md-6">
                    <?php if (!$isFolder){ ?>
                                <?=$form->field($model, 'sottotitolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_subtitle_field_placeholder')])->hint(AmosDocumenti::t('amosdocumenti', '#documents_subtitle_field_hint'))?>
                    <?php } ?>
                </div>
                <div class="col-md-6">
                    <?php if (!$isFolder){ ?>
                        <?=$form->field($model, 'descrizione_breve')->textarea(['maxlength' => 160, 'rows' => 3, 'placeholder' => AmosDocumenti::t('amosdocumenti', 'Descrizione sintetica')])->label(AmosDocumenti::t('amosdocumenti', 'descrizione_breve'))?>
                    <?php } ?>
                </div>
                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6 ">
                        <?=
                            $form->field($model, 'author')->textarea([
                                'rows' => '3',
                                'maxlength' => true, 
                                'placeholder' => AmosDocumenti::t('amosdocumenti', 'author'),
                            ])->label(AmosDocumenti::t('amosdocumenti', 'author'))
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'object')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'object'));
                        ?>
                    </div>
                <?php endif; ?>

            </div>            
        </div>

        <!--Tipologia e categoria-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form">Tipologia</h2>
            <div class="row">
                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'documenti_agid_content_type_id')->widget(Select::classname(), [
                                'data' => ArrayHelper::map(\open20\amos\documenti\models\base\DocumentiAgidContentType::findRedactor()->asArray()->all(), 'id', 'name'),
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
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
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

                <?php if (!$isFolder && $enableCategories): ?>
                    <div class="col-md-6">
                        <?=$form->field($model, 'documenti_categorie_id')->widget(Select2::className(), [
                            'options' => ['placeholder' => AmosDocumenti::t('amosdocumenti', 'Digita il nome della categoria'), 'id' => 'documenti_categorie_id-id', 'disabled' => false],
                            'data' => ArrayHelper::map(\open20\amos\documenti\utility\DocumentsUtility::getDocumentiCategorie()->orderBy('titolo')->all(), 'id', 'titolo'),
                        ]);?>
                    </div>    
                    <div class="col-md-6 ">
                        <?=($model->version) ? $form->field($model, 'version')->textInput(['disabled' => true]) : '';?>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <!--Altre informazioni-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form">Altre informazioni</h2>
            <div class="row">
                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'agid_organizational_unit_content_type_office_id')->widget(Select::classname(), [
                                'data' => ArrayHelper::map(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()->orderBy('name')
                                    /*->andWhere(['agid_organizational_unit_content_type_id' => 
                                        AgidOrganizationalUnitContentType::find()->select('id')->andWhere(['like', 'name', 'Uffici'])->one()->id
                                    ])->andWhere(['deleted_at' =>  null])*/->asArray()->all(), 'id', 'name'),
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
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'agid_organizational_unit_content_type_area_id')->widget(Select::classname(), [
                                'data' => ArrayHelper::map(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::find()->orderBy('name')
                                    /*->andWhere(['agid_organizational_unit_content_type_id' => 
                                        AgidOrganizationalUnitContentType::find()->select('id')->andWhere(['like', 'name', 'Aree amministrative'])->one()->id
                                    ])->andWhere(['deleted_at' =>  null])*/->asArray()->all(), 'id', 'name'),
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
                <?php endif; ?>

                <!-- < ?php if (!$isFolder): ?> -->
                <!-- <div class="col-md-6 ">
                        < ?=
                            $form->field($model, 'descrizione_breve')->textarea([
                                'maxlength' => true, 
                                'rows' => 3, 
                                'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_abstract_field_placeholder')])
                            // ->hint(AmosDocumenti::t('amosdocumenti', '#documents_abstract_field_hint'))
                            ->label(AmosDocumenti::t('amosdocumenti', 'Descrizione sintetica'));
                        ?>
                </div> -->
                <!-- < ?php endif;?> -->

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'extended_description')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'extended_description'));
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6 ">
                        <?=
                            $form->field($model, 'distribution_proscription')->textInput([
                                'maxlength' => true, 
                                'placeholder' => AmosDocumenti::t('amosdocumenti', 'distribution_proscription')])
                            ->label(AmosDocumenti::t('amosdocumenti', 'distribution_proscription'))
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!--altre informazioni 2-->

        <div class="col-xs-12 ">
            <div class="row">
                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6 ">
                        <?= 
                            $form->field($model, 'start_date')->widget(DateControl::classname(), [])
                                ->label(Yii::t('amosdocumenti', 'Data inizio')); 
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?= 
                            $form->field($model, 'end_date')->widget(DateControl::classname(), [])
                                ->label(Yii::t('amosdocumenti', 'Data fine')); 
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'dates_and_intermediate_stages')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'dates_and_intermediate_stages'));
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'further_information')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                                        
                                ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'further_information'));
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'regulatory_requirements')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'regulatory_requirements'));
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!--altre informazioni 3-->
        <div class="col-xs-12">
            <div class="row">
                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6 ">
                        <?=
                            $form->field($model, 'protocol')->textInput([
                                'maxlength' => true, 
                                'placeholder' => AmosDocumenti::t('amosdocumenti', 'Inserisci il protocollo')
                            ])
                            // ->hint(AmosDocumenti::t('amosdocumenti', 'protocol'))
                            ->label(AmosDocumenti::t('amosdocumenti', 'protocol')); 
                        ?>
                    </div>
                <?php endif; ?>

                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6 ">
                        <?= 
                            $form->field($model, 'protocol_date')->widget(DateControl::classname(), [])
                                ->label(Yii::t('amosdocumenti', 'Data protocollo')); 
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!--altre info 4-->
        <div class="col-xs-12">
            <div class="row">
                <!-- AGID FIELD -->
                <?php if( $enableAgid ) : ?>
                    <div class="col-md-6 ">
                        <?=
                            $form->field($model, 'help_box')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])->label(AmosDocumenti::t('amosdocumenti', 'help_box'));
                        ?>
                    </div>
                <?php endif; ?>

                <?php if( !$enableAgid ) : ?>

                    <?php if (!$isFolder): ?>
                        <div class="col-md-6 ">
                            <?= $form->field($model, 'descrizione')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                        'placeholder' => AmosDocumenti::t('amosdocumenti', '#documents_description_field_placeholder'),
                                        'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])?>
                        </div>
                    <?php endif;?>

                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row">

        <!--modalità pubblicazione-->
        <?php
            $showReceiverSection = false;

            $moduleCwh = \Yii::$app->getModule('cwh');
            isset($moduleCwh) ? $showReceiverSection = true : null;

            $moduleTag = \Yii::$app->getModule('tag');
            isset($moduleTag) ? $showReceiverSection = true : null;
        ?>
		<?php if ($showReceiverSection): ?>
            <div class="col-xs-12">
                <?=Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#tag'), ['class' => 'subtitle-form'])?>
                <div class="col-xs-12 receiver-section">
                    <?=
                        \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                            'model' => $model,
                            'moduleCwh' => $moduleCwh,
                            'scope' => $scope,
                        ]);
                    ?>
                </div>
            </div>
		<?php endif;?>

        <div class="col-xs-12">
            <div class="col-md-6 ">
		        <?=RequiredFieldsTipWidget::widget(['containerClasses' => 'col-xs-12 note_asterisk'])?>
            </div>
        </div>
        

			
	

    
   
      
       

		
    
                <div class="col-md-6 ">
                    <?=($model->version) ? $form->field($model, 'version')->textInput(['disabled' => true]) : '';?>
                </div>
            </div>
		    
            <div class="row">
                <div class="col-md-6 ">
                    <?=$this->render('boxes/box_custom_fields_end', ['form' => $form, 'model' => $model]);?>
                </div>
            </div>
		
        
        
        
        
		
    </div>
 
      


	<div class="row">
		<div class="col-xs-12">
			<?php
            if (\Yii::$app->user->can('DOCUMENTI_PUBLISHER_FRONTEND')):
                if (Yii::$app->getModule('documenti')->params['site_publish_enabled']): ?>

				<?php
                    $primoPiano = '';
                    $primoPiano = Html::tag('div',
                        $form->field($model, 'primo_piano')->dropDownList([
                            '0' => 'No',
                            '1' => 'Si',
                        ], 
                        [
                            'value' => (isset($enableAgid) && true == $enableAgid) ? 1 : null,
                            'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                            'disabled' => false,
                            'onchange' => '
                                                if($(this).val() == 1) $(\'#documenti-in_evidenza\').prop(\'disabled\', false);
                                                if($(this).val() == 0) {
                                                    $(\'#documenti-in_evidenza\').prop(\'disabled\', true);
                                                    $(\'#documenti-in_evidenza\').val(0);
                                                }
                                                ',
                        ]),
                        ['class' => 'col-md-6']
                    );
                ?>
				<?php endif;?>

			<?php if (Yii::$app->getModule('documenti')->params['site_featured_enabled']): ?>
			<?php
            $inEvidenza = '';
            $inEvidenza = Html::tag('div',
                $form->field($model, 'in_evidenza')->dropDownList([
                    '0' => 'No',
                    '1' => 'Si',
                ], [
                    'value' => (isset($enableAgid) && true == $enableAgid) ? 1 : null,
                    'prompt' => AmosDocumenti::t('amosdocumenti', 'Seleziona...'),
                    'disabled' =>  (isset($enableAgid) && true == $enableAgid) ? false : ($model->primo_piano == 1 ? false : true)
                ]),
                ['class' => 'col-md-6']);
            ?>
			<?php endif;?>
			<?php endif;?>
			<?php
            $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());
            $publicationDate = '';
            if ($module->hidePubblicationDate == false) {
                $endPublicationDateHint = ($model->is_folder ?
                    AmosDocumenti::t('amosdocumenti', '#folder_end_publication_date_hint') :
                    AmosDocumenti::t('amosdocumenti', '#end_publication_date_hint'));
                $publicationDate = Html::tag('div',
                    $form->field($model, 'data_pubblicazione')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE])/*->hint(AmosDocumenti::t('amosdocumenti', '#start_publication_date_hint'))*/,
                    ['class' => 'col-md-4 col-xs-12']) .
                Html::tag('div',
                    $form->field($model, 'data_rimozione')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE])->hint($endPublicationDateHint),
                    ['class' => 'col-md-4 col-xs-12']);
            }
            ?>

			<?php if (!$isFolder) {
                $model->comments_enabled = '1'; //default enable comment
                if (!is_null($commentsModule) && in_array($model->className(), $commentsModule->modelsEnabled)) {
                    $enableComments = Html::tag('div',
                        $form->field($model, 'comments_enabled')->inline()->radioList(
                            [
                                '1' => AmosDocumenti::t('amosdocumenti', '#comments_ok'),
                                '0' => AmosDocumenti::t('amosdocumenti', '#comments_no'),

                            ],
                            ['class' => 'comment-choice'])
                        , ['class' => 'col-md-4 col-xs-12']);
                } else {
                    $enableComments = $form->field($model, 'comments_enabled')->hiddenInput()->label(false);
                }
            }
            ?>
			<?php if ($moduleNotify && !empty($moduleNotify->enableNotificationContentLanguage) && $moduleNotify->enableNotificationContentLanguage) {?>
			<?php
            $contentLanguage = "<div class=\"col-xs-6 nop\">" . \open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget::widget(['model' => $model]) . "</div>"
                ?>
                        <?php }?>

                        <?=AccordionWidget::widget([
                'items' => [
                    [
                        'header' => AmosDocumenti::t('amosdocumenti', '#settings_optional'),
                        'content' => $publicationDate . $enableComments . '<div class="clearfix"></div>' . $primoPiano . $inEvidenza . $contentLanguage,
                    ],
                ],
                'headerOptions' => ['tag' => 'h2'],
                'clientOptions' => [
                    'collapsible' => true,
                    'active' => 'false',
                    'icons' => [
                        'header' => 'ui-icon-amos am am-plus-square',
                        'activeHeader' => 'ui-icon-amos am am-minus-square',
                    ],
                ],
            ]);
            ?>

			<?php
            $moduleSeo = \Yii::$app->getModule('seo');
            if (isset($moduleSeo)): ?>
                        <?=AccordionWidget::widget([
                'items' => [
                    [
                        'header' => AmosDocumenti::t('amosdocumenti', '#settings_seo_title'),
                        'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                            'contentModel' => $model,
                        ]),
                    ],
                ],
                'headerOptions' => ['tag' => 'h2'],
                'options' => Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
                'clientOptions' => [
                    'collapsible' => true,
                    'active' => 'false',
                    'icons' => [
                        'header' => 'ui-icon-amos am am-plus-square',
                        'activeHeader' => 'ui-icon-amos am am-minus-square',
                    ],
                ],
            ]);
            ?>
			<?php endif;?>

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
//                            'checkboxOptions' => function ($model, $key, $index, $column) {
                    //                                $idUserProfileComunity = $this->params['idUserprofileCommunity'];
                    //                                return ['value' => $model->id,
                    //                                    'checked' => true,
                    //                                ];
                    //                            }
                ],
            ],

        ]);
        \yii\widgets\Pjax::end();

        $emailNotify .= Html::tag('div',
            Html::tag('div',
                Html::tag('h2', AmosDocumenti::t('amosdocumenti', 'Utenti'), ['class' => 'subtitle-form']),
                ['class' => 'col-xs-12']) . $pjaxContent,
            ['class' => 'col-xs-12 col-lg-6']);

        $emailNotify .= Html::tag('div',
            Html::tag('div',
                Html::tag('h2', AmosDocumenti::t('amosdocumenti', 'Gruppi'), ['class' => 'subtitle-form']),
                ['class' => 'col-xs-12']) .
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
            ['class' => 'col-xs-12 col-lg-6']);
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

echo \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([

    // parametri ereditati da verioni precedenti del widget WorkflowTransition
    'form' => $form,
    'model' => $model,
    'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
    'viewWidgetOnNewRecord' => true,

    'closeButton' => Html::a($closeButtonText, $appController->getFormCloseUrl($model), ['class' => 'btn btn-outline-primary']),

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
<?php //echo Html::a(AmosDocumenti::t('amosdocumenti','#go_back'), \Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']);?>
<?php ActiveForm::end();?>




