<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\search\EventTypeSearch;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\search\EventSearch $model
 * @var yii\widgets\ActiveForm $form
 */

$moduleTag = Yii::$app->getModule('tag');

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

$idPrefix = "eventsearch";
$this->registerJs("
    $('#" . $idPrefix . "-begin_date_hour').change(function () {
        if ($('#" . $idPrefix . "-begin_date_hour').val() == '') {
            $('#" . $idPrefix . "-begin_date_hour-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
            if ($('#" . $idPrefix . "-begin_date_hour-disp-kvdate .input-group-addon.kv-date-remove').length == 0) {
                $('#" . $idPrefix . "-begin_date_hour-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"" . AmosEvents::t('amosevents', 'Clean field') . "\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
                initDPRemove('" . $idPrefix . "-begin_date_hour-disp');
            }
        }
    });
    $('#" . $idPrefix . "-end_date_hour').change(function () {
        if ($('#" . $idPrefix . "-end_date_hour').val() == '') {
            $('#" . $idPrefix . "-end_date_hour-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
            if ($('#" . $idPrefix . "-end_date_hour-disp-kvdate .input-group-addon.kv-date-remove').length == 0) {
                $('#" . $idPrefix . "-end_date_hour-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"" . AmosEvents::t('amosevents', 'Clean field') . "\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
                initDPRemove('" . $idPrefix . "-end_date_hour-disp');
            }
        }
    });
", yii\web\View::POS_READY);
?>
<div class="event-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);

    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>

    <div class="col-md-4">
        <?= $form->field($model, 'title')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by title')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'description')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by description')]) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'event_type_id')->widget(Select2::className(), [
            'data' => EventTypeSearch::searchEnabledGenericContextEventTypesReadyForSelect(),
            'language' => substr(Yii::$app->language, 0, 2),
            'options' => [
                'multiple' => true,
                'id' => 'EventType',
                'placeholder' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                'class' => 'dynamicCreation',
                'data-model' => 'event_type',
                'data-field' => 'name',
                'data-module' => 'events',
                'data-entity' => 'event-type',
                'data-toggle' => 'tooltip'
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ]
        ])->label($model->getAttributeLabel('eventType')) ?>
    </div>

    <div class="col-md-4">
        <?= $form->field($model, 'begin_date_hour')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'layout' => '{input} {picker} ' . (($model->begin_date_hour == '') ? '' : '{remove}')
            ]
        ]); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'end_date_hour')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE,
            'options' => [
                'layout' => '{input} {picker} ' . (($model->end_date_hour == '') ? '' : '{remove}')
            ]
        ]); ?>
    </div>

    <?php if (isset($moduleTag) && in_array($eventsModule->model('Event'), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
        <div class="col-xs-12">
            <?php
            $params = \Yii::$app->request->getQueryParams();
            /*echo \open20\amos\tag\widgets\TagWidget::widget([
                'model' => $model,
                'attribute' => 'tagValues',
                'form' => $form,
                'isSearch' => true,
                'form_values' => isset($params[$model->formName()]['tagValues']) ? $params[$model->formName()]['tagValues'] : []
            ]);*/
            ?>
        </div>
    <?php endif; ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosEvents::t('amosevents', 'Cancel'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosEvents::t('amosevents', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Advanced search<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>
</div>
