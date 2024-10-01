<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventAccreditationList;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\EventSeats;
use open20\amos\events\utility\EventsUtility;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * @var string $invitationId
 * @var array $companions
 * @var \open20\amos\events\models\Event $eventModel
 * @var bool $isGroup
 */

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

/** @var Event $eventModelNew */
$eventModelNew = $eventsModule->createModel('Event');
/** @var EventInvitation $eventInvitationModel */
$eventInvitationModel = $eventsModule->createModel('EventInvitation');
/** @var EventSeats $eventSeatsModel */
$eventSeatsModel = $eventsModule->createModel('EventSeats');
/** @var EventAccreditationList $eventAccreditationListModel */
$eventAccreditationListModel = $eventsModule->createModel('EventAccreditationList');
/** @var EventParticipantCompanion $eventParticipantCompanionModel */
$eventParticipantCompanionModel = $eventsModule->createModel('EventParticipantCompanion');

if ($isGroup) {
    echo Html::tag('h3', Html::tag('strong', AmosEvents::t('amosevents', "Gruppo")));
    $columns = [
        [
            'label' => 'Nome',
            'value' => function ($model) {
                return $model->nome . ' ' . $model->cognome;
            }
        ],
        'email'
    ];

} else {
    $columns = [
        'nome',
        'cognome',
        'email',
        'azienda'
    ];
    echo Html::tag('h3', Html::tag('strong', AmosEvents::txt("Companions")));
}

Pjax::begin(['timeout' => false, 'id' => "pjax-companion-id-{$invitationId}-widget"]);
$template = $eventModel->has_tickets ? '{assign-seat}{markAsAttendant}{change-companion-accreditation-list}{removeCompanion}' : '{assign-seat}';
$columns = ArrayHelper::merge($columns, [
    !$eventModel->has_tickets ? null : [
        'label' => AmosEvents::txt('Accreditation list'),
        'value' => function ($model) use ($eventParticipantCompanionModel, $eventAccreditationListModel) {
            $accreditationName = "";
            $accreditationListId = $eventParticipantCompanionModel::findOne(['id' => $model->id])['event_accreditation_list_id'];
            if (!empty($accreditationListId)) {
                $accreditation = $eventAccreditationListModel::findOne(['id' => $accreditationListId]);
                if (!empty($accreditation)) {
                    $accreditationName = $accreditation->title;
                }
            }
            return $accreditationName;
        }
    ],
    [
        'label' => AmosEvents::txt('Attendant'),
        'value' => function ($model) use ($eventParticipantCompanionModel) {
            $response = "";
            $companion = $eventParticipantCompanionModel::findOne(['id' => $model->id]);
            if ($companion && !empty($companion)) {
                $response = $companion['presenza'] ? Yii::t('amoscore', 'Yes') : Yii::t('amoscore', 'No');
            }
            return $response;
        }
    ],
]);

//SEATS MANAGEMENT
if ($eventModel->seats_management) {
    $columns [] = [
        'value' => function ($model) use ($eventModel, $eventSeatsModel) {
            $seats = $eventSeatsModel::find()
                ->andWhere(['event_id' => $eventModel->id])
                ->andWhere(['event_participant_companion_id' => $model->id])->one();
            if ($seats) {
                return $seats->getStringCoordinateSeat() .', '.$seats->getLabelStatus();
            }
            return '-';
        },
        'label' => AmosEvents::t('amosevents', 'Posto assegnato')
    ];
}

