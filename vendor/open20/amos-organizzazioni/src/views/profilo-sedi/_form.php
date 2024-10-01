<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-sedi
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\utility\OrganizzazioniUtility;
use open20\amos\organizzazioni\widgets\maps\PlaceWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\organizzazioni\models\ProfiloSedi $model
 * @var yii\widgets\ActiveForm $form
 */

$types = OrganizzazioniUtility::getProfiloSediTypesReadyForSelect();
$profiloSediTypeId = $model->profilo_sedi_type_id;
if ($model->isNewRecord && (count($types) == 1)) {
    $typeIds = array_keys($types);
    $profiloSediTypeId = reset($typeIds);
}

/** @var Module $organizzazioniModule */
$organizzazioniModule = Yii::$app->getModule(Module::getModuleName());

?>

<div class="profilo-sedi-form col-xs-12 nop">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data', // To load images
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <?php $this->beginBlock('general'); ?>
    <div class="row">
        <div class="col-lg-8 col-sm-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'profilo_sedi_type_id')->widget(Select::className(), [
                'data' => $types,
                'options' => [
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'multiple' => false,
                    'placeholder' => Module::t('amosorganizzazioni', 'Select/Chooes') . '...',
                    'value' => $profiloSediTypeId
                ]
            ])->label($model->getAttributeLabel('profiloSediType')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <?php if (!$organizzazioniModule->oldStyleAddressEnabled): ?>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'address')->widget(
                    PlaceWidget::className(), [
                        'placeAlias' => 'sedeIndirizzo'
                    ]
                ); ?>
            </div>
        <?php else: ?>
            <?= $this->render('@vendor/open20/amos-organizzazioni/src/views/profilo-sedi/_old_style_address_fields', ['form' => $form, 'modelSedi' => $model]); ?>
        <?php endif; ?>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#email_field_placeholder')]) ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'pec')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#pec_field_placeholder')]) ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#telefono_field_placeholder')]) ?>
        </div>
        <div class="col-md-6 col-xs-12">
            <?= $form->field($model, 'fax')->textInput(['maxlength' => true, 'placeholder' => Module::t('amosorganizzazioni', '#fax_field_placeholder')]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => Module::t('amosorganizzazioni', 'Generale'),
        'content' => $this->blocks['general'],
    ];
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CloseSaveButtonWidget::widget([
        'model' => $model,
        'urlClose' => ['/organizzazioni/profilo/update', 'id' => $model->profilo_id],
        'closeButtonLabel' => Module::t('amosorganizzazioni', '#go_back')
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
