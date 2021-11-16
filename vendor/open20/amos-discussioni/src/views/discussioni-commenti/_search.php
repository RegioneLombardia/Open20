<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\discussioni\AmosDiscussioni;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\search\DiscussioniCommentiSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="discussioni-commenti-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosDiscussioni::tHtml('amosdiscussioni', 'Cerca per') ?>:</h2></div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'id') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'testo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'discussioni_risposte_id') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'created_at') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'updated_at') ?>
    </div>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'version') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosDiscussioni::t('amosdiscussioni', 'Annulla'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosDiscussioni::t('amosdiscussioni', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->

    <?php ActiveForm::end(); ?>

</div>