//ACTION COLUMNS
$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => $template,
    'buttons' => [
        'markAsAttendant' => function ($url, $model) use ($eventModelNew, $eventInvitationModel) {
            $btn = '';

            $invitation = $eventInvitationModel::findOne(['id' => $model->event_invitation_id]);
            $event = $eventModelNew::findOne(['id' => $invitation->event_id]);

            if (EventsUtility::hasPrivilegesLoggedUser($event)) {
                if ($event->begin_date_hour == null || (
                        (strtotime("now") >= strtotime($event->begin_date_hour . ' - 6 hours')) && (date('Y-m-d H:i:s') <= date($event->end_date_hour))
                    )
                ) {
                    $attendantTranslatedMessage = AmosEvents::txt("Attendant");
                    $errorTranslatedMessage = AmosEvents::txt("Error");
                    $markAttendanceTranslatedMessage = AmosEvents::txt("Mark as attendant");

                    //if(!$model->presenza) {
                    $confirmationUrl = Yii::$app->urlManager->createUrl(['/events/event/register-companion', 'eid' => $event->id, 'pid' => $invitation->user_id, 'cid' => $model->id, 'iid' => $invitation->id, 'booleanResponse' => true]);
                    $removeAttendanceUrl = Yii::$app->urlManager->createUrl(['/events/event/remove-companion-attendance', 'eid' => $event->id, 'pid' => $invitation->user_id, 'cid' => $model->id, 'iid' => $invitation->id, 'booleanResponse' => true]);

                    $btnId = "attendant-companion-eid_{$event->id}-pid_{$invitation->user_id}-iid_{$invitation->id}-cid_{$model->id}";
                    $divRemoveAttendanceId = "remove-attendance-{$btnId}";

                    $encodeEventTitle = htmlentities($event->title);

                    $btn = Html::a(
                            AmosIcons::show('pin-account', ['class' => '']) . ' ' . AmosIcons::show('circle-o', ['class' => '']), //AmosIcons::show('pin-account', ['class' => '']) . ' ' . Html::tag('span', $markAttendanceTranslatedMessage, ['class' => 'message']),
                            null,
                            [
                                'id' => $btnId,
                                'class' => 'btn btn-tools-secondary m-l-5',
                                'title' => AmosEvents::txt('Mark as attendant'),
                                'style' => ($model->presenza ? "display:none;" : ""),
                                'onClick' => "
                                                            $('#error-$btnId').remove();
                                                            $.ajax({
                                                                url : '$confirmationUrl', 
                                                                type: 'GET',
                                                                async: true,
                                                                success: function(response) {
                                                                    if(response) {
                                                                        $('#$btnId').hide();
                                                                        $('#$divRemoveAttendanceId').show();
                                                                        $.pjax.reload({url: '/events/event/show-companions-list-only?eid=$event->id&iid=$invitation->id', container: '#pjax-companion-id-$invitation->id-widget', async: false, timeout: false, push:false, skipOuterContainers:true});
                                                                        window.history.pushState('$encodeEventTitle', '$encodeEventTitle', window.getWidgetCurrentPageUrl());
                                                                    } else {
                                                                        $('#$btnId span.message').remove();
                                                                        $('#$btnId').append('<span id=\x22error-$btnId\x22> $errorTranslatedMessage</span>');
                                                                        $('#$btnId').show();
                                                                    }
                                                               }
                                                            });
                                                        return false;
                                                        "
                            ]
                        ) . Html::a(
                            AmosIcons::show('pin-account', ['class' => '']) . ' ' . AmosIcons::show('check-circle', ['class' => '']),//AmosIcons::show('pin-account', ['class' => '']) . ' ' . Html::tag('span', AmosEvents::txt('Remove attendance'), ['class' => 'message']),
                            null,
                            [
                                'id' => $divRemoveAttendanceId,
                                'class' => 'btn btn-tools-secondary',
                                'style' => (!($model->presenza) ? "display:none;" : ""),
                                'title' => AmosEvents::txt('Remove attendance'),
                                'onClick' => "
                                                            $('#error-$btnId').remove();
                                                            $.ajax({
                                                                url : '$removeAttendanceUrl', 
                                                                type: 'GET',
                                                                async: true,
                                                                success: function(response) {
                                                                    if(response) {
                                                                        $('#$divRemoveAttendanceId').hide();
                                                                        $('#$btnId').show();
                                                                        $.pjax.reload({url: '/events/event/show-companions-list-only?eid=$event->id&iid=$invitation->id', container: '#pjax-companion-id-$invitation->id-widget', async: false, timeout: false, push:false, skipOuterContainers:true});
                                                                        window.history.pushState('$encodeEventTitle', '$encodeEventTitle', window.getWidgetCurrentPageUrl());
                                                                    } else {
                                                                        $('#$divRemoveAttendanceId span.message').remove();
                                                                        $('#$divRemoveAttendanceId').append('<span id=\x22error-$btnId\x22> $errorTranslatedMessage</span>');
                                                                        $('#$divRemoveAttendanceId').show();
                                                                    }
                                                               }
                                                            });
                                                        return false;
                                                        "
                            ]
                        );
                    /*} else {
                        $btn = Html::a(AmosIcons::show('pin-account', ['class' => '']) . $attendantTranslatedMessage, null, ['class' => 'btn btn-primary', 'disabled' => 'disabled']);
                    }*/
                }
            }
            return $btn;
        },
        /*'translate' => function ($url, $model, $key) {
        },*/
        'change-companion-accreditation-list' => function ($url, $model) use ($eventModelNew, $eventInvitationModel, $eventAccreditationListModel) {
            $invitation = $eventInvitationModel::findOne(['id' => $model->event_invitation_id]);
            $event = $eventModelNew::findOne(['id' => $invitation->event_id]);
            if (EventsUtility::checkManager($event)) {
                $modalId = 'change-companion-accreditation-list-modal-' . $model->id;
                $selectId = 'accreditation-list-companion-' . $model->id;
                Modal::begin([
                    'header' => AmosEvents::txt("Select accreditation list"),
                    'id' => $modalId,
                ]);

                $accreditationTypesModels = $eventAccreditationListModel::find()->andWhere(['event_id' => $event->id])->orderBy('position ASC')->all();
                $accreditationTypes = [
                    null => AmosEvents::txt("Not set"),
                ];

                foreach ($accreditationTypesModels as $atModel) {
                    $accreditationTypes[$atModel->id] = $atModel->title;
                }

                $encodeEventTitle = htmlentities($event->title);

                echo Html::tag('div', Select::widget([
                    'auto_fill' => true,
                    'hideSearch' => true,
                    'theme' => 'bootstrap',
                    'data' => $accreditationTypes,
                    'model' => $model,
                    'attribute' => 'event_accreditation_list_id',
                    'value' => isset($accreditationTypes[$model->event_accreditation_list_id]) ? AmosEvents::txt($accreditationTypes[$model->event_accreditation_list_id])
                        : null,
                    'options' => [
//                                    'prompt' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                        'disabled' => false,
                        'id' => $selectId
                    ],
                    'pluginOptions' => [
                        'allowClear' => false,
                    ]
                ]), ['class' => 'm-15-0']);

                echo Html::tag('div',
                    Html::a(AmosCommunity::t('amoscommunity', 'Cancel'),
                        null,
                        ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                    . Html::a(AmosCommunity::t('amoscommunity', 'Save'),
                        null,
                        [
                            'class' => 'btn btn-primary',
                            'onclick' => "
                                    
                                    $.ajax({
                                        url : '$url', 
                                        type: 'POST',
                                        async: true,
                                        data: { 
                                            accreditationListId: $('#$selectId').val(),
                                        },
                                        success: function(response) {
                                           $('#$modalId').modal('hide');
                                           setTimeout(function() {
                                               $.pjax.reload({url: '/events/event/show-companions-list-only?eid=$event->id&iid=$invitation->id', container: '#pjax-companion-id-$invitation->id-widget', async: false, timeout: false, push:false, skipOuterContainers:true});
                                               window.history.pushState('$encodeEventTitle', '$encodeEventTitle', window.getWidgetCurrentPageUrl());
                                           }, 350);
                                       }
                                    });
                                return false;
                            "
                        ]),
                    ['class' => 'pull-right m-15-0']
                );
//                        echo $this->render('@vendor/open20/amos-community/src/views/community/change-user-role', ['model' => $model]);
                Modal::end();

                $btn = Html::a(
                    Html::a(AmosIcons::show('assignment-o', ['class' => '']),
                        null, [
                            'class' => 'btn btn-tools-secondary btn-tools-secondary-text',
                            'style' => 'margin-left:3px',
                            'title' => AmosEvents::txt('Select accreditation list'),
                            'data-toggle' => 'modal',
                            'data-target' => '#' . $modalId,
                            'onclick' => 'checkSelect2Init("' . $modalId . '", "' . $selectId . '");'
                        ]));

                return $btn;
            }
            return '';
        },
        'removeCompanion' => function ($url, $model) use ($eventModelNew, $eventInvitationModel) {
            $invitation = $eventInvitationModel::findOne(['id' => $model->event_invitation_id]);
            $event = $eventModelNew::findOne(['id' => $invitation->event_id]);
            if (EventsUtility::checkManager($event)) {
                $modalId = 'remove-companion-modal-' . $model->id;
                $selectId = 'accreditation-list-companion-' . $model->id;

                $url = "/events/event/remove-companion";

                $encodeEventTitle = htmlentities($event->title);

                Modal::begin([
                    'header' => AmosEvents::txt("Remove companion"),
                    'id' => $modalId,
                ]);

                echo AmosEvents::txt('Do you really want to remove {name_surname} as companion?', ['name_surname' => $model->nome . ' ' . $model->cognome]);

                echo Html::tag('div',
                    Html::a(Yii::t('amoscore', 'No'),
                        null,
                        ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal'])
                    . Html::a(Yii::t('amoscore', 'Yes'),
                        null,
                        [
                            'class' => 'btn btn-primary',
                            'onclick' => "
                                    
                                    $('#$modalId a.btn').addClass('disabled');
                                    $.ajax({
                                        url : '$url', 
                                        type: 'GET',
                                        async: true,
                                        data: { 
                                            eid: {$event->id},
                                            iid: {$invitation->id},
                                            cid: {$model->id},
                                            booleanResponse: 1,
                                        },
                                        success: function(response) {
                                           $('#$modalId').modal('hide');
                                           setTimeout(function() {
                                               $.pjax.reload({url: '/events/event/show-companions-list-only?eid=$event->id&iid=$invitation->id', container: '#pjax-companion-id-$invitation->id-widget', async: false, timeout: false, push:false, skipOuterContainers:true});
                                               window.history.pushState('$encodeEventTitle', '$encodeEventTitle', window.getWidgetCurrentPageUrl());
                                           }, 350);
                                       }
                                    });
                                return false;
                            "
                        ]),
                    ['class' => 'pull-right m-15-0']
                );
//                        echo $this->render('@vendor/open20/amos-community/src/views/community/change-user-role', ['model' => $model]);
                Modal::end();

                $btn = Html::a(
                    Html::a(AmosIcons::show('close', ['class' => '']),
                        null, [
                            'class' => 'btn btn-danger-inverse',
                            'style' => 'margin-left:3px',
                            'title' => AmosEvents::txt('Remove companion'),
                            'data-toggle' => 'modal',
                            'data-target' => '#' . $modalId,
                            'onclick' => 'checkSelect2Init("' . $modalId . '", "' . $selectId . '");'
                        ]));

                return $btn;
            }
            return '';
        },
        'assign-seat' => function ($url, $model) use ($eventModel, $eventSeatsModel) {
            if (EventsUtility::checkManager($eventModel)) {
                if ($eventModel->seats_management) {
                    $hasSeat = $eventSeatsModel::find()
                        ->andWhere(['event_id' => $eventModel->id])
                        ->andWhere(['event_participant_companion_id' => $model->id])->count();
                    if (!$hasSeat) {
                        $btn = Html::a(
                            AmosIcons::show('seat'), [
                            '/events/event/assign-seat',
                            'id' => $eventModel->id,
                            'user_id' => null,
                            'event_companion_id' => $model->id],
                            ['title' => AmosCommunity::t('amosevents', 'Assegna posto'),
                                'class' => 'btn btn-tools-secondary'
                            ]
                        );
                    } else {
                        $btn = Html::a(
                            AmosIcons::show('close-circle-o'), [
                            '/events/event/remove-seat',
                            'id' => $eventModel->id, 'user_id' => null,
                            'event_companion_id' => $model->id
                        ],
                            ['title' => AmosCommunity::t('amosevents', 'Libera posto'),
                                'class' => 'btn btn-tools-secondary',
                                'data-confirm' => AmosEvents::t('amosevents', 'Sei sicuro di liberare il posto?')
                            ]
                        );
                    }
                }
            }
            return $btn;
        }
    ],
];

echo GridView::widget([
    'id' => 'companions-list-id-' . $invitationId,
    'dataProvider' => new ActiveDataProvider([
        'query' => $companions,
        'pagination' => false
    ]),
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '-'
    ],
    'responsive' => true,
    'export' => false,
    //'pjax' => true,
    'columns' => array_filter($columns),
]);

Pjax::end();
