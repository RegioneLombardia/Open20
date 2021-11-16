<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use yii\bootstrap\Alert;

$FlashMsg = Yii::$app->session->getAllFlashes();

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $event
 * @var $registerGroupForm
 * @var yii\widgets\ActiveForm $form
 * @var string $fid
 * @var array $userData
 * @var array $companions
 * @var array $gdprQuestions
 */

$this->title = AmosEvents::t("Iscrivi gruppo all'evento {event_name}", ['event_name' => $event->title]);

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

?>
<div class="event_signUp col-xs-12 nop">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="header-form">
        <?php
        $url = '/img/img_default.jpg';
        if (!is_null($event->eventLogo)) {
            $url = $event->eventLogo->getWebUrl('original', false, true);
        }
        ?>
        <?= Html::img($url, [
            'title' => $event->getAttributeLabel('eventLogo'),
            'class' => 'img-responsive'
        ]); ?>
    </div>

    <div class="header-form_caption">
        <div class="form_caption_date text-center">

            <h2 class="event-day"><?= date("d", strtotime($event->begin_date_hour)) ?></h2>
            <p class="event-month"><?= Yii::$app->formatter->asDate($event->begin_date_hour, 'MMM') ?></p>
            <p class="event-year"><?= date("Y", strtotime($event->begin_date_hour)) ?></p>

            <!--                    < ?= \Yii::$app->getFormatter()->asDatetime($event->begin_date_hour) ?> <br>-->
            <!--                    < ?= ($event->end_date_hour ? \Yii::$app->getFormatter()->asDatetime($event->end_date_hour) : '-') ?> <br>-->
        </div>
        <div class="form_caption_title">
            <span><?= ($event->cityLocation) ? $event->cityLocation->nome : '-' ?> </span> <span> | </span>
            <span><?= ($event->countryLocation) ? $event->countryLocation->nome : '-' ?></span>
            <h2><?= $event->title ?></h2>
            <!--                    <p>< ?= $event->summary ?></p>-->
            <!--                    <p>< ?= $event->description ?></p>-->
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php if (!empty($FlashMsg)): ?>
            <div class="container-messages">
                <?php endif; ?>
                <?php foreach ($FlashMsg as $type => $message): ?>
                    <?php if (!is_array($message)): ?>
                        <?= Alert::widget([
                            'options' => [
                                'class' => 'alert-' . $type,
                                'role' => 'alert'
                            ],
                            'body' => $message,
                        ]); ?>
                    <?php else: ?>
                        <?php foreach ($message as $ty => $msg): ?>
                            <?= Alert::widget([
                                'options' => [
                                    'class' => 'alert-' . $type,
                                    'role' => 'alert'
                                ],
                                'body' => $msg,
                            ]); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!empty($FlashMsg)): ?>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <p><?= $event->summary ?></p>
        </div>
    </div>

    <div class="row wrap-description">
        <div class="col-xs-12">
            <h3><?= AmosEvents::t('amosevents', '#event_signup_ticket_request'); ?></h3>
            <?= $event->description ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($registerGroupForm, 'groupName')->textInput(['placeholder' => AmosEvents::txt('Nome gruppo')])
                ->label($registerGroupForm->getAttributeLabel('groupName')); ?>
            <?= $form->field($registerGroupForm, 'nSeats')->textInput(['placeholder' => AmosEvents::txt('Numero di posti')])
                ->label($registerGroupForm->getAttributeLabel('nSeats')); ?>
            <?= $form->field($registerGroupForm, 'email')->textInput(['placeholder' => AmosEvents::txt('Email referente gruppo')]); ?>
            <?= $form->field($registerGroupForm, 'sector')->widget(\kartik\select2\Select2::className(),[
                'data' => \yii\helpers\ArrayHelper::map($event->getSectorsAvailableForGroups(), 'sector', 'sectorNamePlusSeatsForGroups'),
                'options' => [
                    'placeholder' => AmosEvents::txt('Settore')
                ]
            ])->label($registerGroupForm->getAttributeLabel('sector')); ?>
            <?= $form->field($registerGroupForm, 'note')->textarea([
                'rows' => 5,
                'placeholder' => AmosEvents::txt('#participant_note')]); ?>
        </div>
    </div>


    <?php if ($eventsModule->enableGdpr): ?>
        <div class="row">
            <?php foreach ($gdprQuestions as $i => $gdprQuestion) {
                echo "<div class=\"col-xs-12\">" .
                    "<p>{$gdprQuestion}</p>" .
                    Html::radioList("gdprQuestion[{$i}]", null, [1 => ' ' . Yii::t('amoscore', 'Yes'), 0 => ' ' . Yii::t('amoscore', 'No')], ['itemOptions' => ['required' => 'required']]) .
                    "</div>";
            } ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12 m-t-20 m-b-20">
            <p><?= AmosEvents::tHtml('amosevents', '#event_signup_end') ?></p>
        </div>
    </div>

    <div class="text-right">
        <?= Html::submitButton(AmosEvents::txt('#event_signup_submit'), ['class' => 'btn']) ?>
    </div>

    <!--        < ?= ($event->provinceLocation) ? ' (' . $event->provinceLocation->sigla . ')' : '' ?> <br>-->
    <!--        < ?= ($event->seats_available) ? $event->seats_available : '-' ?> <br>-->

    <?php ActiveForm::end(); ?>
</div>



