<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\organizzazioni\Module;
use open20\amos\tag\AmosTag;
use open20\amos\tag\widgets\TagWidget;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\organizzazioni\models\search\ProfiloSearch $model
 * @var yii\widgets\ActiveForm $form
 */

/** @var AmosTag $moduleTag */
$moduleTag = Yii::$app->getModule('tag');


$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;
$actionRoute = '/' . Module::getModuleName() . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
?>
<div class="<?= Yii::$app->controller->id ?>-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => $actionRoute,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel); ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>

    <div class="col-xs-12 m-t-20 m-b-10">
        <h2 class="title">
            <?= Module::tHtml('amosorganizzazioni', 'Search by'); ?>:
        </h2>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'name')->textInput(['placeholder' => Module::t('amosorganizzazioni', '#search_placeholder_name')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'partita_iva')->textInput(['placeholder' => Module::t('amosorganizzazioni', '#search_placeholder_partita_iva')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'istat_code')->textInput(['placeholder' => Module::t('amosorganizzazioni', '#search_placeholder_istat_code')]) ?>
    </div>

    <?php if (isset($moduleTag) && in_array(Module::instance()->model('Profilo'), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
        <div class="col-xs-12 m-t-10 m-b-20">
            <?php
            $params = \Yii::$app->request->getQueryParams();
            echo TagWidget::widget([
                'model' => $model,
                'attribute' => 'tagValues',
                'form' => $form,
                'isSearch' => true,
                'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="col-xs-12 m-b-20">
        <div class="pull-right">
            <?= Html::a(Module::tHtml('amosorganizzazioni', 'Reset'), [$actionRoute], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::tHtml('amosorganizzazioni', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
