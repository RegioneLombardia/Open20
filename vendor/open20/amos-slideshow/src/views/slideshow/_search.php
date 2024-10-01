<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\slideshow\AmosSlideshow;

/**
 * @var yii\web\View $this
 * @var open20\amos\slideshow\models\search\SlideshowSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="slideshow-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosSlideshow::tHtml('amosslideshow', 'Cerca per:') ?></h2></div>

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]); ?>

    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'id') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'name') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'description') ?></div>
    <div class="col-sm-6 col-lg-4">    <?= $form->field($model, 'created_at') ?></div>
    <div
        class="col-sm-6 col-lg-4">    <?= $form->field($model, 'updated_at') ?></div> <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosSlideshow::t('amosslideshow', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosSlideshow::t('amosslideshow', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center"></?= AmosSlideshow::t('amosslideshow', 'Ricerca avanzata:\') ?><br>
                < ?=AmosIcons::show('caret-down-circle');?>
            </p></a-->
    <?php ActiveForm::end(); ?>

</div>
