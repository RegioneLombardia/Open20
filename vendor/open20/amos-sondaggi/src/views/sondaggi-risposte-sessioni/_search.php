<?php

use open20\amos\sondaggi\AmosSondaggi;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\sondaggi\models\search\SondaggiRisposteSessioniSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="sondaggi-risposte-sessioni-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'session_id') ?>

    <?= $form->field($model, 'unique_id') ?>

    <?= $form->field($model, 'begin_date') ?>

    <?= $form->field($model, 'end_date') ?>

    <?php // echo $form->field($model, 'session_tmp') ?>

    <?php // echo $form->field($model, 'user_profile_id') ?>

    <?php // echo $form->field($model, 'sondaggi_id') ?>

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
