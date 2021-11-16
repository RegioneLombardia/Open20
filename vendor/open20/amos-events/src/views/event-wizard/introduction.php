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
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\search\EventTypeSearch;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var \open20\amos\events\models\Event $model
 */
$this->title = AmosEvents::t('amosevents',"Nuovo Evento");

/** @var EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');

?>

<div class="event-wizard-introduction col-xs-12 nop">
    <div class="col-lg-12 nop">
        <?= AmosIcons::show('calendar', ['class' => 'am-4 icon-calendar-intro'], 'dash') ?>
        <?= AmosEvents::t('amosevents', 'An event could be an activity, a deadline, a meeting, a workshop or other facts that you would like to share through the calendar. For example: a work meeting, writing a document, a workshop, a conference, etc.') ?>
        <div class="nop col-xs-12">
            <p><?= AmosEvents::t('amosevents', 'In the following three steps you have to insert') ?>:</p>
            <div>
                <ol>
                    <li>
                        <?= AmosEvents::tHtml('amosevents', "informations describing the event") ?>
                    </li>
                    <li>
                        <?= AmosEvents::tHtml('amosevents', "data for organizing the event and for follow up") ?>
                    </li>
                    <li>
                        <?= AmosEvents::tHtml('amosevents', "how to publish") ?>
                    </li>
                </ol>
            </div>
        </div>
        <p>
            <strong><?= AmosEvents::t('amosevents', 'In order to create an event,') ?></strong> <?= AmosEvents::t('amosevents', 'first of all') ?>
            <strong><?= AmosEvents::t('amosevents', 'you have to select') ?></strong> <?= AmosEvents::t('amosevents', 'its') ?>
            <strong><?= AmosEvents::t('amosevents', 'type') ?></strong> <?= AmosEvents::t('amosevents', ', then') ?>
            <strong><?= AmosEvents::t('amosevents', 'press CONTINUE (in the lower right corner)') ?></strong>
        </p>
    </div>

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <div class="nop col-xs-12">
        <br>
        <?= $form->field($model, 'event_type_id')->widget(Select2::className(), [
            'data' => ArrayHelper::map($eventTypeSearchModel::searchGenericContextEventTypes()->asArray()->all(), 'id', 'title'),
            'language' => substr(Yii::$app->language, 0, 2),
            'options' => ['multiple' => false,
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
                'allowClear' => true
            ]
        ])->label(AmosEvents::tHtml('amosevents', "The event that I am publishing is of type"))
        ?>
    </div>
    <br>
    <br>
    <div class="nop col-xs-12 text-uppercase m-t-15 m-b-25">
        <strong><?= Html::a(AmosEvents::tHtml('amosevents', 'Skip wizard. Fill directly the event.'), ['/events/event/create']) ?></strong>
    </div>
    <br>

    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'viewPreviousBtn' => false,
        'cancelUrl' => Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
