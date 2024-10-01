<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @open20/amos/collaborations/views/views
 */

use open20\amos\collaborations\models\CollaborationMarketKeywords;
use open20\amos\collaborations\models\CollaborationPartnershipType;
use open20\amos\collaborations\models\CollaborationPartnerTypeAndSize;
use open20\amos\collaborations\models\CollaborationStageOfDevelopment;
use open20\amos\collaborations\models\CollaborationTargetCountries;
use open20\amos\collaborations\models\CollaborationTechnologyKeywords;
use open20\amos\collaborations\models\CollaborationType;
use open20\amos\collaborations\models\CollaborationIprStatus;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\collaborations\utility\CollaborationProposalsUtility;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\WorkflowTransitionWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ArrayUtility;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\datecontrol\DateControl;
use Yii;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\collaborations\models\CollaborationProposals $model
 * @var yii\widgets\ActiveForm $form
 */

$js = <<<JS

    // Se collaboration_type_id già presente (in update) mostra i campi associati al valore selezionato
    let type = $('#collaborationproposals-collaboration_type_id').val();
    if(type == 1 || type == 3){
        $("#advantages").show();
    }else if(type == 2 || type == 4){
        $("#technicalSpecs").show();
    }else if(type == 5){
        $("#advantages").show();
        $("#technicalSpecs").show();
    }
    
    // Tag aree di interesse, visibile per guest e in evidenza
    // In update ...
    /*if($('#cwh-regola_pubblicazione').val() == 2) {
        $('#visible_to_guest').val(0).change();
            $('#visible_to_guest').prop('disabled', true);
            $('#in_evidence').val(0).change();
            $('#in_evidence').prop('disabled', true);
    }*/
    // On change ...
    /*$('#cwh-regola_pubblicazione').on('change', function(){
        if($('#cwh-regola_pubblicazione').val() == 2){
            $('#visible_to_guest').val(0).change();
            $('#visible_to_guest').prop('disabled', true);
            $('#in_evidence').val(0).change();
            $('#in_evidence').prop('disabled', true);
        }else{
            $('#visible_to_guest').prop('disabled', false);
        }
    });*/
    
    // IMMAGINE
    // Se viene inserita un'immagine mostra caption
    $('#collaborationproposals-image').on('change', function(){
        if(!($('#collaborationproposals-image').val())){
            $('#caption').hide();
        }else{
            $('#caption').fadeIn('fast');
        }
    });
    // In update se c'è un'anteprima dell'immagine mostra caption
    if($('.preview_image').is(':visible')){
        $('#caption').show();
    }
    // Se c'è un errore di validazione su caption
    if($('.form-group.field-collaborationproposals-caption.has-error').is(':visible')){
        $('#caption').show();
    }
    
    $('.deleteImageCrop.btn.btn-danger.btn-sm.btn-icon').on('click', function () {
        $('#caption').hide();
        $('#collaborationproposals-image').val('');
    });
    
    // DOCUMENTO (PDF)
    // In update se è già caricato un file mostra short_abstract
    if($('.attachments-list').is(':visible')){
        $('#short_abstract').show();
    }
    // Se c'è un errore di validazione su short_abstract
    if($('.form-group.field-collaborationproposals-short_abstract.has-error').is(':visible')){
        $('#short_abstract').show();
    }
    
    $('.attachments-list-delete').on('click', function () {
        $('#short_abstract').hide();
        $('.file-caption-name').val('');
    });
    
    // VIDEO TITLE
    // Se video_pitch è compilato mostra video_title
    $('#video_pitch').on('input', function (){
        if($('#video_pitch').val().length > 0){
            $('#video_title').fadeIn('fast')();
        }else{
            $('#video_title').fadeOut('fast')();
        }
    });
    // In update mostra video_title se video_pitch è compilato
    if($('#video_pitch').val().length > 0){
        $('#video_title').show();
    }


    $("#collaborationProposals-start_date0-disp").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("#collaborationProposals-end_date0-disp").on("keyup", function () {
  validateDatePubblicazioneFields();
});

$("#collaborationProposals-start_date0-disp").on("focusout", function () {
  validateDatePubblicazioneFields();
});

