<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventMembershipType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;

use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var \open20\amos\events\models\Event $model
 */

$eventManagementFieldId = Html::getInputId($model, 'event_management');
$registrationLimitDateFieldId = Html::getInputId($model, 'registration_limit_date') . '-disp';
$eventMembershipTypeIdFieldId = Html::getInputId($model, 'event_membership_type_id');
$seatsAvailableFieldId = Html::getInputId($model, 'seats_available');
$paidEventFieldId = Html::getInputId($model, 'paid_event');

$js = "
    function disableEventManagementFields() {
        if ($('#" . $eventManagementFieldId . "').val() == 0) {
            $('#" . $registrationLimitDateFieldId . "').val('').prop('disabled', true);
            $('#" . $eventMembershipTypeIdFieldId . "').val('').trigger('change').prop('disabled', true);
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

    $('#" . $eventManagementFieldId . "').on('change', function (event) {
        disableEventManagementFields();
    });
    
    disableEventManagementFields();
";
$this->registerJs($js, View::POS_READY);
$moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
$this->title = AmosEvents::t('amosevents',"Nuovo Evento");

/** @var EventMembershipType $eventMembershipTypeModel */
$eventMembershipTypeModel = $moduleEvents->createModel('EventMembershipType');

?>

<div class="event-wizard-organizational-data">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>

    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <?php
            $disable = false;
            if(!$moduleEvents->enableInvitationManagement) {
                $disable = true;
                $model->event_management = Event::BOOLEAN_FIELDS_VALUE_NO;
            }?>
            <?= $form->field($model, 'event_management')->dropDownList(Html::getBooleanFieldsValues(), [
                'prompt' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                'disabled' => $disable,
                'options' => [
                    Event::BOOLEAN_FIELDS_VALUE_NO => ['selected' => true]
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'registration_limit_date')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ]) ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <div class="select">
                <?= $form->field($model, 'event_membership_type_id')->widget(Select2::classname(), [
                    'options' => ['placeholder' => AmosEvents::t('amosevents', 'Type membership type'), 'disabled' => false],
                    'data' => EventsUtility::translateArrayValues(ArrayHelper::map($eventMembershipTypeModel::find()->asArray()->all(), 'id', 'title'))
                ])->label($model->getAttributeLabel('eventMembershipType')) ?>
            </div>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'seats_available')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'paid_event')->dropDownList(Html::getBooleanFieldsValues(), [
                'prompt' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                'disabled' => false
            ]) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model, 'publish_in_the_calendar')->dropDownList(Html::getBooleanFieldsValues(), [
                'prompt' => AmosEvents::t('amosevents', 'Select/Choose') . '...',
                'disabled' => false,
                'options' => [
                    Event::BOOLEAN_FIELDS_VALUE_YES => ['selected' => true]
                ]
            ]) ?>
        </div>
    </div>

    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/event-wizard/description', 'id' => $model->id]),
        'cancelUrl' => Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
