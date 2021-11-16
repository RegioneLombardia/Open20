<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-type
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventTypeContext;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\search\EventTypeSearch $model
 * @var yii\widgets\ActiveForm $form
 */

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var EventTypeContext $eventTypeContextModel */
$eventTypeContextModel = $eventsModule->createModel('EventTypeContext');

?>
<div class="event-type-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'title')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by title')]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'description')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by description')]) ?>
    </div>

    <div class="col-sm-6 col-lg-4"><!-- TODO to replace with the color input used in the form -->
        <?= $form->field($model, 'color')->textInput(['placeholder' => AmosEvents::t('amosevents', 'Search by color')]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'event_context_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map($eventTypeContextModel::find()->orderBy('id')->asArray()->all(), 'id', 'title'),
            'language' => substr(Yii::$app->language, 0, 2),
            'options' => ['multiple' => false,
                'placeholder' => AmosEvents::t('amosevents', 'Select/choose event context'),
                'class' => 'dynamicCreation',
                'data-model' => 'event-type-context',
                'data-field' => 'id',
                'data-module' => 'events',
                'data-entity' => 'event-type-context',
                'data-toggle' => 'tooltip'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "select2:open" => "dynamicInsertOpening"
            ]
        ])->label(AmosEvents::tHtml('amosevents', 'Event context')) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'locationRequested')->checkbox(['label' => AmosEvents::t('amosevents', 'Search by location requested')]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'durationRequested')->checkbox(['label' => AmosEvents::t('amosevents', 'Search by duration requested')]) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'logoRequested')->checkbox(['label' => AmosEvents::t('amosevents', 'Search by logo requested')]) ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(AmosEvents::t('amosevents', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(AmosEvents::t('amosevents', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <!--a><p class="text-center">Advanced search<br>
            < ?=AmosIcons::show('caret-down-circle');?>
        </p></a-->
    <?php ActiveForm::end(); ?>
</div>
