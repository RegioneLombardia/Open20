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
use open20\amos\discussioni\models\DiscussioniTopic;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use open20\amos\admin\models\UserProfile;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\search\DiscussioniTopicSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleTag = Yii::$app->getModule('tag');
$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) 
    || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;

$currentView = Yii::$app->request->getQueryParam('currentView');
?>
<div class="discussioni-topic-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><h2><?= AmosDiscussioni::tHtml('amosdiscussioni', 'Cerca per') ?>:</h2></div>

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'id' => 'discussioni-topic_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
        ]
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    echo Html::hiddenInput("currentView", $currentView);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'titolo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'testo') ?>
    </div>

    <div class="col-sm-6 col-lg-4">
    <?= $form->field($model, 'created_by')->widget(\kartik\select2\Select2::className(), [
        'data' => (!empty($model->created_by) 
            ? [$model->created_by => UserProfile::findOne(['user_id' => $model->created_by])->getNomeCognome()] 
            : []
        ),
        'options' => ['placeholder' => AmosDiscussioni::t('amosdiscussioni', 'Cerca ...')],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => \yii\helpers\Url::to(['/admin/user-profile-ajax/ajax-user-list']),
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
            ],
        ],
    ]);
    ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'created_at')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'updated_at')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ]) ?>
    </div>

    <?php if (isset($moduleTag) && in_array(DiscussioniTopic::className(), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
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
        <?= Html::a(
            AmosDiscussioni::t('amosdiscussioni', 'Annulla'), 
            [Yii::$app->controller->action->id, 'currentView' => $currentView],
            ['class'=>'btn btn-secondary']) ?>
        <?= Html::submitButton(AmosDiscussioni::t('amosdiscussioni', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>
