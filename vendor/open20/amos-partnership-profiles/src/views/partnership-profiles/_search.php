<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\Module;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\search\PartnershipProfilesSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleTag = Yii::$app->getModule('tag');

$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;
?>
<div class="<?= Yii::$app->controller->id ?>-search element-to-toggle" data-toggle-element="form-search">

    <?php
    $form = ActiveForm::begin([
        'action' => Yii::$app->controller->action->id,
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    <?= Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);  ?>
    <?= Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView')); ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= Module::tHtml('amospartnershipprofiles', 'Search by'); ?>:
        </h2>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'title')->textInput(['placeholder' => Module::t('amospartnershipprofiles', 'Search by title')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'short_description')->textInput(['placeholder' => Module::t('amospartnershipprofiles', 'Search by short description')]) ?>
    </div>

    <?php if (isset($moduleTag) && in_array(Module::instance()->model('PartnershipProfiles'), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
        <div class="col-xs-12">
            <?php
            $params = \Yii::$app->request->getQueryParams();
            echo \open20\amos\tag\widgets\TagWidget::widget([
                'model' => $model,
                'attribute' => 'tagValues',
                'form' => $form,
                'isSearch' => true,
                'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
            ]);
            ?>
        </div>
    <?php endif; ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Module::tHtml('amospartnershipprofiles', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::tHtml('amospartnershipprofiles', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Advanced search<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>
</div>