$("#collaborationProposals-end_date0-disp").on("focusout", function () {
  validateDatePubblicazioneFields();
});

window.validateDatePubblicazioneFields = function () {
  setTimeout(function () {
    $('.collaborations-aform').yiiActiveForm('validateAttribute', 'collaborations-start_date');
    $('.collaborations-aform').yiiActiveForm('validateAttribute', 'collaborations-end_date');
  }, 1000);
}

JS;

$this->registerJs($js);


$isArchived = CollaborationProposalsUtility::isArchived($model->id);

// SEO
$collaborationProposalsModule = \Yii::$app->getModule(\open20\amos\collaborations\Module::getModuleName());
$moduleSeo = \Yii::$app->getModule('seo');
$hideSeoModuleClass = $collaborationProposalsModule->hideSeoModule ? ' hidden' : '';
?>

<div class="collaboration-proposals-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'collaboration-proposals_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => 'collaborations-aform'
        ]
    ]); ?>

    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); 
    ?>
    <div class="clearfix"></div>
    <div>
        <!-- <- ?php if ($isArchived && !$model->isNewRecord) { ?>
            <div class="workflow-transition-state-descriptor-widget col-xs-12">
                <div class="message">
                    < ?= \Yii::t('amoscollaborations', 'Archiviata'); ?>
                </div>
            </div>
            <div class="text-uppercase text-danger">
                <strong>< ?= \Yii::t('amoscollaborations', 'La proposta non è più modificabile') ?></strong>
            </div>
        < ?php } else {
            echo WorkflowTransitionStateDescriptorWidget::widget([
                'form' => $form,
                'model' => $model,
                'workflowId' => CollaborationProposals::WORKFLOW,
                'classDivIcon' => '',
                'classDivMessage' => 'message',
                'viewWidgetOnNewRecord' => false
            ]);
        } ?> -->

        <div class="row">
            <div class="col-md-12">
                <!-- TITLE -->
                <?= $form->field($model, 'title')->limitedCharsTextInput([
//                        'maxlength' => 256,
//                        'placeholder' => Yii::t('amoscollaborations', 'Titolo') . ' ...'
                ])->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => 256])); ?>
            </div>
            <!-- <div class="col-md-2">
                 ID EEN 
                < ?= $form->field($model, 'id_een')->limitedCharsTextInput(
                        [
//                            'maxlength' => 100,
                            'style' => 'text-transform: uppercase',
//                            'placeholder' => Yii::t('amoscollaborations', 'ID EEN') . ' ...'
                        ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => 100])); ?>
            </div> -->
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- CATEGORY -->
                <?= $form->field($model, 'category_id')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => $model->getCategoryLabel(),
                        'hideSearch' => true,
                        'options' =>
                        [
                            'multiple' => false,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            //'allowClear' => true,
                        ],
                    ]
                ); ?>
            </div>
            <div class="col-md-4">
                <!-- COLLABORATION TYPE (SOUGHT) -->
                <?= $form->field($model, 'collaboration_type_id')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(CollaborationType::find()->all(), 'id', 'name'), 'amoscollaborations'),
                        'hideSearch' => true,
                        'id' => 'collaboration_type',
                        'options' =>
                        [
                            'multiple' => false,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                            'onchange' => 'if($(this).val() == 1 || $(this).val() == 3){
                                               $("#advantages").fadeIn("fast");
                                               $("#technicalSpecs").fadeOut("fast");
                                           }else if($(this).val() == 2 || $(this).val() == 4){
                                               $("#advantages").fadeOut("fast");
                                               $("#technicalSpecs").fadeIn("fast");
                                           }else if($(this).val() == 5){
                                               $("#advantages").fadeIn("fast");
                                               $("#technicalSpecs").fadeIn("fast");
                                           }',
                        ],
                        'pluginOptions' =>
                        [
                            //'allowClear' => true,
                        ],
                    ]
                ); ?>
            </div>
            <div class="col-md-4">
                <!-- STAGE OF DEVELOPMENT -->
                <?php if ($model->isNewRecord) {
                    $model->stage_of_development_id = 6; // 6 = 'Non applicabile'
                } ?>
                <?= $form->field($model, 'stage_of_development_id')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(
                                    ArrayHelper::map(
                                        CollaborationStageOfDevelopment::find()->orderBy(['id' => SORT_DESC])->all(),
                                        'id', 'name'
                                    ), 'amoscollaborations'),
                        'hideSearch' => true,
                        'options' =>
                        [
                            'multiple' => false,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            //'allowClear' => true,
                        ],
                    ]
                ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- SHORT SUMMARY -->
                <?= $form->field($model, 'short_summary')->widget(
                    TextEditorWidget::className(),
                    [
                        'id' => 'short_summary' . $fid,
                        'clientOptions' => [
                            'lang' => substr(\Yii::$app->language, 0, 2)
                        ],
                        'options' => [
                            'maxlength' => CollaborationProposals::SHORT_SUMMARY_MAX_LENGTH,
//                            'placeholder' => \Yii::t('amoscollaborations', 'Sintesi') . ' ...'
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => CollaborationProposals::SHORT_SUMMARY_MAX_LENGTH])); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- FULL DESCRIPTION -->
                <?= $form->field($model, 'full_description')->widget(
                    TextEditorWidget::className(),
                    [
                        'id' => 'full_description' . $fid,
                        'clientOptions' => [
                            'lang' => substr(\Yii::$app->language, 0, 2)
                        ],
                        'options' => [
                            'maxlength' => CollaborationProposals::FULL_DESCRIPTION_MAX_LENGTH,
//                            'placeholder' => \Yii::t('amoscollaborations', 'Descrizione') . ' ...'
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => CollaborationProposals::FULL_DESCRIPTION_MAX_LENGTH])); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" id="advantages" <?= !empty($model->advantages) ? '' : 'style="display: none"' ?>>
                <!-- ADVANTAGES -->
                <?= $form->field($model, 'advantages')->widget(
                    TextEditorWidget::className(),
                    [
                        'id' => 'advantages' . $fid,
                        'clientOptions' => [
                            'lang' => substr(\Yii::$app->language, 0, 2)
                        ],
                        'options' => [
                            'maxlength' => CollaborationProposals::ADVANTAGES_MAX_LENGTH,
//                            'placeholder' => \Yii::t('amoscollaborations', 'Vantaggi / Aspetti innovativi') . ' ...'
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => CollaborationProposals::ADVANTAGES_MAX_LENGTH]))
                    ->label(\Yii::t('amoscollaborations', 'Vantaggi / Aspetti innovativi') . "<span class='text-danger'> *</span>"); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" id="technicalSpecs" <?= !empty($model->technical_specifications) ? '' : 'style="display: none"' ?>>
                <!-- TECHNICAL SPECIFICATIONS -->
                <?= $form->field($model, 'technical_specifications')->widget(
                    TextEditorWidget::className(),
                    [
                        'id' => 'advantages' . $fid,
                        'clientOptions' => [
                            'lang' => substr(\Yii::$app->language, 0, 2)
                        ],
                        'options' => [
                            'maxlength' => CollaborationProposals::TECHNICAL_SPECIFICATIONS_MAX_LENGTH,
//                            'placeholder' => \Yii::t('amoscollaborations', 'Specifiche tecniche / Competenze cercate') . ' ...'
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => CollaborationProposals::TECHNICAL_SPECIFICATIONS_MAX_LENGTH]))
                    ->label(\Yii::t('amoscollaborations', 'Specifiche tecniche/ competenze cercate') . "<span class='text-danger'> *</span>"); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- PARTNER ROLE -->
                <?= $form->field($model, 'partner_role')->widget(
                    TextEditorWidget::className(),
                    [
                        'id' => 'partner_role' . $fid,
                        'clientOptions' => [
                            'lang' => substr(\Yii::$app->language, 0, 2)
                        ],
                        'options' => [
                            'maxlength' => CollaborationProposals::PARTNER_ROLE_MAX_LENGTH,
//                            'placeholder' => \Yii::t('amoscollaborations', 'Ruolo / Contributo del partner cercato') . ' ...'
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => CollaborationProposals::PARTNER_ROLE_MAX_LENGTH])); ?>
            </div>
        </div>

        <!-- <div class="row">
             GOALS 
            <div class="col-md-12">
                < ?php
                $goals = CollaborationProposals::getAllGoals();
                $goalsArray = [];
                $goalsArray = ArrayHelper::map($goals, 'id', 'nome');

                echo $form->field($model, 'tagValuesGoal')->widget(\kartik\select2\Select2::className(), [
                    'data' => ArrayUtility::translateArrayValues($goalsArray, 'amoscollaborations'),
                    'showToggleAll' => false,
                    'options' =>
                    [
                        'id' => 'goals-id',
                        'placeholder' => Yii::t('amoscollaborations', "Seleziona ..."),
                        'multiple' => true,
                        'data-toggle' => 'tooltip',
                    ],
                    'pluginOptions' =>
                    [
                        'allowClear' => true,
                        'maximumSelectionLength' => 5
                    ],
                ])->label(\Yii::t('amoscollaborations', 'Obiettivi di sviluppo sostenibile (Goal ONU)'))
                    ->hint(\Yii::t('amoscollaborations', 'Max {x} valori selezionabili', ['x' => 5]) . '. ' . \Yii::t('amoscollaborations', 'Deve essere selezionato almeno 1 valore'));
                ?>
            </div>

        </div> -->

        <div class="row">
            <div class="col-md-4">
                <!-- IPR STATUS -->
                <?= $form->field($model, 'ipr_status_mm')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(CollaborationIprStatus::find()->all(), 'id', 'name'), 'amoscollaborations'),
                        'hideSearch' => true,
                        'showToggleAll' => false,
                        'options' =>
                        [
                            'multiple' => true,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            'allowClear' => true,
                            'maximumSelectionLength' => 3,
                        ],
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} valori selezionabili', ['x' => 3]))
                    ->label(\Yii::t('amoscollaborations', 'Proprietà industriale')); ?>
            </div>

            <div class="col-md-4">
                <!-- PARTNERSHIP TYPE -->
                <?= $form->field($model, 'partnership_type_mm')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(CollaborationPartnershipType::find()->all(), 'id', 'name'), 'amoscollaborations'),
                        'hideSearch' => true,
                        'options' =>
                        [
                            'multiple' => true,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            'allowClear' => true,
                        ],
                    ]
                )->label(\Yii::t('amoscollaborations', 'Tipo di partnership')); ?>
            </div>

            <div class="col-md-4">
                <!-- PARTNER TYPE AND SIZE -->
                <?= $form->field($model, 'partner_type_and_size_mm')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(CollaborationPartnerTypeAndSize::find()->all(), 'id', 'name'), 'amoscollaborations'),
                        'hideSearch' => true,
                        'options' =>
                        [
                            'multiple' => true,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            'allowClear' => true,
                        ],
                    ]
                )->label(\Yii::t('amoscollaborations', 'Tipo e dimensione del partner cercato')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- TARGET COUNTRIES  -->
                <?= $form->field($model, 'target_countries_mm')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(CollaborationTargetCountries::find()->all(), 'id', 'name'), 'amoscollaborations'),
                        'hideSearch' => true,
                        'options' =>
                        [
                            'multiple' => true,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            'allowClear' => true,
                        ],
                    ]
                )->label(\Yii::t('amoscollaborations', 'Paesi interessati')); ?>
            </div> 

            <!-- MARKET KEYWORDS -->
            <!--<div class="col-md-6">
                < ?= $form->field($model, 'market_keywords_mm')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayHelper::map(CollaborationMarketKeywords::find()->all(), 'id', 'description', 'category'),
                        'hideSearch' => true,
                        'showToggleAll' => false,
                        'options' =>
                            [
                                'multiple' => true,
                                'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                                'data-toggle' => 'tooltip',
                            ],
                        'pluginOptions' =>
                            [
                                'allowClear' => true,
                                'maximumSelectionLength' => 5,
                            ],
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max 5 valori selezionabili')); ?>
            </div>-->

            <!-- TECHNOLOGY KEYWORDS -->
            <!--<div class="col-md-4">
                < ?= $form->field($model, 'technology_keywords_mm')->widget(
                    \kartik\select2\Select2::classname(),
                    [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(CollaborationTechnologyKeywords::find()->all(), 'id', 'description'), 'amoscollaborations'),
                        'hideSearch' => true,
                        'showToggleAll' => false,
                        'options' =>
                        [
                            'multiple' => true,
                            'placeholder' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                            'data-toggle' => 'tooltip',
                        ],
                        'pluginOptions' =>
                        [
                            'allowClear' => true,
                            'maximumSelectionLength' => 5,
                        ],
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max 5 valori selezionabili'))
                    ->label(\Yii::t('amoscollaborations', 'Tecnologie impiegate')); ?>
            </div>-->
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- IMAGE -->
                <?= $form->field($model, 'image')->widget(
                    \open20\amos\attachments\components\CropInput::classname(),
                    [
                        'jcropOptions' => ['aspectRatio' => '0'],
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Estensioni supportate: png, jpg, gif. Dimensione max: 4MB').'<br>'.
                                \Yii::t('amoscollaborations', 'Formato: 16:9. Larghezza: 1920px (min. 1300px)')) ?>
            </div>

            <div class="col-md-6">
                <!-- DOCUMENT -->
                <?= $form->field($model, 'pdf')->widget(
                    \open20\amos\attachments\components\AttachmentsInput::classname(),
                    [
                        'options' => [ // Options of the Kartik's FileInput widget
                            'multiple' => false, // If you want to allow multiple upload, default to false
                            'onchange' => "if(!($(this).val())){
                                               $('#short_abstract').fadeOut('fast');
                                            }else{
                                                $('#short_abstract').fadeIn('fast');
                                            }"
                        ],
                        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 1, // Client max files
                            'showPreview' => false,
                            'allowedFileExtensions' => ['pdf'],
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Estensione supportata: pdf'))
                    ->label(\Yii::t('amoscollaborations', 'Documento')); ?>
                <?php if (!empty($model->pdf)) {
                    echo \open20\amos\attachments\components\AttachmentsList::widget([
                        'model' => $model,
                        'attribute' => 'pdf',
                    ]);
                } ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 pull-left" id="caption" style="display: none">
                <!-- CAPTION -->
                <?= $form->field($model, 'caption')->limitedCharsTextarea(
                    [
                        'placeholder' => \Yii::t('amoscollaborations', 'Didascalia immagine'),
                        'rows' => 2,
                        'showCount' => false
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => 100]))
                ->label(\Yii::t('amoscollaborations', 'Didascalia immagine') . "<span class='text-danger'> *</span>"); ?>
            </div>

            <div class="col-md-6 pull-right" id="short_abstract" style="display: none">
                <!-- SHORT ABSTRACT -->
                <?= $form->field($model, 'short_abstract')->limitedCharsTextarea(
                    [
                        'placeholder' => \Yii::t('amoscollaborations', 'Sommario documento'),
                        'rows' => 2,
                        'showCount' => false
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => 100])); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- VIDEO PITCH -->
                <?= $form->field($model, 'video_pitch')->textInput(
                        [
                            'maxlength' => true,
                            'placeholder' => 'http://...',
                            'id' => 'video_pitch'
                        ]
                )->hint(\Yii::t('amoscollaborations', 'Url video') . '. ' . \Yii::t('amoscollaborations', 'Piattaforme supportate') . ': ' . 'YouTube, Vimeo'); ?>
            </div>

            <div class="col-md-6" id="video_title" style="display: none">
                <!-- VIDEO TITLE -->
                <?= $form->field($model, 'video_title')->limitedCharsTextInput([
//                        'maxlength' => true,
                        'placeholder' => \Yii::t('amoscollaborations', 'Titolo del video') . ' ...'
                ])->hint(\Yii::t('amoscollaborations', 'Max {x} caratteri', ['x' => 250]))
                ->label(\Yii::t('amoscollaborations', 'Titolo del video') . "<span class='text-danger'> *</span>"); ?>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6">
                <!-- START DATE -->
                <?php if($model->isNewRecord){
                    $model->start_date = date('Y-m-d');
                } ?>
                <?= $form->field($model, 'start_date')->widget(
                    DateControl::classname(),
                    [
                        'options' =>
                            [
                                'id' => lcfirst(Inflector::id2camel(\yii\helpers\StringHelper::basename($model->className()), '_')) . '-start_date' . ((isset($fid)) ? $fid : 0),
                                'layout' => '{input} {picker} ' . (($model->start_date == '') ? '' : '{remove}'),
                            ]
                    ]
                ); ?>
            </div>

            <div class="col-md-6">
                <!-- END DATE -->
                <?php if($model->isNewRecord){
                    $model->end_date = date('Y-m-d', strtotime('+1 year'));
                } ?>
                <?= $form->field($model, 'end_date')->widget(
                    DateControl::classname(),
                    [
                        'options' =>
                        [
                            'id' => lcfirst(Inflector::id2camel(\yii\helpers\StringHelper::basename($model->className()), '_')) . '-end_date' . ((isset($fid)) ? $fid : 0),
                            'layout' => '{input} {picker} ' . (($model->end_date == '') ? '' : '{remove}'),
                            'value' => strpos(Url::current(), 'create') ? date('Y-m-d', strtotime('+1 year')) : null
                        ]
                    ]
                ); ?>
            </div>
        </div>
        
        <div class="receiver-section m-b-10">
            <div class="tag-section-collaboration">
                <div class="">
                    <!-- TAG -->
                    <?php
                    $moduleTag = \Yii::$app->getModule('tag');
                    $moduleCwh = \Yii::$app->getModule('cwh');
                    isset($moduleCwh) ? $showReceiverSection = true : null;
                    isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
                    if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
                        echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                            'model' => $model,
                            'moduleCwh' => $moduleCwh,
                            'scope' => $scope,
                        ]);
                    } ?>
                </div>

                
                <div class="row m-t-30">
                <h3 class="subtitle-section-form">
                    <?= \Yii::t('amosnews', "Pubblication on the portal mode") ?>
                    <em><?= \Yii::t('amoscollaborations', "Scegli se pubblicare la proposta di collaborazione anche sul portale pubblico") ?></em>
                </h3>

                    <!-- VISIBLE TO GUEST -->
                    <?php
                    // True by default
                    if ($model->isNewRecord) {
                        $model->visible_to_guest = true;
                    } ?>
                    <div class="col-md-6">
                        <?= $form->field($model, 'visible_to_guest')->widget(
                            \kartik\select2\Select2::className(),
                            [
                                'data' =>
                                [
                                    CollaborationProposals::NOT_VISIBLE_TO_GUEST => \Yii::t('amoscore', 'No'),
                                    CollaborationProposals::VISIBLE_TO_GUEST => \Yii::t('amoscore', 'Si')
                                ],
                                'hideSearch' => true,
                                'options' =>
                                [
                                    'id' => 'visible_to_guest',
                                    'prompt' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                                    'disabled' => false,
                                    'onchange' => "if($(this).val() == 1){
                                                       $('#in_evidence').prop('disabled', false);
                                                   }
                                                   if($(this).val() == 0){
                                                       $('#in_evidence').prop('disabled', true);
                                                       $('#in_evidence').val(0).change();
                                                   }"
                                ]
                            ]
                        )->label(\Yii::t('amoscollaborations', 'Vuoi rendere visibile la proposta anche per utenti non registrati?')); ?>
                    </div>

                    <!-- IN EVIDENCE -->
                    <?php
                    // True by default
                    if ($model->isNewRecord) {
                        $model->in_evidence = 0;
                    } ?>
                    <div class="col-md-6">
                        <?= $form->field($model, 'in_evidence')->widget(
                            \kartik\select2\Select2::className(),
                            [
                                'data' =>
                                [
                                    CollaborationProposals::NOT_IN_EVIDENCE => \Yii::t('amoscore', 'No'),
                                    CollaborationProposals::IN_EVIDENCE => \Yii::t('amoscore', 'Si')
                                ],
                                'hideSearch' => true,
                                'options' =>
                                [
                                    'id' => 'in_evidence',
                                    'prompt' => \Yii::t('amoscollaborations', 'Seleziona ...'),
                                    'disabled' => false,
                                ]
                            ]
                        )->label(\Yii::t('amoscollaborations', 'In evidenza (La proposta verrà visualizzata anche in homepage)')); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- SEO -->
        <?php if ($moduleSeo) { ?>
            <div class="<?= $hideSeoModuleClass ?>">
                <?= AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => Yii::t('amosnews', 'SEO'),
                            'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                                'contentModel' => $model,
                            ]),
                        ]
                    ],
                    'headerOptions' => ['tag' => 'h2'],
                    'options' => (Yii::$app->user->can('COLLABORATIONS_ADMINISTRATOR') || \Yii::$app->user->can('COLLABORATIONS_STAFF')) ? [] : ['style' => 'display:none;'],
                    'clientOptions' => [
                        'collapsible' => true,
                        'active' => 'false',
                        'icons' => [
                            'header' => 'ui-icon-amos am am-plus-square',
                            'activeHeader' => 'ui-icon-amos am am-minus-square',
                        ]
                    ],
                ]);
                ?>
            </div>
        <?php } ?>


        <?= RequiredFieldsTipWidget::widget(); ?>
        <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>

        <?php
        $backButton =
            '<div class="workflow-buttons-container nop">' .
                '<div class="workflow-form-actions workflow-button-container">' .
                    Html::a(
                        \Yii::t('amoscore', 'Annulla'),
                        Url::previous(),
                        [
                            'class' => 'btn btn-secondary btn-block',
                            'title' => Yii::t('amoscollaborations', 'Torna alla pagina precedente'),
                            'data-confirm' => Yii::t('amoscollaborations', 'Se non hai cliccato SALVA tutte le modifiche eseguite andranno perse. Confermi?'),
                        ]
                    ) . '<p>' . Yii::t('amoscollaborations', 'Torna alla pagina precedente') . '</p>' .
                '</div>' .
            '</div>';
        ?>

        <div class="row">
            <?php if (!$isArchived || $model->isNewRecord) {
                echo \open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget::widget([
                    'form' => $form,
                    'model' => $model,
                    'workflowId' => CollaborationProposals::WORKFLOW,
                    'viewWidgetOnNewRecord' => true,
                    //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
                    'closeButton' => $backButton,
                    'initialStatusName' => "BOZZA",
                    'initialStatus' => CollaborationProposals::STATUS_DRAFT,
                    'statusToRender' => [
                        CollaborationProposals::STATUS_DRAFT => 'Modifica in corso',
                        CollaborationProposals::STATUS_PUBLISHED => 'Pubblicata'
                    ],
                    'hideSaveDraftStatus' => [
                        //1 => CollaborationProposals::STATUS_PUBLISHED,
                        2 => CollaborationProposals::STATUS_CLOSED,
                        3 => CollaborationProposals::STATUS_REFUSED
                    ],
                    'additionalButtons' => [],
                    'draftButtons' => [
                        'default' => [
                            'button' => Html::submitButton(
                                \Yii::t('amoscore', 'Salva'),
                                [
                                    'class' => 'btn btn-workflow',
                                    'data-confirm' => $model->isNewRecord ? Yii::t('amoscollaborations', 'Confermi di voler salvare? Cliccando OK salverai la proposta in bozza. Cliccando ANNULLA tornerai alla pagina in modifica.') : Yii::t('amoscollaborations', 'Confermi di voler salvare? Cliccando OK salverai le modifiche mantenendo lo stato attuale. Cliccando ANNULLA tornerai alla pagina in modifica.')
                                ]
                            ),
                            'description' => $model->isNewRecord ? \Yii::t('amoscollaborations', 'Potrai pubblicare la proposta in seguito') : \Yii::t('amoscollaborations', 'Salva e mantieni lo stato attuale'),
                        ]
                    ],
                    'enableConfirmNotification' => false
                ]);
            } else { ?>
                <div class="d-flex flex-wrap m-t-30 m-l-10">
                    <?= $backButton ?>
                </div>
            <?php } ?>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="col-md-4 col xs-12"></div>
        <div class="clearfix"></div>