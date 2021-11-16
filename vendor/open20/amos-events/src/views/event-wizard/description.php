<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-wizard
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\comuni\models\IstatNazioni;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventLengthMeasurementUnit;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\utility\EventsUtility;
use kartik\datecontrol\DateControl;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\redactor\widgets\Redactor;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var \open20\amos\events\models\Event $model
 */

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

$beginDateHourId = lcfirst(Inflector::id2camel(\yii\helpers\StringHelper::basename($model->className()), '_')) . '-begin_date_hour' . ((isset($fid)) ? $fid : 0);

$js = "
    function calcEndDateHour() {
        if (($('#" . $beginDateHourId . "').val() != '') && ($('#event-length').val() != '') && ($('#EventLengthMeasurementUnit').val() != '')) {
            var dataArray = {
                beginDateHour: $('#" . $beginDateHourId . "').val(),
                lengthValue: $('#event-length').val(),
                lengthMUId: $('#EventLengthMeasurementUnit').val()
            };
            $.ajax({
                url: '" . Url::to(['event/calculate-end-date-hour']) . "',
                type: 'post',
                data: dataArray,
                dataType: 'json',
                success: function (response) {
                    $('#event-end_date_hour').val(response.datetime);
                    $('#elem-end-date').html(response.date);
                    $('#elem-end-hour').html(response.time);
                }
            });
        } else {
                $('#event-end_date_hour').val('');
                $('#elem-end-date').html('-');
                $('#elem-end-hour').html('-');
        }
    }

    $('#" . $beginDateHourId . "').on('change', function (event) {
        calcEndDateHour();
    });

    $('#event-length').on('change', function (event) {
        calcEndDateHour();
    });

    $('#EventLengthMeasurementUnit').on('change', function (event) {
        calcEndDateHour();
    });
    
    calcEndDateHour();
    
    $('#country_location_id-id').change(function(){
        if ($('#country_location_id-id').val() == 1) {
            $('#province_location_id-id').prop('disabled', '');
        } else {
            $('#select2-province_location_id-id-container').text('" . AmosEvents::t('amosevents', 'Type the name of the province') . "');
            $('#select2-city_location_id-id-container').text('" . AmosEvents::t('amosevents', 'Select/Choose') . '...' . "');
            $('#province_location_id-id').prop('disabled', 'disabled');
            $('#city_location_id-id').prop('disabled', 'disabled');
        }
    });
";
$this->registerJs($js, View::POS_READY);

$this->title = AmosEvents::t('amosevents',"Nuovo Evento");

/** @var EventLengthMeasurementUnit $eventLengthMeasurementUnitModel */
$eventLengthMeasurementUnitModel = $moduleEvents->createModel('EventLengthMeasurementUnit');

?>

