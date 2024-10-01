<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\cwh\widgets\DestinatariPlusTagWidget;
use open20\amos\organizzazioni\assets\OrganizzazioniAsset;
use open20\amos\organizzazioni\controllers\ProfiloController;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloEntiType;
use open20\amos\organizzazioni\models\ProfiloLegalForm;
use open20\amos\organizzazioni\models\ProfiloTypesPmi;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use open20\amos\organizzazioni\widgets\maps\PlaceWidget;
use open20\amos\tag\AmosTag;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\alert\Alert;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\organizzazioni\models\Profilo $model
 * @var \open20\amos\core\forms\ActiveForm $form
 * @var \open20\amos\organizzazioni\models\ProfiloSediLegal $mainLegalHeadquarter
 * @var \open20\amos\organizzazioni\models\ProfiloSediOperative $mainOperativeHeadquarter
 * @var \open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */

$this->registerJs("    
    verifySameSede();
    $('#profilo-la_sede_legale_e_la_stessa_del input').on('change', function() {
        verifySameSede();
    });
    function verifySameSede() {
    var attrib = $(\"#profilo-la_sede_legale_e_la_stessa_del input[type='radio']:checked\").val();
        if(attrib == 1){
            $('#same_sede').hide();
        } else {
            $('#same_sede').show();
        }
    }
    ", View::POS_READY);

$moduleL = Yii::$app->getModule('layout');
if (!empty($moduleL)) {
    OrganizzazioniAsset::register($this);
}

/** @var Module $organizzazioniModule */
$organizzazioniModule = Yii::$app->getModule(Module::getModuleName());

/** @var AmosTag $moduleTag */
$moduleTag = Yii::$app->getModule('tag');

/** @var ProfiloController $appController */
$appController = Yii::$app->controller;

$profiloEntiTypeElementId = Html::getInputId($model, 'profilo_enti_type_id');
$istatCodeElementId = Html::getInputId($model, 'istat_code');
$tipologiaDiOrganizzazione = Html::getInputId($model, 'tipologia_di_organizzazione');
$typeMunicipality = ProfiloEntiType::TYPE_MUNICIPALITY;
$sameHeadquarterElementId = Html::getInputId($model, 'la_sede_legale_e_la_stessa_del');
$legalHeadquarterAddressElementId = Html::getInputId($model, 'mainLegalHeadquarterAddress');
$disableFieldChecks = isset($organizzazioniModule->disableFieldChecks) ? $organizzazioniModule->disableFieldChecks : false;
$workflowIsEnabled = $organizzazioniModule->enableWorkflow;

$js = <<<JS
function addRequiredAsterisk(fieldName) {
    $('.field-' + fieldName).addClass('required');
}

function removeRequiredAsterisk(fieldName) {
    $('.field-' + fieldName).removeClass('required');
}

var sameHeadquarterElementId = $('#$sameHeadquarterElementId');
function manageLegalHeadquarterRequiredAddress() {
    if (sameHeadquarterElementId.val() == 1) {
        removeRequiredAsterisk('$legalHeadquarterAddressElementId');
    } else {
        addRequiredAsterisk('$legalHeadquarterAddressElementId');
    }
}

manageLegalHeadquarterRequiredAddress();

sameHeadquarterElementId.change(function() {
    manageLegalHeadquarterRequiredAddress();
});
JS;
$this->registerJs($js);


$jsenablecheck = <<<JS
var profiloEntiTypeElement = $('#$profiloEntiTypeElementId');
var istatCodeElement = $('#$istatCodeElementId');
//var tipologiaDiOrganizzazione = $('#$tipologiaDiOrganizzazione');

function manageEnabledFields() {
    if (profiloEntiTypeElement.val() == $typeMunicipality) {
        addRequiredAsterisk('$istatCodeElementId');
        istatCodeElement.prop("disabled", false);
//        tipologiaDiOrganizzazione.prop("disabled", true);
    } else {
        removeRequiredAsterisk('$istatCodeElementId');
        istatCodeElement.prop("disabled", true);
//        tipologiaDiOrganizzazione.prop("disabled", false);
    }
}

//manageEnabledFields();

profiloEntiTypeElement.change(function() {
   manageEnabledFields();
});
JS;

if (!$disableFieldChecks) {
    $this->registerJs($jsenablecheck);
}
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'are-profilo_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'enctype' => 'multipart/form-data' // important
    ]
]);


