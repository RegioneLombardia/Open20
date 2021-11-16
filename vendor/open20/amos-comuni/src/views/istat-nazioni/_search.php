<?php

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\search\IstatNazioniSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="istat-nazioni-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2>Cerca per:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]); ?>

    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'id') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'nome') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'nome_inglese') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'area') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'unione_europea') ?></div> <?php // echo $form->field($model, 'istat_continenti_id') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton('Search', ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Ricerca avanzata<br>
                < ?=AmosIcons::show('caret-down-circle');?>
            </p></a-->
    <?php ActiveForm::end(); ?>

</div>
