<?php

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\comuni\models\search\IstatComuniSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="istat-comuni-search element-to-toggle" data-toggle-element="form-search">
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
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'progressivo') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'nome_tedesco') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'cod_ripartizione_geografica') ?></div> <?php // echo $form->field($model, 'ripartizione_geografica') ?>

    <?php // echo $form->field($model, 'comune_capoluogo_provincia') ?>

    <?php // echo $form->field($model, 'cod_istat_alfanumerico') ?>

    <?php // echo $form->field($model, 'codice_2006_2009') ?>

    <?php // echo $form->field($model, 'codice_1995_2005') ?>

    <?php // echo $form->field($model, 'codice_catastale') ?>

    <?php // echo $form->field($model, 'popolazione_20111009') ?>

    <?php // echo $form->field($model, 'codice_nuts1_2010') ?>

    <?php // echo $form->field($model, 'codice_nuts2_2010') ?>

    <?php // echo $form->field($model, 'codice_nuts3_2010') ?>

    <?php // echo $form->field($model, 'codice_nuts1_2006') ?>

    <?php // echo $form->field($model, 'codice_nuts2_2006') ?>

    <?php // echo $form->field($model, 'codice_nuts3_2006') ?>

    <?php // echo $form->field($model, 'soppresso') ?>

    <?php // echo $form->field($model, 'istat_unione_dei_comuni_id') ?>

    <?php // echo $form->field($model, 'istat_regioni_id') ?>

    <?php // echo $form->field($model, 'istat_province_id') ?>

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