/** @var ProfiloEntiType $modelProfiloEntiType */
$modelProfiloEntiType = $organizzazioniModule->createModel('ProfiloEntiType');

/** @var ProfiloEntiType $modelProfiloTipoStruttura */
$modelProfiloTipoStruttura = $organizzazioniModule->createModel('ProfiloTipoStruttura');

/** @var ProfiloTypesPmi $modelProfiloTypesPmi */
$modelProfiloTypesPmi = $organizzazioniModule->createModel('ProfiloTypesPmi');

/** @var ProfiloLegalForm $modelProfiloLegalForm */
$modelProfiloLegalForm = $organizzazioniModule->createModel('ProfiloLegalForm');

/** @var UserProfile $modelUserProfile */
$modelUserProfile = AmosAdmin::instance()->createModel('UserProfile');

?>

<?php if ($workflowIsEnabled) : ?>
    <?= WorkflowTransitionStateDescriptorWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => Profilo::PROFILO_WORKFLOW,
        'classDivIcon' => '',
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => false
    ]); ?>
<?php endif; ?>

<div class="area-profilo-form">
    <div class="row">

        <div class="col-xs-12">
            <?= Html::tag('h2', Module::t('amosorganizzazioni', '#settings_general_title'), ['class' => 'subtitle-form']) ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($model, 'logoOrganization')->widget(CropInput::classname(), [
                'jcropOptions' => ['aspectRatio' => '1.7']
            ])->label(Module::t('amosorganizzazioni', '#image_field'))->hint(Module::t('amosorganizzazioni', '#image_field_hint')) ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#name_field_placeholder')])->hint(Module::t('amosorganizzazioni', '#name_field_hint')) ?>
        </div>

        <?php if ($organizzazioniModule->enableUniqueSecretCodeForInvitation && !$model->isNewRecord) : ?>
            <div class="col-xs-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label"><?= $model->getAttributeLabel('unique_secret_code') . ':' ?></label> <?= $model->unique_secret_code ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($organizzazioniModule->enableProfiloTipologiaStruttura === true) : ?>
            <div class="col-xs-12">

                <?= $form->field($model, 'tipologia_struttura_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map($modelProfiloTipoStruttura::find()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosorganizzazioni', 'Seleziona') . '...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>
            </div>
        <?php endif; ?>

        <?php if ($organizzazioniModule->enableProfiloEntiType === true) : ?>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'profilo_enti_type_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map($modelProfiloEntiType::find()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosorganizzazioni', 'Seleziona') . '...',
                        'disabled' => (!$model->isNewRecord),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>
            </div>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'istat_code')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#istat_code_field_placeholder')]) ?>
            </div>
        <?php endif; ?>

        <?php if ($organizzazioniModule->enableTipologiaOrganizzazione === true) : ?>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'tipologia_di_organizzazione')->widget(Select::classname(), [
                    'data' => ArrayHelper::map($modelProfiloTypesPmi::find()->asArray()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosorganizzazioni', 'Seleziona') . '...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        <?php endif; ?>
        <?php if ($organizzazioniModule->enableFormaLegale === true) : ?>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'forma_legale')->widget(Select::classname(), [
                    'data' => ArrayHelper::map($modelProfiloLegalForm::find()->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosorganizzazioni', 'Seleziona') . '...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
        <?php endif; ?>

        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'partita_iva')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#partita_iva_field_placeholder')]) ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'codice_fiscale')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#codice_fiscale_field_placeholder')]) ?>
        </div>

        <div class="col-xs-12">
            <?= $form->field($model, 'presentazione_della_organizzaz')->widget(TextEditorWidget::className(), [
                'options' => [
                    'id' => 'presentazione_della_organizzaz' . $fid,
                ],
                'clientOptions' => [
                    'placeholder' => Module::t('amosorganizzazioni', '#presentazione_field_placeholder'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>

        <?php if ($organizzazioniModule->enableOrganizationAttachments) : ?>
            <div class="col-xs-12 section-form">
                <?= Html::tag('h2', Module::t('amosorganizzazioni', '#attachments_title'), ['class' => 'subtitle-form']) ?>
                <?= $form->field(
                    $model,
                    'allegati'
                )->widget(AttachmentsInput::classname(), [
                    'options' => [ // Options of the Kartik's FileInput widget
                        'multiple' => true, // If you want to allow multiple upload, default to false
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 100, // Client max files
                        'showPreview' => false,
                        'allowedFileExtensions' => !empty($organizzazioniModule->allowedFileExtensions)? $organizzazioniModule->allowedFileExtensions: null,
                    ]
                ])->label(Module::t('amosorganizzazioni', '#attachments_field'))->hint(Module::t('amosorganizzazioni', '#attachments_field_hint')) ?>

                <?= AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'allegati'
                ]) ?>
            </div>
        <?php endif; ?>

        <?php if ($organizzazioniModule->enableMembershipOrganizations) : ?>
            <div class="col-xs-12">
                <?= $form->field($model, 'parent_id')->widget(Select::className(), [
                    'data' => OrganizzazioniUtility::getMembershipOrganizationsReadyForSelect($model),
                    'options' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'multiple' => false,
                        'placeholder' => Module::t('amosorganizzazioni', 'Select/Choose') . '...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>
            </div>
        <?php endif; ?>

        <?php if (!$organizzazioniModule->oldStyleAddressEnabled) : ?>

            <div class="col-xs-12">
                <?= Html::tag('h2', Module::t('amosorganizzazioni', 'Sede operativa'), ['class' => 'subtitle-form']) ?>
            </div>

            <div class="col-xs-12">
                <?= $form->field($model, 'mainOperativeHeadquarterAddress')->widget(
                    PlaceWidget::className(),
                    [
                        'placeAlias' => 'sedeIndirizzo'
                    ]
                ); ?>
            </div>
        <?php else : ?>
            <?= $this->render('@vendor/open20/amos-organizzazioni/src/views/profilo-sedi/_old_style_address_fields', ['form' => $form, 'modelSedi' => $mainOperativeHeadquarter]); ?>
        <?php endif; ?>

        <div class="col-xs-12">
            <?= $form->field($model, 'sito_web')->textInput([
                'maxlength' => true,
                'placeholder' => Module::t('amosorganizzazioni', '#sito_field_placeholder')
            ]) ?>
        </div>

        <?php if ($organizzazioniModule->enableSocial) : ?>
            <div class="col-xs-12 social-section">
                <?= Html::tag('h2', Module::t('amosorganizzazioni', '#social_title'), ['class' => 'subtitle-form']) ?>
                <div class="row">
                    <div class="col-xs-2">
                        <?= AmosIcons::show('facebook-box'); ?>
                    </div>
                    <div class="col-xs-10">
                        <?= $form->field($model, 'facebook')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                    <div class="col-xs-2">
                        <?= AmosIcons::show('twitter-box'); ?>
                    </div>
                    <div class="col-xs-10">
                        <?= $form->field($model, 'twitter')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                    <div class="col-xs-2">
                        <?= AmosIcons::show('linkedin-box'); ?>
                    </div>
                    <div class="col-xs-10">
                        <?= $form->field($model, 'linkedin')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>

            </div>
        <?php endif; ?>

        <div class="col-md-6 col-xs-12">
            <?= $form->field($mainOperativeHeadquarter, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => Module::t('amosorganizzazioni', '#email_field_placeholder')
            ]) ?>
        </div>

        <div class="col-md-6 col-xs-12">
            <?= $form->field($mainOperativeHeadquarter, 'pec')->textInput([
                'maxlength' => true,
                'placeholder' => Module::t('amosorganizzazioni', '#pec_field_placeholder')
            ]) ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($mainOperativeHeadquarter, 'phone')->textInput([
                'maxlength' => true,
                'placeholder' => Module::t('amosorganizzazioni', '#telefono_field_placeholder')
            ]) ?>
        </div>

        <div class="col-md-6 col-xs-12">
            <?= $form->field($mainOperativeHeadquarter, 'fax')->textInput([
                'maxlength' => true,
                'placeholder' => Module::t('amosorganizzazioni', '#fax_field_placeholder')
            ]) ?>
        </div>

        <div class="col-xs-12">
            <?php echo $form->field($model, 'responsabile')->textInput([
                'maxlength' => true,
                'placeholder' => Module::t('amosorganizzazioni', '#responsabile_field_placeholder')
            ]) ?>
        </div>

        <?php if ($organizzazioniModule->enableRappresentanteLegaleText) : ?>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'rappresentante_legale_text') ?>
            </div>
        <?php else : ?>
            <div class="col-md-6 col-xs-12">
                <!-- rappresentante_legale string -->
                <?= $form->field($model, 'rappresentante_legale')->widget(Select::className(), [
                    'initValueText' => empty($model->rappresentante_legale) ? '' : $modelUserProfile::findOne(['user_id' => $model->rappresentante_legale])->nomeCognome,
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'placeholder' => Module::t('amosorganizzazioni', 'Seleziona') . '...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => $appController->getRappresentanteLegaleAjaxUrl($model),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]); ?>
            </div>
        <?php endif; ?>
        <div class="col-md-6 col-xs-12">
            <!-- referente_operativo string -->
            <?php if (!$model->isNewRecord) : ?>
                <?= $form->field($model, 'referente_operativo')->widget(Select::className(), [
                    'initValueText' => empty($model->referente_operativo) ? '' : $modelUserProfile::findOne(['user_id' => $model->referente_operativo])->nomeCognome,
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'placeholder' => Module::t('amosorganizzazioni', 'Seleziona') . '...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => $appController->getReferenteOperativoAjaxUrl($model),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]); ?>
            <?php else : ?>
                <?= Alert::widget([
                    'type' => Alert::TYPE_WARNING,
                    'body' => Module::t('amosorganizzazioni', '#alert_select_delegated'),
                    'closeButton' => false
                ]); ?>
            <?php endif; ?>
        </div>
        <?php if ($organizzazioniModule->enableContattoReferenteOperativo) : ?>
            <div class="col-xs-12">
                <?= $form->field($model, 'contatto_referente_operativo')->textInput(['maxlength' => true]); ?>
            </div>
        <?php endif; ?>
        <div class="col-xs-12<?= ($organizzazioniModule->forceSameSede ? ' hidden' : '') ?>">
            <?= $form->field($model, 'la_sede_legale_e_la_stessa_del', [
                'options' => [
                    'class' => 'checkLocationsForCopy',
                ]
            ])->inline(true)->radioList([
                1 => Yii::t('amosorganizzazioni', 'Si'),
                0 => Yii::t('amosorganizzazioni', 'No')
            ]) ?>
        </div>

        <div class="col-xs-12">
            <div class="row" id="same_sede">
                <div class="col-xs-12">
                    <?= Html::tag('h2', Module::t('amosorganizzazioni', '#same_sede_title'), ['class' => 'subtitle-form']) ?>
                </div>

                <?php if (!$organizzazioniModule->forceSameSede) : ?>
                    <?php if (!$organizzazioniModule->oldStyleAddressEnabled) : ?>
                        <div class="col-xs-12">
                            <?= $form->field($model, 'mainLegalHeadquarterAddress')->widget(
                                PlaceWidget::className(),
                                [
                                    'placeAlias' => 'sedeLegaleIndirizzo'
                                ]
                            ); ?>
                        </div>
                    <?php else : ?>
                        <?= $this->render('@vendor/open20/amos-organizzazioni/src/views/profilo-sedi/_old_style_address_fields', ['form' => $form, 'modelSedi' => $mainLegalHeadquarter]); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="col-md-6 col-xs-12">
                    <?= $form->field($mainLegalHeadquarter, 'email')->textInput([
                        'maxlength' => true,
                        'placeholder' => Module::t('amosorganizzazioni', '#email_field_placeholder')
                    ]) ?>
                </div>
                <div class="col-md-6 col-xs-12">
                    <?= $form->field($mainLegalHeadquarter, 'pec')->textInput([
                        'maxlength' => true,
                        'placeholder' => Module::t('amosorganizzazioni', '#pec_field_placeholder')
                    ]) ?>
                </div>
                <div class="col-md-6 col-xs-12">
                    <?= $form->field($mainLegalHeadquarter, 'phone')->textInput([
                        'maxlength' => true,
                        'placeholder' => Module::t('amosorganizzazioni', '#telefono_field_placeholder')
                    ]) ?>
                </div>
                <div class="col-md-6 col-xs-12">
                    <?= $form->field($mainLegalHeadquarter, 'fax')->textInput([
                        'maxlength' => true,
                        'placeholder' => Module::t('amosorganizzazioni', '#fax_field_placeholder')
                    ]) ?>
                </div>
            </div>
        </div>

        <?php if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors) : ?>
            <div class="col-xs-12">
                <?= Html::tag('h2', Module::t('amosorganizzazioni', '#settings_receiver_title'), ['class' => 'subtitle-form']) ?>
                <div class="col-xs-12 receiver-section">
                    <?= DestinatariPlusTagWidget::widget([
                        'model' => $model,
                        'moduleCwh' => $moduleCwh,
                        'scope' => $scope
                    ]); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <?= AccordionWidget::widget([
            'items' => [
                [
                    'header' => Module::t('amosorganizzazioni', '#other_headquarters'),
                    'content' => $this->render('_other_headquarters', ['model' => $model, 'isView' => false]),
                ]
            ],
            'headerOptions' => ['tag' => 'h2'],
            'clientOptions' => [
                'collapsible' => false,
                'active' => false,
                'icons' => [
                    'header' => 'ui-icon-amos am am-plus-square',
                    'activeHeader' => 'ui-icon-amos am am-minus-square',
                ]
            ],
            'options' => [
                'class' => 'first-accordion'
            ]
        ]); ?>
        <?= AccordionWidget::widget([
            'items' => [
                [
                    'header' => Module::t('amosorganizzazioni', '#employees'),
                    'content' => $this->render('organization-employees', ['model' => $model, 'isUpdate' => true]),
                ]
            ],
            'headerOptions' => ['tag' => 'h2'],
            'clientOptions' => [
                'collapsible' => false,
                'active' => false,
                'icons' => [
                    'header' => 'ui-icon-amos am am-plus-square',
                    'activeHeader' => 'ui-icon-amos am am-minus-square',
                ]
            ],
            'options' => [
                'class' => 'first-accordion'
            ]
        ]); ?>
    </div>
    <div class="col-xs-12"><?= RequiredFieldsTipWidget::widget() ?></div>
    <?php if (!$workflowIsEnabled) : ?>
        <div class="col-xs-12"><?= CloseSaveButtonWidget::widget(['model' => $model]); ?></div>
    <?php else : ?>
        <?php
        $statusToRenderToHide = $model->getStatusToRenderToHide();
        ?>
        <?= WorkflowTransitionButtonsWidget::widget([
            'form' => $form,
            'model' => $model,
            'workflowId' => Profilo::PROFILO_WORKFLOW,
            'viewWidgetOnNewRecord' => true,
            'closeButton' => Html::a(Module::t('amosorganizzazioni', 'Annulla'), Yii::$app->session->get('previousUrl'), ['class' => 'btn btn-secondary']),
            'initialStatusName' => "DRAFT",
            'initialStatus' => Profilo::PROFILO_WORKFLOW_STATUS_DRAFT,
            'statusToRender' => $statusToRenderToHide['statusToRender'],
            //POII-1147 gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
            //altrimenti se stato VALIDATO => pulsante salva nascosto
            'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
            'draftButtons' => [
                Profilo::PROFILO_WORKFLOW_STATUS_TOVALIDATE => [
                    'button' => Html::submitButton(Module::t('amosorganizzazioni', 'Save'), ['class' => 'btn btn-workflow']),
                    'description' => 'le modifiche e mantieni in "richiesta di pubblicazione"'
                ],
                Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED => [
                    'button' => Html::submitButton(Module::t('amosorganizzazioni', 'Save'), ['class' => 'btn btn-workflow']),
                    'description' => Module::t('amosorganizzazioni', 'le modifiche e mantieni "pubblicata"'),
                ],
                'default' => [
                    'button' => Html::submitButton(Module::t('amosorganizzazioni', 'save draft'), ['class' => 'btn btn-workflow']),
                    'description' => Module::t('amosorganizzazioni', 'potrai richiedere la pubblicazione in seguito'),
                ]
            ]
        ]); ?>
    <?php endif; ?>
</div>
</div>
<?php ActiveForm::end(); ?>