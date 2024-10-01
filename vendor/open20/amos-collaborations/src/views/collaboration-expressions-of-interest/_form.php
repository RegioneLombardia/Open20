<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor\open20\amos\collaborations\views/views
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\comuni\widgets\helpers\AmosComuniWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\user\User;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;
use \open20\amos\collaborations\models\CollaborationProposals;

/**
 * @var yii\web\View $this
 * @var open20\amos\collaborations\models\CollaborationExpressionsOfInterest $model
 * @var yii\widgets\ActiveForm $form
 */

$js = <<<JS
let country_select = '#CollaborationExpressionsOfInterest-country-id';
let province_select = '#CollaborationExpressionsOfInterest-province-id';
let city_select = '#CollaborationExpressionsOfInterest-city-id';
let italy = 1;

// Se è già inserito un valore mostra i campi
if($(province_select).val()){
    $('#div_province').show();
} else {
    $('#div_province').hide();
}
if($(city_select).val()){
    $('#div_city').show();
} else {
    $('#div_city').hide();
}
if($('#municipality').val()){
    $('#div_municipality').show();
} else {
    $('#div_municipality').hide();
}
if($('#address').val()){
    $('#div_address').show();
} else {
    $('#div_address').hide();
}
if($('#postal_code').val()){
    $('#div_postal_code').show();
} else {
    $('#div_postal_code').hide();
}

// On change country
$(country_select).on('change', function(){
    if($(country_select).val() == italy) {
        $('#div_province').fadeIn('fast');
        $('#div_city').fadeIn('fast');
        $('#div_municipality').fadeIn('fast');
        $('#div_address').fadeIn('fast');
        $('#div_postal_code').fadeIn('fast');
    }else{
        $('#div_province').fadeOut('fast');
        $(province_select).val(null).change();
        $('#div_city').fadeOut('fast');
        $(city_select).val(null).change();
        $('#div_municipality').fadeOut('fast');
        $('#municipality').val(null).change();
        $('#div_address').fadeOut('fast');
        $('#address').val(null).change();
        $('#div_postal_code').fadeOut('fast');
        $('#postal_code').val(null).change();
    }
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

// Se ci sono errori di validazione dopo l'invio mostra i campi
if($('.form-group.field-CollaborationExpressionsOfInterest-province-id.has-error').length){
    $('#div_province').show();
}
if($('.form-group.field-CollaborationExpressionsOfInterest-city-id.has-error').length){
    $('#div_city').show();
}
if($('.form-group.field-municipality.has-error').length){
    $('#div_municipality').show();
}
if($('.form-group.field-address.has-error').length){
    $('#div_address').show();
}
if($('.form-group.field-postal_code.has-error').length){
    $('#div_postal_code').show();
}

JS;

$this->registerJs($js);
?>

<div class="collaboration-expressions-of-interest-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'collaboration-expressions-of-interest_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); 
    ?>

    <div>
        <div class="row">
            <span class="hidden">
                <!-- Proposal ID -->
                <?= $form->field($model, 'collaboration_proposal_id')->textInput(
                    [
                        'value' => $model->isNewRecord ? CollaborationProposals::findOne(\Yii::$app->request->get('id'))->id : $model->collaboration_proposal_id,
                        'readonly' => true
                    ]
                )->label(false); ?>
            </span>


            <div class="col-md-12">
                <!-- Proposal title -->
                <?= $form->field($model, 'proposal_title')->textInput(
                    [
                        'value' => $model->isNewRecord ? CollaborationProposals::findOne(\Yii::$app->request->get('id'))->title : $model->proposal_title,
                        'readonly' => true
                    ]
                ); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <!-- Proposer first name -->
                <?= $form->field($model, 'proposer_first_name')->textInput(
                    [
                        'value' => $model->isNewRecord ? UserProfile::findOne(['user_id' => Yii::$app->user->id])->nome : $model->proposer_first_name,
                        //'readonly' => true
                    ]
                ) ?>
            </div>

            <div class="col-md-2">
                <!-- Proposer last name -->
                <?= $form->field($model, 'proposer_last_name')->textInput(
                    [
                        'value' => $model->isNewRecord ? UserProfile::findOne(['user_id' => Yii::$app->user->id])->cognome : $model->proposer_last_name,
                        //'readonly' => true
                    ]
                ) ?>
            </div>

            <div class="col-md-6">
                <!-- Proposer email -->
                <?= $form->field($model, 'proposer_email')->textInput(
                    [
                        'value' => $model->isNewRecord ? User::findOne(Yii::$app->user->id)->email : $model->proposer_email,
                    ]
                ) ?>
            </div>

            <div class="col-md-2">
                <!-- Phone -->
                <?= $form->field($model, 'proposer_phone')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Company -->
                <?= $form->field($model, 'company')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- $manifestazioneinteresse -->
                <?= $form->field($model, 'manifestazioneinteresse')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <?= AmosComuniWidget::widget([
                'form' => $form,
                'model' => $model,
                // Country
                'nazioneConfig' => [
                    'attribute' => 'country',
                    'class' => 'col-md-4',
                    'id' => 'country',
                    //'options' => ['label' => $model->getAttributeLabel('country') . "<span class='text-danger'> *</span>"],
                ],
                // Province
                'provinciaConfig' => [
                    'attribute' => 'province',
                    'class' => 'col-md-4',
                    'id' => 'province',
                    'options' => [
                        'label' => $model->getAttributeLabel('province') . "<span class='text-danger'> *</span>",
                        'divId' => 'div_province',
                        'style' => 'display: none'
                    ],
                ],
                // City
                'comuneConfig' => [
                    'attribute' => 'city',
                    'class' => 'col-md-4',
                    'id' => 'city',
                    'options' => [
                        'label' => $model->getAttributeLabel('city') . "<span class='text-danger'> *</span>",
                        'divId' => 'div_city',
                        'style' => 'display: none'
                    ]
                ]
            ]) ?>
        </div>

        <div class="row">
            <div class="col-md-4" id="div_municipality" style="display: none">
                <!-- Municipality -->
                <?= $form->field($model, 'municipality')->textInput(['id' => 'municipality'])
                    ->label($model->getAttributeLabel('municipality') . "<span class='text-danger'> *</span>") ?>
            </div>

            <div class="col-md-4" id="div_address" style="display: none">
                <!-- Address -->
                <?= $form->field($model, 'address')->textInput(['id' => 'address'])
                    ->label($model->getAttributeLabel('address') . "<span class='text-danger'> *</span>") ?>
            </div>

            <div class="col-md-4" id="div_postal_code" style="display: none">
                <!-- Postal code -->
                <?= $form->field($model, 'postal_code')->textInput(['id' => 'postal_code'])
                    ->label($model->getAttributeLabel('postal_code') . "<span class='text-danger'> *</span>") ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- DOCUMENT -->
                <?= $form->field($model, 'file')->widget(
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
                            'maxFileCount' => 5, // Client max files
                            'showPreview' => false,
                            'allowedFileExtensions' => ['pdf','doc','docx'],
                        ]
                    ]
                )->hint(\Yii::t('amoscollaborations', 'Estensione supportata: pdf, doc, docx'))
                    ->label(\Yii::t('amoscollaborations', 'Documento')); ?>
                <?php if (!empty($model->file)) {
                    echo \open20\amos\attachments\components\AttachmentsList::widget([
                        'model' => $model,
                        'attribute' => 'file',
                    ]);
                } ?>
            </div>
        </div>

        <?= RequiredFieldsTipWidget::widget(); ?>
        <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-4 col xs-12"></div>
</div>
<div class="clearfix"></div>