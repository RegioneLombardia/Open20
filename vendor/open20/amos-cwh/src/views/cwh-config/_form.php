<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\cwh\AmosCwh;
use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var open20\amos\cwh\models\CwhConfig $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<div class="cwh-config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $this->beginBlock('generale'); ?>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'classname')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'tablename')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-6 col-sm-6">
        <?= $form->field($model, 'visibility')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-6 col-sm-6">
        <?= AmosCwh::t('amoscwh', '#visibility_hint') ?>
    </div>

    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php $itemsTab[] = [
        'label' => AmosCwh::t('amoscwh', 'generale '),
        'content' => $this->blocks['generale'],
    ];
    ?>

    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?= CloseSaveButtonWidget::widget([
            'model' => $model
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
