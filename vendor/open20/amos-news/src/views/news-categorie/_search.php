<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-categorie
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\search\NewsCategorieSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="news-categorie-search">
    <div class="col-xs-12"><h2><?= AmosNews::t('amosnews', 'Cerca per') ?>:</h2></div>
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
            <?= Html::resetButton(AmosNews::t('amosnews', 'Annulla'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNews::t('amosnews', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--a><p class="text-center">Ricerca avanzata<br>
        < ?=AmosIcons::show('caret-down-circle');?>
    </p></a-->
    <?php ActiveForm::end(); ?>

</div>
