<?php

use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\sondaggi\AmosSondaggi;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\SondaggiDomandeTipologie $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sondaggi-domande-tipologie-form">
    
    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]); ?>
    
    <?php $this->beginBlock('generale'); ?>
    <div class="row">
        <div class="col-lg-8 col-sm-8">
            <?= $form->field($model, 'tipologia')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'attivo')->dropDownList([0 => 'No', 1 => 'Si']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'descrizione')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>
    
    <?php
    $itemsTab[] = [
        'label' => AmosSondaggi::t('amossondaggi', 'generale '),
        'content' => $this->blocks['generale'],
    ];
    ?>
    
    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    ); ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <div id="form-actions" class="bk-btnFormContainer">
        <?= Html::a(AmosSondaggi::tHtml('amossondaggi', 'Chiudi'), Url::previous(), [
            'class' => 'btn btn-warning'
        ]); ?>
        <?= Html::submitButton($model->isNewRecord ? AmosSondaggi::tHtml('amossondaggi', 'Inserisci') : AmosSondaggi::tHtml('amossondaggi', 'Salva'), [
            'class' => $model->isNewRecord ?
                'btn btn-success' :
                'btn btn-primary'
        ]); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
