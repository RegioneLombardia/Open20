<?php

use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sondaggi-domande-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'risposta_predefinita') ?>

    <?= $form->field($model, 'riposta_multipla') ?>

    <?= $form->field($model, 'domanda_condizionata') ?>

    <?= $form->field($model, 'domanda') ?>

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

    <div class="form-group">
        <?= Html::submitButton(AmosSondaggi::tHtml('amossondaggi', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        <?= Html::resetButton(AmosSondaggi::tHtml('amossondaggi', 'Reset'), ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
