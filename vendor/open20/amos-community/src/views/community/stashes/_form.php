<?php

use open20\amos\community\widgets\mini\CommunityMembersMiniWidget;
use open20\amos\community\widgets\mini\SubcommunitiesMiniWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\cwh\widgets\DestinatariPlusTagWidget;
use open20\amos\report\widgets\ReportFlagWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\AccordionWidget;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use open20\amos\attachments\components\CropInput;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\Community $model
 * @var yii\widgets\ActiveForm $form
 */

$modelsEnabled = Yii::$app->getModule('cwh')->modelsEnabled;

$idTabContents = 'tab-contents';
$idTabSheet = 'tab-registry';
$idTabParticipants = 'tab-participants';
$idTabSubcommunities = 'tab-subcommunities';
//check if a specific tab must be the active one
$tabContentsActive = isset($tabActive) ? ((strcmp($tabActive, $idTabContents) == 0) ? true : false) : false;
$tabSheetActive = isset($tabActive) ? ((strcmp($tabActive, $idTabSheet) == 0) ? true : false) : true;
$tabParticipantsActive = isset($tabActive) ? ((strcmp($tabActive, $idTabParticipants) == 0) ? true : false) : false;
$tabSubcommunitiesActive = isset($tabActive) ? ((strcmp($tabActive, $idTabSubcommunities) == 0) ? true : false) : false;

$communities = CommunityUtil::getParentList();
$showSubcommunityField = (!empty($communities) && ($model->isNewRecord || $model->parent_id != null));
/** @var AmosCommunity $moduleCommunity */
$moduleCommunity = Yii::$app->getModule('community');
$fixedCommunityType = !is_null($moduleCommunity->communityType);
$viewTabContents = $moduleCommunity->viewTabContents;
$bypassWorkflow = $moduleCommunity->forceWorkflow($model);
$showSubcommunities = $moduleCommunity->showSubcommunities;
$hideContentsModels = $moduleCommunity->hideContentsModels;

$currentStatus = $model->status;
$draftStatus = $model->getDraftStatus();
$validatedStatus = $model->getValidatedStatus();

$js = <<<JS
var modalId = '#visibleOnEditPopup-$model->id';
var valDescription = $('.description-redactor').val();
var statusWorkflowDraft = document.getElementById("$draftStatus");


$('#tab-registry').on('change', 'input, select, textarea', function(e) {
  $('#community-backtoedit').val(1);
});

$(modalId).on('click', '#visibleYes', function(e) {
  $('#community-visible_on_edit').val(1);
});

$(modalId).on('click', '#visibleNot', function(e) {
   e.preventDefault();
   $('#community-visible_on_edit').val(0);
   $(modalId).modal('hide'); 
   $('#warningPopup').modal('show'); 
});

$('#warningPopup').on('click', '#ok-warning', function(e) {
    $('form').submit();  
});

$('#saveBtn').on('click', function(e) {
    e.preventDefault();
        if($('.description-redactor').val() !== valDescription) {
          $('#community-backtoedit').val(1);
    }
    if($(this).data('target') == modalId && $('#community-backtoedit').val() == 1){
       $(modalId).modal('show'); 
    }else{
       $('form#community_form_$model->id').submit();  
    }
  return false;
});

$(statusWorkflowDraft).on('click', function(e) {
   if( this.id == '$draftStatus' && '$currentStatus' == '$validatedStatus' ){
           $(this).find("button[type='submit']").removeAttr('disabled');
            $('#community-backtoedit').val(1);
           $(modalId).modal('show');
              return false;

   }
       $('form#community_form_$model->id').submit();  
   return false;
 
});
JS;


$this->registerJs($js);

?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        'class' => 'form default-form col-xs-12 nop',
        'id' => 'community_form_' . $model->id,
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ],
]);
?>

<?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>


<?= WorkflowTransitionStateDescriptorWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => Community::COMMUNITY_WORKFLOW,
    'classDivMessage' => 'message',
    'viewWidgetOnNewRecord' => false
]); ?>

