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
 * @var \open20\amos\discussioni\models\search\DiscussioniTopicSearch $model
 * @var yii\widgets\ActiveForm $form
 */
$currentView = Yii::$app->request->getQueryParam('currentView');
?>

<div class="discussioni-topic-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <h2><?= AmosDiscussioni::t('amosdiscussioni', 'Ordina per') ?>:</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    echo Html::hiddenInput("currentView", $currentView); ?>
    
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    
    <div class="col-sm-6 col-lg-4">
    <?= $form->field($model, 'orderType')->dropDownList([
        SORT_ASC => AmosDiscussioni::t('amosdiscussioni', 'Crescente'),
        SORT_DESC => AmosDiscussioni::t('amosdiscussioni', 'Decrescente'),
    ])
    ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
        <?= Html::a(
            AmosDiscussioni::t('amosdiscussioni', 'Annulla'), 
            [Yii::$app->controller->action->id, 'currentView' => $currentView],
            ['class'=>'btn btn-secondary']) ?>
        <?= Html::submitButton(AmosDiscussioni::t('amosdiscussioni', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>