<div class="event-wizard-description">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-wizard-form',
            'class' => 'form',
            'enctype' => 'multipart/form-data', //to load images
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>

    <section>
        <h1><?= AmosIcons::show('calendar'); ?><?= AmosEvents::tHtml('amosevents', 'Event') ?></h1>
        <div class="row">
            <div class="col-lg-8 col-sm-8">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="col-xs-12 pull-right">
                    <?= $form->field($model, 'eventLogo')->widget(AttachmentsInput::classname(), [
                        'options' => [ // Options of the Kartik's FileInput widget
                            'multiple' => false, // If you want to allow multiple upload, default to false
                        ],
                        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                            'maxFileCount' => 1 // Client max files
                        ]
                    ])->label($model->getAttributeLabel('eventLogo')) ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'event_commentable')->dropDownList(Html::getBooleanFieldsValues(), [
                        'prompt' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                        'disabled' => false
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'description')->widget(Redactor::className(), [
                    'clientOptions' => [
                        'buttonsHide' => [
                            'image',
                            'file'
                        ],
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'placeholder' => AmosEvents::t('amosevents', 'Insert the event description'),
                    ],
                ]); ?>
            </div>
        </div>
    </section>

    <section>
        <h1><?= AmosIcons::show('compass'); ?><?= AmosEvents::tHtml('amosevents', 'Location') ?></h1>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'event_location')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'event_address')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3 col-sm-3">
                <?= $form->field($model, 'event_address_house_number')->textInput(['maxlength' => true])->label(AmosEvents::tHtml('amosevents', 'House Number')) ?>
            </div>
            <div class="col-lg-3 col-sm-3">
                <?= $form->field($model, 'event_address_cap')->textInput(['maxlength' => true])->label(AmosEvents::tHtml('amosevents', 'CAP')) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-4">
                <div class="select">
                    <?=
                    $form->field($model, 'country_location_id')->widget(Select2::classname(), [
                        'options' => ['placeholder' => AmosEvents::t('amosevents', 'Type the name of the country'), 'id' => 'country_location_id-id', 'disabled' => false],
                        'data' => ArrayHelper::map(IstatNazioni::find()->orderBy('nome')->asArray()->all(), 'id', 'nome')
                    ])->label(AmosEvents::tHtml('amosevents', 'Country Location'));
                    ?>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="select">
                    <?=
                    $form->field($model, 'province_location_id')->widget(Select2::classname(), [
                        'options' => ['placeholder' => AmosEvents::t('amosevents', 'Type the name of the province'), 'id' => 'province_location_id-id', 'disabled' => false],
                        'data' => ArrayHelper::map(IstatProvince::find()->orderBy('nome')->asArray()->all(), 'id', 'nome')
                    ])->label(AmosEvents::tHtml('amosevents', 'Province Location'));
                    ?>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="select">
                    <?=
                    $form->field($model, 'city_location_id')->widget(DepDrop::classname(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'data' => $model->city_location_id ? [$model->city_location_id => $model->city_location_id] : [],
                        'options' => ['id' => 'city_location_id-id', 'disabled' => FALSE],
                        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                        'pluginOptions' => [
                            'depends' => [(FALSE) ?: 'province_location_id-id'],
                            'placeholder' => [AmosEvents::t('amosevents', 'Select/Choose') . '...'],
                            'url' => Url::to(['/comuni/default/comuni-by-provincia', 'soppresso' => 0]),
                            'initialize' => true,
                            'params' => ['city_location_id-id'],
                        ],
                    ])->label(AmosEvents::tHtml('amosevents', 'City Location'));
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h1><?= AmosIcons::show('time') ?><?= AmosEvents::tHtml('amosevents', 'Date And Hour') ?></h1>
        <div class="row">
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'begin_date_hour')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATETIME,
                    'options' => [
                        'id' => $beginDateHourId,
                        'layout' => '{input} {picker} ' . (($model->begin_date_hour == '') ? '' : '{remove}')]
                ]); ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'length')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'length_mu_id')->widget(Select2::className(), [
                    'data' => EventsUtility::translateArrayValues(ArrayHelper::map($eventLengthMeasurementUnitModel::find()->asArray()->all(), 'id', 'title')),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => ['multiple' => false,
                        'id' => 'EventLengthMeasurementUnit',
                        'placeholder' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                        'class' => 'dynamicCreation',
                        'data-model' => 'event_length_measurement_unit',
                        'data-field' => 'title',
                        'data-module' => 'events',
                        'data-entity' => 'event-length-measurement-unit',
                        'data-toggle' => 'tooltip'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        "select2:open" => "dynamicInsertOpening"
                    ]
                ])->label(AmosEvents::tHtml('amosevents', 'Length Measurement Unit'))
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-4">
                <label><?= AmosEvents::tHtml('amosevents', 'End Date') ?></label>
                <div id="elem-end-date"></div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <label><?= AmosEvents::tHtml('amosevents', 'End Hour') ?></label>
                <div id="elem-end-hour"></div>
            </div>
            <?= $form->field($model, 'end_date_hour')->hiddenInput()->label(false) ?>
        </div>
    </section>

    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/event-wizard/introduction', 'id' => $model->id]),
        'cancelUrl' => Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
