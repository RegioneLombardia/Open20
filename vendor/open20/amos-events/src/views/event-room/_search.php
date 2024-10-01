<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-room
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\search\EventRoomSearch $searchModel
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="event-room-search element-to-toggle" data-toggle-element="form-search">
    <?php
    $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <?= Html::hiddenInput("enableSearch", "1") ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= AmosEvents::t('amosevents', 'Search'); ?>:
        </h2>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'room_name')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by room name')]) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'available_seats')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by available seats')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosEvents::t('amosevents', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosEvents::t('amosevents', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
