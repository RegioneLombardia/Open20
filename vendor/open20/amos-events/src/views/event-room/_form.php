<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-room
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\events\AmosEvents;
use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\EventRoom $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="event-room-form">
    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // important
    ]);
    ?>

    <?php $this->beginBlock('general'); ?>
    <div class="row">
        <div class="col-lg-9 col-sm-9">
            <?= $form->field($model, 'room_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3 col-sm-3">
            <?= $form->field($model, 'available_seats')->textInput() ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php
    $itemsTab[] = [
        'label' => AmosEvents::t('amosevents', 'General'),
        'content' => $this->blocks['general'],
    ];
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget([
        'model' => $model
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
