<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\components\CropInput;
use open20\amos\comuni\widgets\helpers\AmosComuniWidget;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\cwh\widgets\DestinatariPlusTagWidget;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsFilesAsset;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventLengthMeasurementUnit;
use open20\amos\events\models\EventType;
use open20\amos\events\models\search\EventTypeSearch;
use open20\amos\events\utility\EventsUtility;
use open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use kartik\alert\Alert;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $model
 * @var int $countEventTypes
 * @var bool $enableAgid
 * @var open20\amos\events\models\EventInvitationsUpload $upload
 * @var yii\widgets\ActiveForm $form
 * @var string|null $fid
 * @var string|null $dataField
 * @var open20\amos\cwh\AmosCwh $moduleCwh
 * @var array $scope
 */

/** @var AmosEvents $moduleEvents */
$moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
$moduleTag = \Yii::$app->getModule('tag');
$moduleSeo = \Yii::$app->getModule('seo');

$hideSeoModuleClass = $moduleEvents->hideSeoModule ? ' hidden' : '';
$enableGalleryAttachment = $moduleEvents->enableGalleryAttachment;
$enableRelatedEvents = $moduleEvents->enableRelatedEvents;

$this->registerJs("
    $('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "').change(function(){
        if($('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "').val() == ''){
            $('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
            if($('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
                $('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
                initDPRemove('event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp');
            }
        }
    });
", yii\web\View::POS_READY);

$this->registerJs(<<<JS
    setTimeout(function() {
      if(window.location.toString().includes('#w8')) {
        $("#w8 #ui-id-3").click();
        $("#w5 #ui-id-1").click();
    }
    }, 500);
JS
    , yii\web\View::POS_LOAD);

$eventManagementFieldId = Html::getInputId($model, 'event_management');
$registrationLimitDateFieldId = Html::getInputId($model, 'registration_limit_date') . '-disp';
$eventMembershipTypeIdFieldId = Html::getInputId($model, 'event_membership_type_id');
$seatsAvailableFieldId = Html::getInputId($model, 'seats_available');
$paidEventFieldId = Html::getInputId($model, 'paid_event');
$eventRoomFieldId = Html::getInputId($model, 'event_room_id');

$dateHourPrefix = lcfirst(Inflector::id2camel(\yii\helpers\StringHelper::basename($model->className()), '_'));
$dateHourSuffix = ((isset($fid)) ? $fid : 0);
$beginDateHourId = $dateHourPrefix . '-begin_date_hour' . $dateHourSuffix;
$endDateHourId = $dateHourPrefix . '-end_date_hour' . $dateHourSuffix;

$eventType = $model->eventType;
$eventTypePresent = !is_null($eventType);
if ($eventTypePresent) {
    $eventTypeWithLimitedSeats = $eventType->limited_seats;
    $eventTypeInformative = ($eventType->event_type == EventType::TYPE_INFORMATIVE);
} else {
    $eventTypeWithLimitedSeats = false;
    $eventTypeInformative = false;
}
$moduleNotify = \Yii::$app->getModule('notify');

$js = "
    function calcEndDateHour() {
        if (($('#" . $beginDateHourId . "').val() != '') && ($('#event-length').val() != '') && ($('#event-length').val() > 0) && ($('#EventLengthMeasurementUnit').val() != '')) {
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
    " . (!$moduleEvents->freeSelectEndOfTheEvent ? "
    $('#" . $beginDateHourId . "').on('change', function (event) {
        calcEndDateHour();
    });

    $('#event-length').on('change', function (event) {
        calcEndDateHour();
    });

    $('#EventLengthMeasurementUnit').on('change', function (event) {
        calcEndDateHour();
    });
    
    calcEndDateHour();" : "") . "
    
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
    
    function disableEventManagementFields() {
        if ($('#" . $eventManagementFieldId . "').val() == 0) {
            $('#" . $registrationLimitDateFieldId . "').val('').prop('disabled', true);
            $('#" . $eventMembershipTypeIdFieldId . "').val('1').trigger('change').prop('disabled', true);
            $('#" . $seatsAvailableFieldId . "').val('').prop('disabled', true);
            $('#" . $paidEventFieldId . "').val('').prop('disabled', true);
            
        }
        if ($('#" . $eventManagementFieldId . "').val() == 1) {
            $('#" . $registrationLimitDateFieldId . "').prop('disabled', false);
            $('#" . $eventMembershipTypeIdFieldId . "').prop('disabled', false);
            $('#" . $seatsAvailableFieldId . "').prop('disabled', false);
            $('#" . $paidEventFieldId . "').prop('disabled', false);
        }
    }
    
    function changeEventManagementRequiredFieldsAsterisk() {
        var eventManagementVal = $('#" . $eventManagementFieldId . "').val();
        if (eventManagementVal == 0) {
            $('.field-event-event_membership_type_id').removeClass('required');
            $('.field-event-seats_available').removeClass('required');
            $('.field-event-paid_event').removeClass('required');
        }
        if (eventManagementVal == 1) {
            $('.field-event-event_membership_type_id').addClass('required');
            $('.field-event-seats_available').addClass('required');
            $('.field-event-paid_event').addClass('required');
        }
    }
    
    function changeSeatsAvailableRequiredAsterisk() {
        var limitedSeatsVal = " . ($eventTypeWithLimitedSeats ? 1 : 0) . ";
        if (limitedSeatsVal == 0) {
            $('.field-event-seats_available').removeClass('required');
        }
        if (limitedSeatsVal == 1) {
            $('.field-event-seats_available').addClass('required');
        }
    }

    $('#" . $eventManagementFieldId . "').on('change', function (event) {
        disableEventManagementFields();
        changeEventManagementRequiredFieldsAsterisk();
    });
    
    disableEventManagementFields();
    changeEventManagementRequiredFieldsAsterisk();
    changeSeatsAvailableRequiredAsterisk();
";
$this->registerJs($js, View::POS_READY);

$jsTicket = <<<JS
    $('#has-ticket').click(function(){
        var value = $(this).val();
        if(value == 1){
            $("#container-seats-management").show();
        }else {
            $("#container-seats-management").hide();
            $('#event-seats_management').val(0)
        }
    });

$('#event-seats_management').click(function(){
     var value = $(this).val();
     if(value == 1){
         $('#event-seats_available').val(0);
     }
});
JS
;

$this->registerJs($jsTicket);


$impXlsJs = <<<JS
$("#import-invitations-form").submit(function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $("#import-invitations-modal").modal("hide");
    $("#import-invitations-response").hide();
    var dta = new FormData(this);
    var url = $(this).prop("action");
    console.log(dta);
    console.log(url);
    $.ajax({
        url: url,
        type: "POST",
        data: dta,
        processData: false,
        contentType: false,
        success: function(rsp) {
            // if (rsp.success) {
            // } else{
            // }
            $("#import-invitations-response").html(rsp.message).show();
            $("#import-invitations-modal button").prop("disabled", false).prop("clicked", false);
        },
        error: function(){
            alert("Something went wrong");
            console.log("Error");
        }
    });
});
JS
;
$this->registerJs($impXlsJs, yii\web\View::POS_READY);

if ($moduleEvents->enableEventRooms) {
    $jsEventsRooms = <<<JS
    $('#$eventRoomFieldId').on('change', function(e) {
        $('#$seatsAvailableFieldId').val($(this).find('option:selected').data('available_seats'));
    });
JS
;

    $this->registerJs($jsEventsRooms, View::POS_READY);
}

$user_enabled = \Yii::$app->user->can('EVENTS_MANAGER');

/** @var EventLengthMeasurementUnit $eventLengthMeasurementUnitModel */
$eventLengthMeasurementUnitModel = $moduleEvents->createModel('EventLengthMeasurementUnit');

/** @var EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');

$eventsFilesAsset = EventsFilesAsset::register($this);
$value = '';
$disabled = false;

$form = ActiveForm::begin([
    'id' => 'event_form',
    'options' => [
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'enctype' => 'multipart/form-data' // important
    ],
    'errorSummaryCssClass' => 'error-summary alert alert-error',
]);

WorkflowTransitionStateDescriptorWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => Event::EVENTS_WORKFLOW,
    'classDivMessage' => 'message',
    'viewWidgetOnNewRecord' => false
]);
?>

<div class="event-form">
    <?php if ($model->getScenario() == Event::SCENARIO_CREATE || $model->getScenario() == Event::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE): ?>
        <?php $this->beginBlock('general'); ?>
        <div class="row">
            <div class="col-md-12">
            <?php
                $append = '';
                if (\Yii::$app->getUser()->can('EVENTTYPE_CREATE')) {
                    $append = ' canInsert';
                }

                if ($moduleEvents->dropdownEventTypeDisabled) {
                    $value = EventType::TYPE_INFORMATIVE;
                    $disabled = true;
                    echo $form->field($model, 'event_type_id')->hiddenInput(['value' => $value])->label(false);
                }

                echo $form->field($model, 'event_type_id')->widget(Select2::class, [
                    'data' => $eventTypeSearchModel::searchEnabledGenericContextEventTypesReadyForSelect(),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'id' => 'EventType' . $fid,
                        'placeholder' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                        'class' => 'dynamicCreation' . $append,
                        'data-model' => 'event_type',
                        'data-field' => 'name',
                        'data-module' => 'events',
                        'data-entity' => 'event-type',
                        'data-toggle' => 'tooltip',
                        'value' => $value,
                        'disabled' => $disabled,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]);
            ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true])->hint(AmosEvents::t('amosevents', '#title_field_hint')) ?>
            </div>
        </div>
        <?php if (isset($moduleCwh) && isset($moduleTag)) : ?>
            <div class="row">
                <div class="col-xs-12">
                <?= DestinatariPlusTagWidget::widget([
                    'model' => $model,
                    'moduleCwh' => $moduleCwh,
                    'scope' => $scope
                ]);
                ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $this->endBlock(); ?>

        <?php
        $itemsTab[] = [
            'label' => AmosEvents::tHtml('amosevents', 'General'),
            'content' => $this->blocks['general'],
        ];
        ?>

    <?php else: ?>

        <?php $this->beginBlock('general'); ?>
        <?= $form->field($model, 'event_type_id')->hiddenInput(['id'])->label(false) ?>
        <div>
            <h4><strong><?= $model->getAttributeLabel('eventType') ?>
                    :</strong> <?= $eventTypePresent ? $eventType->title : '-' ?></h4>
        </div>
        <?php if ($moduleEvents->viewEventSignupLinkInForm && ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED)): ?>
            <div>
                <h4>
                    <strong><?= AmosEvents::t('amosevents', '#external_link_register') ?>: </strong>
                    <?= Url::base(true) . Url::toRoute(['event-signup', 'eid' => $model->id]); ?>
                </h4>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $this->render('boxes/box_custom_fields_begin', ['form' => $form, 'model' => $model]); ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true])->hint(AmosEvents::t('amosevents', '#title_field_hint')) ?>

                <?php if ($moduleEvents->freeSelectEndOfTheEvent): ?>
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <?= $form->field($model, 'begin_date_hour')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATETIME,
                                'options' => [
                                    'id' => $beginDateHourId,
                                    'layout' => '{input} {picker} ' . (($model->begin_date_hour == '') ? '' : '{remove}')]
                            ]); ?>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <?= $form->field($model, 'end_date_hour')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATETIME,
                                'options' => [
                                    'id' => $endDateHourId,
                                    'layout' => '{input} {picker} ' . (($model->end_date_hour == '') ? '' : '{remove}')]
                            ]); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-lg-5 col-sm-5">
                            <?= $form->field($model, 'begin_date_hour')->widget(DateControl::class, [
                                'type' => DateControl::FORMAT_DATETIME,
                                'options' => [
                                    'id' => $beginDateHourId,
                                    'layout' => '{input} {picker} ' . (($model->begin_date_hour == '') ? '' : '{remove}')]
                            ]); ?>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?= $form->field($model, 'length')->textInput([
                                'maxlength' => true,
                                'type' => 'number',
                                'min' => 1,
                                'step' => 1,
                            ])
                            ?>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <?= $form->field($model, 'length_mu_id')->widget(Select2::class,
                                [
                                    'data' => EventsUtility::translateArrayValues(
                                        ArrayHelper::map(
                                            $eventLengthMeasurementUnitModel::find()->asArray()->all(), 'id', 'title'
                                        )
                                    ),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => ['multiple' => false,
                                        'id' => 'EventLengthMeasurementUnit' . $fid,
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
                <?php endif; ?>


                <?php
                if ($eventTypeWithLimitedSeats) {
                    ?>
                    <div class="row">
                        <?php
                        $disabled = false;
                        if ($model->seats_management) {
                            $disabled = true;
                        }
                        /** @var Event $eventModel */
                        $eventModel = $moduleEvents->createModel('Event');
                        $allEventRooms = EventsUtility::findAllEventRooms();
                        ?>
                        <?php if ($moduleEvents->enableEventRooms): ?>
                            <div class="col-lg-4 col-sm-4">
                                <?= $form->field($model, 'event_room_id')->widget(Select::class, [
                                    'data' => EventsUtility::getEventRoomsReadyForSelect($allEventRooms),
                                    'language' => substr(Yii::$app->language, 0, 2),
                                    'options' => [
                                        'multiple' => false,
                                        'placeholder' => AmosEvents::t('amosevents', 'Seleziona') . '...',
                                        'options' => EventsUtility::getEventRoomsDataForSelect($allEventRooms)
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ]); ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-lg-4 col-sm-4">
                            <?= $form->field($model, 'seats_available')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
                        </div>
                        <!-- Lasciare così la findOne perché deve prendere sempre il valore da db e non quello caricato nel model (tipo quando si salva e ci sono errori). Forse basta fare getOldAttribute ma è da testare -->
                        <?php if ($eventModel::findOne($model->id)->seats_management) { ?>
                            <div class="col-lg-4 col-sm-4" style="margin-top:30px;">
                                <?php
                                echo Html::button(AmosEvents::t('amosevents', "Importa posti"),
                                    [
                                        'class' => 'btn btn-primary pull-left',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#modalImport',
                                    ]);
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>

                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'description')->widget(TextEditorWidget::class, [
                            'clientOptions' => [
                                'placeholder' => AmosEvents::t('amosevents', 'Insert the event description'),
                                'lang' => substr(Yii::$app->language, 0, 2)
                            ]
                        ]) ?>
                    </div>
                </div>

            </div>

            <div class="col-xs-12">
                <?= $this->render('boxes/box_custom_uploads_begin', ['form' => $form, 'model' => $model]); ?>
                <!-- {{{ Locandina -->
                <div>
                    <?=
                    $form->field($model, 'eventLogo')->widget(CropInput::class,
                        [
                            'jcropOptions' => ['aspectRatio' => '1.7']
                        ])->label(AmosEvents::t('amosevents', 'Locandina'))
                    ?>
                </div>
                <!-- }}} Locandina -->
                <!-- {{{ Allegati -->
                <div class="attachment-section" style="padding: 24px;">
                    <div>
                        <?= Html::tag('h2', AmosEvents::t('amosevents', '#attachments_title')) ?>
                        <?= $form->field($model, 'eventAttachments')->widget(AttachmentsInput::class, [
                            'options' => [// Options of the Kartik's FileInput widget
                                'multiple' => true, // If you want to allow multiple upload, default to false
                            ],
                            'pluginOptions' => [// Plugin options of the Kartik's FileInput widget
                                'maxFileCount' => 100, // Client max files
                                'showPreview' => false
                            ]
                        ])->label(AmosEvents::t('amosevents', '#attachments_field'))->hint(AmosEvents::t('amosevents', '#attachments_field_hint')) ?>

                        <?= AttachmentsList::widget([
                            'model' => $model,
                            'attribute' => 'eventAttachments'
                        ]) ?>
                    </div>
                </div>

                <!--Gallery, inserimento di più immagini-->
                <div class="attachment-section" style="padding: 24px;">
                    <div>
                        <?php if ($enableGalleryAttachment) : ?>
                            <?= Html::tag('h2', AmosEvents::t('amosevents', 'Gallery')); ?>
                            <?= $form->field($model, 'event_gallery_attachment')->widget(AttachmentsInput::className(), [
                                    'options' => [ // Options of the Kartik's FileInput widget
                                        'multiple' => true,
                                        'accept' => ".jpg, .jpeg, .png",
                                    ],
                                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                                        'maxFileCount' => 100, // Client max files
                                        'showPreview' => true,
                                        'allowedPreviewTypes' => ['image'],
                                        'maxFileSize' => 5 * 1024 * 1024,
                                    ]
                                ])->hint(AmosEvents::t('amosevents', '#gallery_field_hint')); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- }}} Allegati -->
                <?= $this->render('boxes/box_custom_uploads_end', ['form' => $form, 'model' => $model]); ?>
            </div>
            <div class="col-xs-12 m-t-35">
                <?php if ($enableRelatedEvents) : ?>
                    <?= Html::tag('h2', AmosEvents::t('amosevents', 'Eventi Correlati')); ?>
                    <?= $form->field($model, 'relatedEventMmAttribute')->widget(Select2::class, [
                        'data' => ArrayHelper::map(
                            \open20\amos\events\models\Event::find()
                                ->andwhere(['status' => 'EventWorkflow/PUBLISHED'])
                                ->andWhere(['deleted_at' => null])
                                //->andWhere(['end_date_hour' => date("y:m:d h:i:sa")])
                                ->all(),
                            'id',
                            'title'
                        ),
                        'options' => [
                            'multiple' => true,
                            'placeholder' => AmosEvents::t('amosevents', 'Seleziona...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ])->hint(AmosEvents::t('amosevents', '#event_field_hint')); ?>
                <?php endif; ?>
            </div>

        </div>

        <div class="row m-t-30">
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'event_location')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12"><h2><?= AmosEvents::t('amosevents', '#form_section_address') ?></h2></div>
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
            <?= AmosComuniWidget::widget([
                'form' => $form,
                'model' => $model,
                'nazioneConfig' => [
                    'attribute' => 'country_location_id',
                    'class' => 'col-lg-4 col-sm-4'
                ],
                'provinciaConfig' => [
                    'attribute' => 'province_location_id',
                    'class' => 'col-lg-4 col-sm-4'
                ],
                'comuneConfig' => [
                    'attribute' => 'city_location_id',
                    'class' => 'col-lg-4 col-sm-4'
                ],
            ]); ?>
        </div>
        <?= $this->render('boxes/box_custom_fields_end', ['form' => $form, 'model' => $model]); ?>
        <?php $this->beginBlock('general_advanced'); ?>
        <div class="row">
            <div class="col-xs-12"><h2><?= AmosEvents::t('amosevents', '#form_section_advanced') ?></h2></div>
            <?php
            if ($moduleEvents->hidePubblicationDate == false) {
                ?>
                <div class="col-lg-4 col-sm-4">
                    <?=
                    $form->field($model, 'publication_date_begin')->widget(DateControl::class,
                        [
                            'type' => DateControl::FORMAT_DATETIME
                        ])->hint(AmosEvents::t('amosevents', '#publication_date_begin_hint'))
                    ?>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <?=
                    $form->field($model, 'publication_date_end')->widget(DateControl::class,
                        [
                            'type' => DateControl::FORMAT_DATETIME
                        ])->hint(AmosEvents::t('amosevents', '#publication_date_end_hint'))
                    ?>
                </div>
            <?php } ?>
            <?php
            if ($moduleNotify && !empty($moduleNotify->enableNotificationContentLanguage) && $moduleNotify->enableNotificationContentLanguage) { ?>
                <div class="col-lg-4 col-sm-4">
                <?= NotifyContentLanguageWidget::widget(['model' => $model]) ?>
                </div>
            <?php } ?>
        </div>
        <?php if ($eventTypeWithLimitedSeats): ?>
            <div class="row">
                <div class="col-sm-4">
                <?= $form->field($model, 'numero_max_accompagnatori')->textInput(['type' => 'number']) ?>
                </div>
            </div>
        <?php endif; ?>
    
        <?php if (!$eventTypeInformative): ?>
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <?= $form->field($model, 'registration_date_begin')->widget(DateControl::class, [
                        'type' => DateControl::FORMAT_DATETIME
                    ])
                    ?>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <?= $form->field($model, 'registration_date_end')->widget(DateControl::class, [
                        'type' => DateControl::FORMAT_DATETIME
                    ])
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'event_commentable')->dropDownList(
                Html::getBooleanFieldsValues(),
                [
                    'options' => !$model->isNewRecord ? [$moduleEvents->forceEventCommentable => ['Selected' => true]] : null,
                    'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'),
                    'disabled' => false
                ])
            ?>
            </div>
        </div>
        <?php if ($moduleEvents->enableFiscalCode): ?>
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'abilita_codice_fiscale_in_form')->dropDownList(
                    Html::getBooleanFieldsValues(),
                    [
                        'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'),
                        'disabled' => false
                    ]
                )
                ?>
                </div>
            </div>
        <?php endif; ?>
    
        <div class="row">
            <?php if ($moduleEvents->enableTickets): ?>
                <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'has_tickets')->dropDownList(
                    Html::getBooleanFieldsValues(),
                    [
                        'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'),
                        'disabled' => false,
                        'id' => 'has-ticket'
                    ])
                ?>
                </div>
            <?php endif; ?>

            <?php
            if ($eventTypeWithLimitedSeats) :
                $strhide = '';
                if (!$model->has_tickets) {
                    $strhide = "display:none";
                }
                
                if ($moduleEvents->enableSeatsManagement): ?>
                    <div id="container-seats-management" class="col-lg-4 col-sm-4" style="<?= $strhide ?>">
                        <?=
                        $form->field($model, 'seats_management')->dropDownList(
                            Html::getBooleanFieldsValues(),
                            ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
                        )->hint(AmosEvents::t('amosevents',
                            "Con gestione posti uguale a 'si' occorre effettuare l'importazione dei posti da file excel"))
                        ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($moduleEvents->enableQrCode): ?>
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'has_qr_code')->dropDownList(
                    Html::getBooleanFieldsValues(),
                    ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
                )
                ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($moduleEvents->ticketPasses['enabled']) && $moduleEvents->ticketPasses['enabled'] = true) : ?>
            <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'enable_ticket_wallet')->dropDownList(
                Html::getBooleanFieldsValues(),
                ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
            )
            ?>
            </div>
            <?php endif; ?>

            <?php if ($moduleEvents->enableCalendarsManagement) : ?>
            <div id="container-seats-management" class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'slots_calendar_management')->dropDownList(
                Html::getBooleanFieldsValues(),
                ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
            )
            ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-xs-12">
            <?php
                if ($model->has_tickets) {
                    /** @var EventAccreditationList $eventAccreditationListModel */
                    $eventAccreditationListModel = $moduleEvents->createModel('EventAccreditationList');
                    echo AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => AmosEvents::txt('Accreditation lists'),
                                'content' => Html::tag('div',
                                    Html::a(
                                        AmosEvents::txt('Add') . ' ' . AmosEvents::txt('Accreditation lists'),
                                        '/events/event-accreditation-list/create?eid=' . $model->id,
                                        ['class' => 'btn btn-primary']
                                    ) .
                                    GridView::widget([
                                        'id' => 'accreditation-lists-available',
                                        'responsive' => true,
                                        'dataProvider' => new ActiveDataProvider([
                                            'query' => $eventAccreditationListModel::find()
                                                ->andWhere(['event_id' => $model->id]),
                                        ]),
                                        'formatter' => [
                                            'class' => 'yii\i18n\Formatter',
                                            'nullDisplay' => '-'
                                        ],
                                        'columns' => [
                                            'title',
                                            [
                                                'attribute' => 'position',
                                                'label' => AmosEvents::txt('Order'),
                                            ],
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{update} {delete}',
                                                'buttons' => [
                                                    'update' => function ($url, $model) {
                                                        return Html::a(
                                                            Html::tag(
                                                                'span',
                                                                '',
                                                                [
                                                                    'class' => 'glyphicon glyphicon-pencil'
                                                                ]
                                                            ),
                                                            [
                                                                '/events/event-accreditation-list/update',
                                                                'id' => $model->id
                                                            ]
                                                        );
                                                    },
                                                    'delete' => function ($url, $model) {
                                                        return Html::a(
                                                            Html::tag(
                                                                'span',
                                                                '',
                                                                [
                                                                    'class' => 'glyphicon glyphicon-trash'
                                                                ]
                                                            ),
                                                            [
                                                                '/events/event-accreditation-list/delete',
                                                                'id' => $model->id
                                                            ]
                                                        );
                                                    }
                                                ]
                                            ],
                                        ]
                                    ]), ['style' => 'overflow-x:hidden;']),
                            ],
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                            'active' => 'false',
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                        ],
                    ]);
                }
                ?>
            </div>
        </div>

        <div>&nbsp;</div>

        <?php $this->endBlock(); ?>

        <?php if ($moduleEvents->enableGdpr): ?>
            <?php $this->beginBlock('general_gdpr'); ?>
            <div class="row">
                <div class="col-xs-12"><h2><?= AmosEvents::t('amosevents', '#form_section_gdpr') ?></h2></div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'gdpr_question_1')->textInput() ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'gdpr_question_2')->textInput() ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'gdpr_question_3')->textInput() ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'gdpr_question_4')->textInput() ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($model, 'gdpr_question_5')->textInput() ?>
                </div>
            </div>
            <?php $this->endBlock(); ?>
        <?php endif; ?>

        <?php $this->beginBlock('advanced_customizations'); ?>
        <div class="row">
            <div class="col-xs-12"><h2><?= AmosEvents::t('amosevents', 'Personalizzazioni') ?></h2></div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'subscribe_form_page_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'thank_you_page_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_ticket_subject')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_ticket_layout_custom')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_ticket_sender')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'event_closed_page_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'event_full_page_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'ticket_layout_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_subscribe_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
            <?= $form->field($model, 'sent_credential')->checkbox([
                0 => AmosEvents::t('amosevents', 'Non inviare credenziali'),
                1 => AmosEvents::t('amosevents', 'Invia credenziali')
            ])
            ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_credential_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_credential_subject')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'email_invitation_custom')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'thank_you_page_already_registered_view')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
            <?= $form->field($model, 'use_token')->checkbox([
                0 => AmosEvents::t('amosevents', 'Non usare token di accesso'),
                1 => AmosEvents::t('amosevents', 'Usa token di accesso')
            ])
            ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'token_group_string_code')->textInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'ics_libero')->checkbox() ?>
            </div>
        </div>
        <?php $this->endBlock(); ?>

        <?php if (isset($moduleCwh) && isset($moduleTag)) : ?>
        <div class="row">
            <div class="col-xs-12 section-form">
                <div class="section-modalita-pubblicazione">
                    <?= Html::tag('h2', AmosEvents::t('amosevents', '#settings_receiver_title'),
                        ['class' => 'subtitle-form m-t-0'])
                    ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= DestinatariPlusTagWidget::widget([
                                'model' => $model,
                                'moduleCwh' => $moduleCwh,
                                'scope' => $scope
                            ]);
                            ?>
                        </div>
                    </div>

                </div>
                
               
            </div>
        </div>
        <?php endif; ?>

        <?php if (!$eventTypeInformative && $moduleEvents->enableCommunitySections): ?>
            <?php $this->beginBlock('general_community'); ?>
            <div class="row">
                <div class="col-xs-12"><h2><?= AmosEvents::t('amosevents', '#section_community') ?></h2></div>
                <div class="col-lg-12 col-sm-12">
                    <?= $form->field($model, 'show_community')->checkbox(); ?>
                </div>
            </div>
            <?php $this->endBlock(); ?>
        <?php endif; ?>

        <?php $this->beginBlock('general_frontend'); ?>
        <div class="row">
            <div class="col-xs-12"><h2><?= AmosEvents::t('amosevents', '#section_frontend') ?></h2></div>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'show_on_frontend')->checkbox(); ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'landing_url')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'landingHeader')->widget(AttachmentsInput::class, [
                    'options' => ['multiple' => false],
                    'pluginOptions' => ['maxFileCount' => 1],
                ]); ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'frontend_page_title')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-lg-12 col-sm-12">
                <?= $form->field($model, 'frontend_claim')->widget(TextEditorWidget::class, [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
        <?php $this->endBlock(); ?>

        <?php if ($user_enabled && !$eventTypeInformative) { ?>
            <?php $this->beginBlock('general_invitations'); ?>
            <?php if ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) : ?>
                <div class="row">
                    <div class="col-xs-12">
                    <?= Html::a(
                        AmosEvents::t('amosevents', '#download_invitations_example_file'),
                        Url::to($eventsFilesAsset->baseUrl . DIRECTORY_SEPARATOR . 'tracciato_esempio.xlsx')
                    )
                    ?>
                    </div>
                    <div class="col-xs-12" id="import-invitations-response"></div>
                    <div class="col-xs-12">
                        <ul>
                        <?php
                            $stats = $model->getInvitationStats();

                            if ($stats['registered']) : ?>
                                <li>
                                <?= AmosEvents::txt('#invitations_stats_registered', [
                                    'total' => $stats['registered'],
                                    'accepted' => (int)$stats['registered_accepted'],
                                    'rejected' => (int)$stats['registered_rejected']
                                ]);
                                ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($stats['imported']) : ?>
                                <li>
                                <?= AmosEvents::txt('#invitations_stats_imported', [
                                    'total' => $stats['imported'],
                                    'accepted' => (int)$stats['imported_accepted'],
                                    'rejected' => (int)$stats['imported_rejected']
                                ]);
                                ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($stats['partners']) : ?>
                                <li>
                                <?= AmosEvents::txt('#invitations_stats_partners', ['total' => $stats['partners']]); ?>
                                </li>
                            <?php endif; ?>
                            
                            <li>
                            <?= AmosEvents::txt('#invitations_stats_total', [
                                'total' => $stats['total'],
                                'accepted' => (int)$stats['accepted'],
                                'rejected' => (int)$stats['rejected']
                                ]);
                            ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="button" class="btn" data-toggle="modal" data-target="#import-invitations-modal">
                            <?= AmosEvents::t('amosevents', '#import_from_excel'); ?>
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-xs-12">
                        <?= Alert::widget([
                            'type' => Alert::TYPE_WARNING,
                            'body' => AmosEvents::t('amosevents', '#import_if_published_message'),
                            'closeButton' => false
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php $this->endBlock(); ?>
        <?php } ?>

        <?php
        $itemsAccordion = [];
        $itemsAccordion[] = [
            'header' => AmosEvents::t('amosevents', '#form_section_advanced'),
            'content' => $this->blocks['general_advanced'],
        ];
        if ($moduleEvents->enableGdpr) {
            $itemsAccordion[] = [
                'header' => AmosEvents::t('amosevents', '#form_section_gdpr'),
                'content' => $this->blocks['general_gdpr'],
            ];
        }
        if ($moduleEvents->enableCommunitySections) {
            $itemsAccordion[] = [
                'header' => AmosEvents::t('amosevents', '#section_community'),
                'content' => $this->blocks['general_community'],
            ];
        }

        if (\Yii::$app->user->can('ADVANCED_CUSTOMIZE_EVENTS')) {
            $itemsAccordion[] = [
                'header' => AmosEvents::t('amosevents', 'Personalizzazioni'),
                'content' => $this->blocks['advanced_customizations'],
            ];
        }
        ?>

        <?=
        AccordionWidget::widget([
            'items' => $itemsAccordion,
            // [
            //     'header' => AmosEvents::t('amosevents', '#section_frontend'),
            //     'content' => $this->blocks['general_frontend'],
            // ],
            'headerOptions' => ['tag' => 'h2'],
            'clientOptions' => [
                'collapsible' => true,
                'active' => 'false',
                'icons' => [
                    'header' => 'ui-icon-amos am am-plus-square',
                    'activeHeader' => 'ui-icon-amos am am-minus-square',
                ]
            ],
        ]);
        ?>


        <?php
        if ($user_enabled && !$eventTypeInformative) {
            ?>
            <?=
            AccordionWidget::widget([
                'items' => [
                    [
                        'header' => AmosEvents::t('amosevents', '#section_invitations'),
                        'content' => $this->blocks['general_invitations'],
                    ],
                ],
                'headerOptions' => ['tag' => 'h2'],
                'clientOptions' => [
                    'collapsible' => true,
                    'active' => 'false',
                    'icons' => [
                        'header' => 'ui-icon-amos am am-plus-square',
                        'activeHeader' => 'ui-icon-amos am am-minus-square',
                    ]
                ],
            ]);
            ?>
        <?php } ?>

        <?php
        if (\Yii::$app->user->can('EVENTS_PUBLISHER_FRONTEND')) {
            if (Yii::$app->getModule('events')->params['site_publish_enabled'] || Yii::$app->getModule('events')->params['site_featured_enabled']) {
                ?>
                <div class="col-xs-12 receiver-section">
                    <div class="row">
                        <?php if (Yii::$app->getModule('events')->params['site_publish_enabled']) { ?>

                            <h3 class="subtitle-section-form"><?= AmosEvents::t('amosevents', "Pubblication on the portal mode") ?>
                                <em>(<?= AmosEvents::t('amosevents', "Choose if you want to publish the event also on the portal") ?>
                                    )</em>
                            </h3>
                            <?php
                            $primoPiano = '<div class="col-md-6 col-xs-12">'
                                . $form->field($model, 'primo_piano')->widget(Select::class,
                                    [
                                        'auto_fill' => true,
                                        'data' => [
                                            '0' => AmosEvents::t('amosevents', 'No'),
                                            '1' => AmosEvents::t('amosevents', 'Si')
                                        ],
                                        'options' => [
                                            'prompt' => AmosEvents::t('amosevents', 'Seleziona'),
                                            'disabled' => false,
                                            'onchange' => "
                    if($(this).val() == 1) $('#event-in_evidenza').prop('disabled', false);
                    if($(this).val() == 0) {
                        $('#event-in_evidenza').prop('disabled', true);
                        $('#event-in_evidenza').val(0);
                    }"
                                        ],
                                    ]) .
                                '</div>';
                            echo $primoPiano;
                        }

                        if (Yii::$app->getModule('events')->params['site_featured_enabled']) {
                            $inEvidenza = '<div class="col-md-6 col-xs-12">'
                                . $form->field($model, 'in_evidenza')->widget(Select::class,
                                    [
                                        'auto_fill' => true,
                                        'data' => [
                                            '0' => AmosEvents::t('amosevents', 'No'),
                                            '1' => AmosEvents::t('amosevents', 'Si')
                                        ],
                                        'options' => [
                                            'prompt' => AmosEvents::t('amosevents', 'Seleziona'),
                                            'disabled' => ($model->primo_piano == 1 ? false : true)
                                        ]
                                    ])
                                . '</div>';
                            echo $inEvidenza;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>


        <?php if (isset($moduleSeo)) : ?>
            <div class="row">
                <div class="col-xs-12<?= $hideSeoModuleClass ?>">
                    <?= Html::tag('h2', AmosEvents::t('amosevents', '#settings_seo_title'),
                        ['class' => 'subtitle-form m-t-25'])
                    ?>
                    <div class="col-xs-12 receiver-section">
                        <?=
                        \open20\amos\seo\widgets\SeoWidget::widget([
                            'contentModel' => $model,
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="clearfix"></div>
        <?php $this->endBlock(); ?>


        <?php
        $itemsTab[] = [
            'label' => AmosEvents::tHtml('amosevents', 'General'),
            'content' => $this->blocks['general'],
        ];
        ?>

    <?php endif; ?>

    <?=
    Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab,
            'hideCwhTab' => true, //$model->isNewRecord,
            'hideTagsTab' => true, //$model->isNewRecord
        ]
    );
    ?>
    
    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>

    <?php
    $hideDraftStatuses = true;

    if (\Yii::$app->user->can($model->getValidatorRole(), ['model' => $model])) {
        $hideDraftStatuses = false;
    }

    if (\Yii::$app->user->can('ADMIN')) {
        $hideDraftStatuses = false;
    }

    $hideDraftStatus = [];
    if ($hideDraftStatuses) {
        $hideDraftStatus[] = Event::EVENTS_WORKFLOW_STATUS_PUBLISHED;
    }
    if ($model->isNewRecord) {
        $defaultButtonLabel = AmosEvents::t('amosevents', '#continue');
        $defaultButtonDescription = AmosEvents::t('amosevents', "#to_enter_details");
    } else {
        $defaultButtonLabel = AmosEvents::t('amosevents', 'Salva in bozza');
        $defaultButtonDescription = AmosEvents::t('amosevents', "potrai richiedere la pubblicazione in seguito");
    }
    ?>
    <div class="row">
    <?=
    WorkflowTransitionButtonsWidget::widget([
        // parametri ereditati da verioni precedenti del widget WorkflowTransition
        'form' => $form,
        'model' => $model,
        'workflowId' => Event::EVENTS_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        'closeButton' => Html::a(AmosEvents::t('amosevents', 'Annulla'), Yii::$app->session->get('previousUrl'),
            ['class' => 'btn btn-secondary']),
        // fisso lo stato iniziale per generazione pulsanti e comportamenti
        // "fake" in fase di creazione (il record non e' ancora inserito nel db)
        'initialStatusName' => 'DRAFT',
        'initialStatus' => $model->getWorkflowSource()->getWorkflow(Event::EVENTS_WORKFLOW)->getInitialStatusId(),
        // Stati da renderizzare obbligatoriamente in fase di creazione (quando il record non e' ancora inserito nel db)
        //'statusToRender' => $statusToRender,
        'hideSaveDraftStatus' => $hideDraftStatus,
        'draftButtons' => [
            Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST => [
                'button' => Html::submitButton(AmosEvents::t('amosevents', 'Save'), ['class' => 'btn btn-workflow']),
                'description' => AmosEvents::t('amosevents', 'le modifiche e mantieni l\'evento in "richiesta di pubblicazione"'),
            ],
            Event::EVENTS_WORKFLOW_STATUS_PUBLISHED => [
                'button' => Html::submitButton(AmosEvents::t('amosevents', 'Save'), ['class' => 'btn btn-workflow']),
                'description' => AmosEvents::t('amosevents', 'le modifiche e mantieni l\'evento "pubblicato"'),
            ],
            'default' => [
                'button' => Html::submitButton($defaultButtonLabel, ['class' => 'btn btn-workflow']),
                'description' => $defaultButtonDescription,
            ]
        ]
    ]);
    ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<div id="import-invitations-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
            $form = ActiveForm::begin([
                'id' => 'import-invitations-form',
                'action' => 'import-invitations?id=' . $model->id,
                'options' => [
                    'enctype' => 'multipart/form-data' // important
                ]
            ]);
            ?>
            <div class="modal-header">
                <h5 class="modal-title"><?= AmosEvents::t('amosevents', 'Inviti') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <?= $form->field($upload, 'excelFile')->fileInput()->hint(AmosEvents::txt('#invitations_excel_file_hint')) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton('Importa', ['class' => 'btn']) ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= AmosEvents::t('amosevents', 'Close') ?></button>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
<?= $this->render('_modal_import', ['model' => $model]); ?>
