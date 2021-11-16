<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-newsletter/src/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;



?>
<div class="service-email-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <div class="col-xs-12"><h2><?= \amos\newsletter\Module::t('amosnewsletter', 'Cerca per') ?>:</h2></div>



    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <?php foreach($mailService->getSearchField() as $attribute){ ?>
    <div class="col-md-4">
        <label><?= \amos\newsletter\Module::t('amosnewsletter', $attribute)?></label>
        <?= Html::textInput("ServiceEmail[$attribute]",null,['class' => 'form-control']) ?>
    </div>
    <?php } ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Yii::t('amoscore', 'Reset'),
                \Yii::$app->controller->action->id == 'view-group'
                    ? [\Yii::$app->controller->action->id, 'idGroup' => $_GET['idGroup']]
                    : [\Yii::$app->controller->action->id, 'idList' => $_GET['idList']],
                    ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
