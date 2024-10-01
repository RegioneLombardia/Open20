<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\utility\EventsUtility;
use kartik\popover\PopoverX;
use yii\widgets\DetailView;

$this->title = $event['title'] . ' - ' . AmosEvents::txt('Participant {name_surname} detail', ['name_surname' => $invitation['name'] . ' ' . $invitation['surname']]);

$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var \open20\amos\events\models\Event $eventModelNew */
$eventModelNew = $eventsModule->createModel('Event');
/** @var EventInvitation $eventInvitationModel */
$eventInvitationModel = $eventsModule->createModel('EventInvitation');

    $attributes = [
        'name',
        'surname',
        $event['abilita_codice_fiscale_in_form'] ?
            [
                'label' => AmosEvents::txt('#fiscalcode'),
                'attribute' => 'fiscal_code',
            ] :
            null,
        'email',
        'company',
        [
            'label' => AmosEvents::txt('Accreditation list'),
            'attribute' => 'accreditationList.title',
        ],
        [
            'label' => AmosEvents::txt('Ticket sent'),
            'value' => function ($model) {
                return $response = $model->is_ticket_sent ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
            }
        ],
        [
            'label' => AmosEvents::txt('Attendant'),
            'value' => function ($model) {
                return $response = $model->presenza ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
            }
        ],
        [
            'label' => AmosEvents::txt('Ticket downloaded at'),
            'attribute' => 'ticket_downloaded_at',
        ],
        [
            'label' => AmosEvents::txt('Ticket downloaded by'),
            'value' => function ($model) {
                if(!empty($model->ticket_downloaded_by)){
                    return User::findOne(['id' => $model->ticket_downloaded_by])->getUserProfile()->one()->getNomeCognome();
                } else {
                    return null;
                }
            }
        ],
        [
            'label' => AmosEvents::txt('#participant_note'),
            'attribute' => 'notes',
        ],
    ];

    if ($eventsModule->enableGdpr) {
        $attributes[] = [
            'label' => AmosEvents::txt('#form_section_gdpr'),
            'format' => 'html',
            'value' => function ($model) use ($eventModelNew) {
                $event = $eventModelNew::findOne(['id' => $model->event_id]);
                $gdprQuestions = '';
                if(!empty($event['gdpr_question_1'])) {
                    $gdprAnswer = (($model->gdpr_answer_1 && !empty($model->gdpr_answer_1)) ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No'));
                    $gdprQuestions .= "<strong>{$event['gdpr_question_1']}</strong> {$gdprAnswer}";
                }
                if(!empty($event['gdpr_question_2'])) {
                    $gdprAnswer = (($model->gdpr_answer_2 && !empty($model->gdpr_answer_2)) ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No'));
                    $gdprQuestions .= "<br /><strong>{$event['gdpr_question_2']}</strong> {$gdprAnswer}";
                }
                if(!empty($event['gdpr_question_3'])) {
                    $gdprAnswer = (($model->gdpr_answer_3 && !empty($model->gdpr_answer_3)) ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No'));
                    $gdprQuestions .= "<br /><strong>{$event['gdpr_question_3']}</strong> {$gdprAnswer}";
                }
                if(!empty($event['gdpr_question_4'])) {
                    $gdprAnswer = (($model->gdpr_answer_4 && !empty($model->gdpr_answer_4)) ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No'));
                    $gdprQuestions .= "<br /><strong>{$event['gdpr_question_4']}</strong> {$gdprAnswer}";
                }
                if(!empty($event['gdpr_question_5'])) {
                    $gdprAnswer = (($model->gdpr_answer_5 && !empty($model->gdpr_answer_5)) ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No'));
                    $gdprQuestions .= "<br /><strong>{$event['gdpr_question_5']}</strong> {$gdprAnswer}";
                }
                return $gdprQuestions;
            }
        ];
    }

    if($event['has_qr_code']) {
        $attributes[] = [
            'label' => AmosEvents::txt('QR code'),
            'format' => 'raw',
            'value' => function ($model) use ($eventModelNew) {
                $event = $eventModelNew::findOne(['id' => $model->event_id]);
                $qrcode = EventsUtility::createQrCode($event, $model, 'participant');

                return PopoverX::widget([
                    'header' => AmosEvents::txt('QR code') . ' ' . $model->name . ' ' . $model->surname,
                    'size' => PopoverX::SIZE_LARGE,
                    'placement' => PopoverX::ALIGN_RIGHT,
                    'content' => Html::tag('div', $qrcode, ['align' => 'center']),
                    'toggleButton' => ['label' => AmosEvents::txt('Show'), 'class' => 'btn btn-default'],
                ]);
            }
        ];
    }

    echo DetailView::widget([
        'model' => $invitation,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-'
        ],
        'attributes' => array_filter($attributes),
    ]);
?>

<?php if(!empty($companions)) : ?>
<h3><?= AmosEvents::txt('Companions'); ?></h3>

<?php foreach($companions as $companion) : ?>
        <hr /><br />

    <?php
        $attributes = [
            'nome',
            'cognome',
            'email',
            $event['abilita_codice_fiscale_in_form'] ?
                [
                    'label' => AmosEvents::txt('#fiscalcode'),
                    'attribute' => 'codice_fiscale',
                ] :
                null,
            'azienda',
            [
                'label' => AmosEvents::txt('Accreditation list'),
                'attribute' => 'accreditationList.title',
            ],
            [
                'label' => AmosEvents::txt('Attendant'),
                'value' => function ($model) {
                    return $response = $model->presenza ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
                }
            ],
            [
                'label' => AmosEvents::txt('#participant_note'),
                'attribute' => 'note',
            ],
        ];

        if($event['has_qr_code']) {
            $attributes[] = [
                'label' => AmosEvents::txt('QR code'),
                'format' => 'raw',
                'value' => function ($model) use ($eventModelNew, $eventInvitationModel) {
                    $invitation = $eventInvitationModel::findOne(['id' => $model->event_invitation_id]);
                    $event = $eventModelNew::findOne(['id' => $invitation->event_id]);
                    $qrcode = EventsUtility::createQrCode($event, $invitation, 'companion', $model);

                    return PopoverX::widget([
                        'header' => AmosEvents::txt('QR code') . ' ' . $model->nome . ' ' . $model->cognome,
                        'size' => PopoverX::SIZE_LARGE,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Html::tag('div', $qrcode, ['align' => 'center']),
                        'toggleButton' => ['label'=> AmosEvents::txt('Show'), 'class'=>'btn btn-default'],
                    ]);
                }
            ];
        }

        echo DetailView::widget([
            'model' => $companion,
            'formatter' => [
                'class' => 'yii\i18n\Formatter',
                'nullDisplay' => '-'
            ],
            'attributes' => array_filter($attributes),
        ])
    ?>


<?php endforeach; ?>

<?php endif; ?>

<div class="row">
    <div class="col-xs-4">
        <?=
            Html::a(
                 Yii::t('amoscore', 'Go back'),
                 '/events/event/view?id='. $event['id']. '#tab-participants',
                 ['class' => 'btn btn-secondary']
            );
        ?>
    </div>
    <div class="col-xs-8">
        <div class="pull-right">
            <?php
                if(!empty($invitation['ticket_downloaded_at']) && EventsUtility::checkManager($eventModelNew::findOne(['id' => $event['id']]))) {
                    echo Html::a(
                        AmosEvents::txt('Download Tickets'),
                        ['/events/event/download-tickets', 'eid' => $event['id'], 'iid' => $invitation['id'], 'code' => $invitation['code']],
                        ['class' => 'btn btn-primary', 'target' => '_blank']
                    );
                }
            ?>
        </div>
    </div>
</div>