<div class="community-form">
    <div class="row">
        <div class="col-xs-12">
            <?= Html::tag('h2', AmosCommunity::t('amoscommunity', '#settings_general_title') .
                CreatedUpdatedWidget::widget(['model' => $model, 'isTooltip' => true]) .
                ReportFlagWidget::widget([
                    'model' => $model,
                ]), ['class' => 'subtitle-form']) ?>
        </div>
        <div class="col-md-8 col-xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => AmosCommunity::t('amoscommunity', '#title_field_plceholder')])->hint(AmosCommunity::t('amoscommunity', '#title_field_hint')) ?>
            <?= $form->field($model, 'description')->widget(TextEditorWidget::className(), [
                'options' => [
                    'id' => 'description' . $model->id,
                    'class' => 'description-redactor'
                ],
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'placeholder' => AmosCommunity::t('amoscommunity', 'Insert the community description'),
                ],
            ])->label($model->getAttributeLabel('description'), ['for' => 'description' . $model->id]);
            ?>
            <?php if (!$fixedCommunityType || ($showSubcommunityField && $showSubcommunities)): ?>
                <div class="col-md-6 col-xs-12">
                    <?php if (!$fixedCommunityType): ?>
                        <?= $form->field($model, 'community_type_id')->widget(Select2::className(), [
                            'data' => CommunityUtil::getCommunityTypeReadyForSelect(),
                            'language' => substr(Yii::$app->language, 0, 2),
                            'options' => ['multiple' => false,
                                'id' => 'communityType' . $model->id,
                                'placeholder' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                                'class' => 'dynamicCreation',
                                'data-model' => 'community-type',
                                'data-field' => 'id',
                                'data-module' => 'community',
                                'data-entity' => 'community-type',
                                'data-toggle' => 'tooltip',
                                'disabled' => (!$model->isNewRecord) && ($model->community_type_id != null)
                            ],
                            'pluginOptions' => [
                                'allowClear' => $model->isNewRecord
                            ],
                            'pluginEvents' => [
                                "select2:open" => "dynamicInsertOpening"
                            ]
                        ])->label($model->getAttributeLabel('communityType'), ['for' => 'communityType' . $model->id]) ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 col-xs-12">
                    <?php if ($showSubcommunityField && $showSubcommunities): ?>
                        <?php if (!empty($model->parent_id)) {
                            $communityParent = Community::findOne($model->parent_id); ?>
                            <?= $form->field($model, 'parent_id')->hiddenInput()->label(false); ?>
                            <p>
                                <strong><?= AmosCommunity::t('amoscommunity', "#parent_id_form_label") ?>
                                    : </strong><?= $communityParent->name ?>
                            </p>
                        <?php } else { ?>
                            <?= $form->field($model, 'parent_id')->widget(Select2::className(), [
                                'data' => $communities,
                                'options' => [
                                    'multiple' => false,
                                    'id' => 'parent_id',
                                    'placeholder' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                                    'class' => 'dynamicCreation',
                                    'data-field' => 'id',
                                    'disabled' => !$model->isNewRecord
                                ],
                                'pluginOptions' => [
                                    'allowClear' => $model->isNewRecord && Yii::$app->user->can('COMMUNITY_CREATOR')
                                ],
                            ])->label(AmosCommunity::t('amoscommunity', "#parent_id_form_label")) ?>
                        <?php } ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php

            $showReceiverSection = false;

            $moduleCwh = \Yii::$app->getModule('cwh');
            isset($moduleCwh) ? $showReceiverSection = true : null;

            $moduleTag = \Yii::$app->getModule('tag');
            isset($moduleTag) ? $showReceiverSection = true : null;

            if ($showReceiverSection) : ?>
                <div class="col-xs-12">
                    <?= Html::tag('h2', AmosCommunity::t('amoscommunity', '#settings_receiver_title'), ['class' => 'subtitle-form']) ?>
                    <div class="col-xs-12 receiver-section">
                        <?= DestinatariPlusTagWidget::widget([
                            'model' => $model,
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-xs-12 note_asterisk">
                <span><?= AmosCommunity::t('amoscommunity', '#required_field') ?></span>
            </div>

        </div>
        <div class="col-md-4 col-xs-12">
            <div class="col-xs-12 nop">
                <?= $form->field($model, 'communityLogo')->widget(CropInput::classname(), [
                    'jcropOptions' => ['aspectRatio' => '1.7']
                ])->label(AmosCommunity::t('amoscommunity', '#image_field'))->hint(AmosCommunity::t('amoscommunity', '#image_field_hint')) ?>
            </div>
            <div class="col-xs-12 member-section nop">
                <div class="col-xs-12">
                    <?= CommunityMembersMiniWidget::widget([
                        'model' => $model,
                        'targetUrlParams' => [
                            'viewM2MWidgetGenericSearch' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="col-xs-12 subcommunity-section nop">
                <div class="col-xs-12">
                    <?= SubcommunitiesMiniWidget::widget([
                        'model' => $model,
                        'isUpdate' => true
                    ]); ?>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php
            $moduleSeo = \Yii::$app->getModule('seo');
            if (isset($moduleSeo)) : ?>
                <?= AccordionWidget::widget([
                    'items' => [
                        [
                            'header' => AmosCommunity::t('amoscommunity', '#settings_seo_title'),
                            'content' => \open20\amos\seo\widgets\SeoWidget::widget([
                                'contentModel' => $model,
                            ]),
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
                ]);
                ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row hidden">
        <?= $form->field($model, 'backToEdit')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'visible_on_edit')->hiddenInput()->label(false) ?>
    </div>

    <?php

    Modal::begin(['id' => 'visibleOnEditPopup-' . $model->id]);
    echo Html::tag('div', AmosCommunity::t('amoscommunity',
        "Community status will be set to editing in progress. Keep the community visible while editing it?"));
    echo Html::tag('div',
        Html::submitButton(AmosCommunity::t('amoscommunity', 'Yes'),
            ['class' => 'btn btn-navigation-primary', 'id' => 'visibleYes'])
        . Html::submitButton(AmosCommunity::t('amoscommunity', 'No'), ['class' => 'btn btn-secondary', 'id' => 'visibleNot'])
        . Html::a(AmosCommunity::t('amoscommunity', 'Annulla'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-secondary']),
        ['class' => 'pull-right m-15-0']
    );
    Modal::end();

    Modal::begin(['id' => 'warningPopup']);
    echo Html::tag('div', AmosCommunity::t('amoscommunity',
        "Attenzione, quando si modifica la descrizione di una community è necessario procedere alla richiesta di validazione.
                La community non risulterà visibile agli utenti fino a quando non sarà nuovamente validata."));
    echo Html::tag('div',
        Html::a(AmosCommunity::t('amoscommunity', 'Ok'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-secondary', 'id' => 'ok-warning'])
        . Html::a(AmosCommunity::t('amoscommunity', 'Annulla'), null, ['data-dismiss' => 'modal', 'class' => 'btn btn-secondary']),
        ['class' => 'pull-right m-15-0']
    );
    Modal::end();

    ?>

    <?php
    $statusToRender = [
        Community::COMMUNITY_WORKFLOW_STATUS_DRAFT => AmosCommunity::t('amoscommunity', 'Modifica in corso'),
    ];
    if (Yii::$app->user->can('COMMUNITY_VALIDATE', ['model' => $model]) || Yii::$app->user->can('ADMIN')) {
        $statusToRender = ArrayHelper::merge($statusToRender,
            [Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED => AmosCommunity::t('amoscommunity', 'Pubblicata')]);
        $hideDraftStatuses = false;
        $hideDraftStatus = [];
    } else {
        $statusToRender = ArrayHelper::merge($statusToRender, [
            Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE => AmosCommunity::t('amoscommunity', 'Richiesta pubblicazione'),
        ]);
        $hideDraftStatuses = true;
        $hideDraftStatus = [];
    }

    ?>
    <div class="wrapper-workflow-community">
        <?= WorkflowTransitionButtonsWidget::widget([
            // parametri ereditati da verioni precedenti del widget WorkflowTransition
            'form' => $form,
            'model' => $model,
            'workflowId' => Community::COMMUNITY_WORKFLOW,
            'viewWidgetOnNewRecord' => true,

            'closeButton' => \open20\amos\core\helpers\Html::a(Yii::t('amoscommunity', 'Annulla'), Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']),

            // fisso lo stato iniziale per generazione pulsanti e comportamenti
            // "fake" in fase di creazione (il record non e' ancora inserito nel db)
            'initialStatusName' => explode('/', $model->getWorkflowSource()->getWorkflow(Community::COMMUNITY_WORKFLOW)->getInitialStatusId())[1],
            'initialStatus' => $model->getWorkflowSource()->getWorkflow(Community::COMMUNITY_WORKFLOW)->getInitialStatusId(),
            // Stati da renderizzare obbligatoriamente in fase di creazione (quando il record non e' ancora inserito nel db)
            'statusToRender' => $statusToRender,

            'hideSaveDraftStatus' => $hideDraftStatus,

            'draftButtons' => [
                Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE => [
                    'button' => Html::submitButton(Yii::t('amoscommunity', 'Salva'), ['class' => 'btn btn-workflow']),
                    'description' => AmosCommunity::t('amoscommunity', 'le modifiche e mantieni la community in "richiesta di pubblicazione"'),
                ],
                Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED => [
                    'button' => Html::submitButton(Yii::t('amoscommunity', 'Salva'), [
                        'class' => 'btn btn-workflow',
//                    'id' => 'saveBtn',
//                    'data-target' => (!$bypassWorkflow && ($model->status == Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED) && !$model->isNewRecord ? '#visibleOnEditPopup-' . $model->id : ''),
//                    'data-toggle' => 'modal'
                    ]),
                    'description' => AmosCommunity::t('amoscommunity', 'le modifiche e mantieni la community "pubblicata"'),
                ],
                'default' => [
                    'button' => Html::submitButton(Yii::t('amoscommunity', 'Salva in bozza'), [
                        'class' => 'btn btn-workflow',
                    ]),
                    'description' => AmosCommunity::t('amoscommunity', 'potrai richiedere la pubblicazione in seguito'),
                ]
            ]
        ]);
        ?>
    </div>

</div>

<?php
ActiveForm::end();
?>
