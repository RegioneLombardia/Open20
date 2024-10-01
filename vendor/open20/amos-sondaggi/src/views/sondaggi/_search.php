<?php

use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\utility\SondaggiUtility;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\sondaggi\models\Sondaggi;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="sondaggi-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'class' => 'row'
    ]); ?>
    
    <div class="col-xs-12">
        <p class="h3"><?= AmosSondaggi::tHtml('amossondaggi', 'Search by') ?></p>
    </div>
    
    <div class="col-xs-12 col-sm-4"><?php echo $form->field($model, 'titolo') ?></div>
    <div class="col-xs-12 col-sm-4"><?php echo $form->field($model, 'descrizione') ?></div>

    <div class="col-xs-12 col-sm-4">
        <?= $form->field($model, 'status')->dropdownList(SondaggiUtility::getSearchStatuses()); ?>
    </div>
   
    <div class="col-xs-12 col-sm-6"><?php echo $form->field($model, 'date_from')->widget(\kartik\datecontrol\DateControl::className(), [
            'type' => \kartik\datecontrol\DateControl::FORMAT_DATE
        ]) ?>
    </div>
    
    <div class="col-xs-12 col-sm-6"><?php echo $form->field($model, 'date_to')->widget(\kartik\datecontrol\DateControl::className(), [
            'type' => \kartik\datecontrol\DateControl::FORMAT_DATE
        ]) ?>
    </div>
    <?php if (AmosSondaggi::instance()->differentiateClosed): ?>
        <div class="col-sm-6">
            <?= $form->field($model, 'closed')->checkbox(); ?>
        </div>
    <?php endif; ?>
  
    <?php // echo $form->field($model, 'sondaggi_id') ?>

    <?php // echo $form->field($model, 'ordinamento') ?>

    <?php // echo $form->field($model, 'sondaggi_domande_pagine_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'version') ?>

    <div class="col-xs-12">
        <div class="pull-right">
        <?= Html::a(AmosSondaggi::tHtml('amossondaggi', 'Reset'), [''], ['class' => 'btn btn-secondary']) ?>    
        <?= Html::submitButton(AmosSondaggi::tHtml('amossondaggi', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>
