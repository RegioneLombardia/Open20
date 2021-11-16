<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\newsletter
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\notificationmanager\AmosNotify;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\notificationmanager\models\search\NewsletterSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="newsletter-search element-to-toggle" data-toggle-element="form-search">
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
            <?= AmosNotify::txt('Search'); ?>:
        </h2>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'subject')->textInput() ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosNotify::txt('Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNotify::txt('Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
