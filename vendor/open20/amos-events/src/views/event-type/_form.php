<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-type
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventType;
use open20\amos\events\models\EventTypeContext;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\redactor\widgets\Redactor;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventType $model
 * @var yii\widgets\ActiveForm $form
 * @var string $fid
 */

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var EventType $eventTypeModel */
$eventTypeModel = $eventsModule->createModel('EventType');
/** @var EventTypeContext $eventTypeContextModel */
$eventTypeContextModel = $eventsModule->createModel('EventTypeContext');

$this->registerJs(<<<'JS'
$(".div-event_type input").on("change", function() {
    var val = $(this).val();
    if (val == 1) {
        var $chks = $(".div-limited_seats,.div-manage_subscritions_queue,.div-partners")
        $chks.find("input").prop("checked", false);
        $chks.hide();
    } else if (val == 2) {
        var $chks = $(".div-manage_subscritions_queue,.div-partners")
        $chks.find("input").prop("checked", false);
        $chks.hide();
        $chks = $(".div-limited_seats");
        $chks.show();
    } else {
        var $chks = $(".div-manage_subscritions_queue")
        $chks.find("input").prop("checked", false);
        $chks.hide();
        $chks = $(".div-limited_seats,.div-partners")
        $chks.show();
    }
});
$(".div-limited_seats input").on("change", function() {
    var $chks = $(".div-manage_subscritions_queue");
    var chk = $(this).prop("checked");
    if (chk) {
        $chks.show();
    } else {
        $chks.find("input").prop("checked", false);
        $chks.hide();
    }
});
JS
);

?>

<div class="event-type-form col-xs-12 nop">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-type_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    <?php $this->beginBlock('general'); ?>
    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'color')->widget(\kartik\color\ColorInput::className(), [
                'options' => ['placeholder' => AmosEvents::t('amosevents', 'Select/choose color') . '...'],
                'pluginOptions' => ['appendTo' => '#event-type_' . ((isset($fid)) ? $fid : 0)],
            ]) ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'event_context_id')->widget(Select2::className(), [
                'options' => ['placeholder' => AmosEvents::t('amosevents', 'Select/choose event context'), 'disabled' => false],
                'data' => ArrayHelper::map($eventTypeContextModel::find()->orderBy('id')->all(), 'id', 'title')
            ])->label(AmosEvents::t('amosevents', 'Event context')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'description')->widget(Redactor::className(), [
                'clientOptions' => [
                    'buttonsHide' => [
                        'image',
                        'file'
                    ],
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 div-event_type">
            <?= $form->field($model, 'event_type')->radioList($eventTypeModel::getTypeOptions()); ?>
        </div>
        <div class="col-lg-12 col-sm-12 div-limited_seats">
            <?= $form->field($model, 'limited_seats')->checkbox(); ?>
        </div>
        <!--<div class="col-lg-12 col-sm-12 div-manage_subscritions_queue">
            <?= $form->field($model, 'manage_subscritions_queue')->checkbox(); ?>
        </div>-->
        <div class="col-lg-12 col-sm-12 div-partners">
            <?= $form->field($model, 'partners')->checkbox(); ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>
    
    <?php $itemsTab[] = [
        'label' => AmosEvents::tHtml('amosevents', 'General'),
        'content' => $this->blocks['general'],
    ];
    ?>
    
    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
