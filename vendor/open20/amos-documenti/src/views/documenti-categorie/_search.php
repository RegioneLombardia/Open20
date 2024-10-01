<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-categorie
 * @category   CategoryName
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\search\DocumentiCategorieSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="documenti-categorie-search">
    <div class="col-xs-12"><p class="h3"><?= AmosDocumenti::tHtml('amosdocumenti', 'Cerca per') ?>:</p></div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="col-sm-6 col-lg-4"> 
        <?= $form->field($model, 'id') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'sottotitolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'descrizione_breve') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'descrizione') ?>
    </div>

    <?php // echo $form->field($model, 'filemanager_mediafile_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'version') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosDocumenti::tHtml('amosdocumenti', 'Resetta'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosDocumenti::tHtml('amosdocumenti', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->
    <?php ActiveForm::end(); ?>

</div>
