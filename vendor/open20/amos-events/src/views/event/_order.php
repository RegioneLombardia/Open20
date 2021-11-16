<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\events\AmosEvents;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<div class="news-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <h2><?= AmosEvents::t('amosnews', 'Ordina per') ?>:</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>
    
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosEvents::t('amosnews', 'Crescente'),
                SORT_DESC => AmosEvents::t('amosnews', 'Decrescente'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosEvents::t('amosnews', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosEvents::t('amosnews', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>