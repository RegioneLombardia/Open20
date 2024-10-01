<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use yii\bootstrap\Alert;

$FlashMsg = Yii::$app->session->getAllFlashes();

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $event
 * @var open20\amos\events\models\EventParticipantCompanion $eventParticipantModel
 * @var yii\widgets\ActiveForm $form
 * @var string $fid
 * @var array $userData
 * @var array $companions
 * @var array $gdprQuestions
 */

$this->title = AmosEvents::txt('#event_signup_title', ['event_name' => $event->title]);

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
$eventType = $event->eventType;
$eventTypePresent = !is_null($eventType);
$getData = Yii::$app->request->get();
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
            <?php $beginDateHourTimeStamp = strtotime($event->begin_date_hour); ?>
            <h2 class="event-day"><?= date("d", $beginDateHourTimeStamp) ?></h2>
            <p class="event-month"><?= Yii::$app->formatter->asDate($event->begin_date_hour, 'MMM') ?></p>
            <p class="event-year"><?= date("Y", $beginDateHourTimeStamp) ?></p>
            <?php if ($event->begin_date_hour): ?>
                <p class="event-year"><?= Yii::$app->getFormatter()->asTime($event->begin_date_hour) ?></p>
            <?php endif; ?>

            <!--                    < ?= \Yii::$app->getFormatter()->asDatetime($event->begin_date_hour) ?> <br>-->
            <!--                    < ?= ($event->end_date_hour ? \Yii::$app->getFormatter()->asDatetime($event->end_date_hour) : '-') ?> <br>-->
        </div>
        <div class="form_caption_title event-network-container">
            <h2><?= $event->title ?></h2>
            <!--                    <p>< ?= $event->summary ?></p>-->
            <!--                    <p>< ?= $event->description ?></p>-->
            <div class="control-address">
                <div class="pointer"><?= AmosIcons::show('map-marker', ['class' => 'am-2'], AmosIcons::DASH) ?></div>
            </div>
            <p class="boxed-data">
                <?php
                $eventLocation = '-';
                if ($event->event_location) {
                    if ($event->hasMethod('getShortEventLocation')) {
                        $eventLocation = $event->getShortEventLocation();
                    } else {
                        $eventLocation = $event->event_location;
                    }
                }
                ?>
                <span class="bold"><?= $eventLocation ?></span>
            </p>
            <p class="boxed-data">
                <span><?= $event->getCompleteAddress() ?></span>
            </p>
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
            <?php if (!isset($getData['pSurname'])) : ?>
                <?= $form->field($eventParticipantModel, 'cognome')->textInput(['placeholder' => AmosEvents::txt('#participant_cognome'), 'value' => $userData['cognome']]); ?>
            <?php else : ?>
                <?= $eventParticipantModel->getAttributeLabel('cognome') ?>: <label><?= $userData['cognome'] ?></label>
                <?= $form->field($eventParticipantModel, 'cognome', [
                    'template' => '{input}',
                    'options' => ['class' => ''],
                    'inputOptions' => ['class' => ''],
                ])->hiddenInput(['value' => $userData['cognome']])->label(false); ?>
            <?php endif; ?>

            <?php if (!isset($getData['pName'])) : ?>
                <?= $form->field($eventParticipantModel, 'nome')->textInput(['placeholder' => AmosEvents::txt('#participant_nome'), 'value' => $userData['nome']]); ?>
            <?php else : ?>
                <?= $eventParticipantModel->getAttributeLabel('nome') ?>: <label><?= $userData['nome'] ?></label>
                <?= $form->field($eventParticipantModel, 'nome', [
                    'template' => '{input}',
                    'options' => ['class' => ''],
                    'inputOptions' => ['class' => ''],
                ])->hiddenInput(['placeholder' => AmosEvents::txt('#participant_nome'), 'value' => $userData['nome']]); ?>
            <?php endif; ?>

            <?php if (!isset($getData['pEmail'])) : ?>
                <?= $form->field($eventParticipantModel, 'email')->textInput(['placeholder' => AmosEvents::txt('#participant_email'), 'value' => str_replace(array("\n", "\r"), "", $userData['email'])]); ?>
            <?php else : ?>
                <?= $eventParticipantModel->getAttributeLabel('email') ?>: <label><?= $userData['email'] ?></label>
                <?= $form->field($eventParticipantModel, 'email', [
                    'template' => '{input}',
                    'options' => ['class' => ''],
                    'inputOptions' => ['class' => ''],
                ])->hiddenInput(['placeholder' => AmosEvents::txt('#participant_email'), 'value' => str_replace(array("\n", "\r"), "", $userData['email'])]); ?>
            <?php endif; ?>

            <?php if ($event->abilita_codice_fiscale_in_form) {
                echo $form->field($eventParticipantModel, 'codice_fiscale')->textInput(['placeholder' => AmosEvents::txt('#participant_codice_fiscale'), 'value' => $userData['codice_fiscale']]);
            } ?>
            <?= $form->field($eventParticipantModel, 'azienda')->textInput(['placeholder' => AmosEvents::txt('#participant_azienda')]); ?>
            <?= $form->field($eventParticipantModel, 'note')->textarea(['placeholder' => AmosEvents::txt('#participant_note')]); ?>
        </div>
    </div>

    <?php if ($eventTypePresent && $eventType->partners): ?>
        <div class="row">
            <h3 class="col-xs-12"><?= AmosEvents::txt('#event_signup_companions_title'); ?></h3>
            <div class="col-xs-12">
                <?php \wbraganca\dynamicform\DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper_companions', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items-companions', // required: css class selector
                    'widgetItem' => '.item-companions', // required: css class
                    'limit' => $event->numero_max_accompagnatori, // the maximum times, an element can be cloned (default 999)
                    'min' => 0, // 0 or 1 (default 1)
                    'insertButton' => '.add-companion', // css class
                    'deleteButton' => '.remove-companion', // css class
                    'model' => $companions[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'nome',
                        'cognome',
                        'email',
                        'codice_fiscale',
                        'azienda',
                        'note',
                    ],
                ]); ?>

                <?= Html::button(AmosEvents::txt('#event_signup_add_companion'), ['class' => 'btn add-companion']) ?>

                <div class="container-items-companions">
                    <?php foreach ($companions as $i => $companion) : ?>
                        <div class="item-companions">
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <?= $form->field($companion, "[{$i}]cognome")->textInput(['placeholder' => AmosEvents::txt('#participant_cognome')]); ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <?= $form->field($companion, "[{$i}]nome")->textInput(['placeholder' => AmosEvents::txt('#participant_nome')]); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <?= $form->field($companion, "[{$i}]email")->textInput(['placeholder' => AmosEvents::txt('#participant_email')]); ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <?php if ($event->abilita_codice_fiscale_in_form) {
                                        echo $form->field($companion, "[{$i}]codice_fiscale")->textInput(['placeholder' => AmosEvents::txt('#participant_codice_fiscale')]);
                                    } ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <?= $form->field($companion, "[{$i}]azienda")->textInput(['placeholder' => AmosEvents::txt('#participant_azienda')]); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?= $form->field($companion, "[{$i}]note")->textarea(['placeholder' => AmosEvents::txt('#participant_note')]); ?>
                                </div>
                            </div>
                            <?= Html::button(AmosEvents::txt('#event_signup_remove_companion'), ['class' => 'btn remove-companion']) ?>
                            <hr/>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php
                \wbraganca\dynamicform\DynamicFormWidget::end();
                ?>
            </div>
        </div>
    <?php endif; ?>

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
