<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\report\models\search\ReportSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="report-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <h2><?= AmosNews::t('amosreport', 'Ordina per') ?>:</h2>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
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
                SORT_ASC => AmosNews::t('amosreport', 'Crescente'),
                SORT_DESC => AmosNews::t('amosreport', 'Decrescente'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosNews::t('amosreport', 'Annulla'), [Yii::$app->controller->action->id], ['class'=>'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosNews::t('amosreport', 'Ordina'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>