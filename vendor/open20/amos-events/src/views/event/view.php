<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsList;
use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\MapWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventSeats;
use open20\amos\events\models\EventType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\events\widgets\CommunityEventMembersWidget;
use open20\amos\events\widgets\InvitedToEventWidget;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $model
 * @var string $position
 * @var ArrayDataProvider|null $dataProviderSeats
 * @var ArrayDataProvider|null $dataProviderSlots
 * @var yii\data\ActiveDataProvider $dataProviderEvents
 * @var string $currentView
 */

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

/** @var EventInvitation $eventInvitationModel */
$eventInvitationModel = $eventsModule->createModel('EventInvitation');

/** @var EventSeats $eventSeatsModel */
$eventSeatsModel = $eventsModule->createModel('EventSeats');

/** @var EventInvitation $evtInv */
$evtInv = $eventsModule->createModel('EventInvitation');
if ($eventsModule->enableAutoInviteUsers) {
    $loggedUserRegisteredInvitation = $evtInv::find()->andWhere([
        'event_id' => $model->id,
        'type' => EventInvitation::INVITATION_TYPE_REGISTERED,
        'state' => EventInvitation::INVITATION_STATE_INVITED,
        'user_id' => Yii::$app->user->id,
    ])->one();
}

// $communityPresent = ($model->event_management && !is_null($model->community) && is_null($model->community->deleted_at));
$communityPresent = (!is_null($model->community) && is_null($model->community->deleted_at));
$enableCalendarsManagement = $eventsModule->enableCalendarsManagement;
$enableGalleryAttachment = $eventsModule->enableGalleryAttachment;
$enableRelatedEvents = $eventsModule->enableRelatedEvents;
$hasPrivilegesLoggedUser = EventsUtility::hasPrivilegesLoggedUser($model);

$eventType = $model->eventType;
$eventTypePresent = !is_null($eventType);
if ($eventTypePresent) {
    $eventTypeInformative = ($eventType->event_type == EventType::TYPE_INFORMATIVE);
} else {
    $eventTypeInformative = false;
}

$this->registerJs(<<<JS
    $(document).on("pjax:timeout", function(event) {

    // Prevent default timeout redirection behavior

    event.preventDefault()

});
JS
    , \yii\web\View::POS_LOAD);

$invitedGridId = 'pjax-invited-list-grid';

$controller = Yii::$app->controller;
$isActionUpdate = ($controller->action->id == 'update');
$confirm = $isActionUpdate ? [
    'confirm' => \open20\amos\core\module\BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
] : null;

/*
 * Commentato per non mostrare la fascia relativa alla community anche quando si arriva da un plugin dalla dashboard.
 */
if ($controller->hasProperty('model')) {
    if ($model->hasProperty('community_id')) {
        $communityId = $model->community_id;
        $community = \open20\amos\community\models\Community::findOne($communityId);
    }
}

$viewParams = [
    'community' => $community,
    'model' => $model,
    'confirm' => $confirm
];

$areAvailableSeats = ($model->event_type_id != EventType::TYPE_LIMITED_SEATS ||
    ($model->event_type_id == EventType::TYPE_LIMITED_SEATS &&
    ($model->checkParticipantsQuantity() < $model->seats_available)));
$buttonClass = '';
$hideDivClass = '';
$buttonUrl = '';

if($eventsModule->enableSubscribeOnView) {
    if(EventsUtility::isEventParticipant($model->id, \Yii::$app->user->id)) {
        $invitation = EventInvitation::findOne(['event_id' => $model->id, 'user_id' => \Yii::$app->user->id]);
        $buttonUrl = Url::to(['/events/event/remove-signup-to-event', 'event' => $model->id, 'invitation' => $invitation->id]);
        $buttonMessage = AmosEvents::t('amosevents', 'Unsubscribe');
    } elseif($model->isSubscribtionsOpened() && $areAvailableSeats) {
        $buttonUrl = Url::to(['/events/event/event-signup', 'eid' => $model->id]);
        $buttonMessage = AmosEvents::t('amosevents', 'Iscriviti all\'evento');
    } else {
        $buttonClass = ' disabled';
        $buttonMessage = AmosEvents::t('amosevents', 'Impossible to subscribe');
    }
} else {
    $hideDivClass = ' hidden';
}
?>
<div class="container<?= $hideDivClass ?>">
    <div class="cta-events pull-right m-b-20">
        <a class="btn btn-primary<?= $buttonClass ?>" href="<?= $buttonUrl ?>"><?= $buttonMessage ?></a>
    </div>
</div>

<?php if (empty($communityId)) : ?>
    <div class="container">
        <?php echo $this->render("@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/events_network_scope", $viewParams); ?>
    </div>
<?php endif; ?>

<div class="container">
    <div class="row">
        <div class="container-user-button col-md-12">

            <!-- utente creatore -->
            <?= ItemAndCardHeaderWidget::widget(
                [
                    'model' => $model,
                    'publicationDateNotPresent' => true,
                    'showPrevalentPartnership' => true,
                    'enableLink' => !(CurrentUser::isPlatformGuest())
                ]
            )?>
            <div>
                <?php

                if ($model->canShowCommentsNotificationBellWidget()) {
                    // Comments notification bell widget
                    echo \open20\amos\comments\widgets\CommentsBellNotificationWidget::widget([
                        'modelClassname' => $model->modelClassName,
                        'modelId' => $model->id,
                        'buttonClass' =>  ''
                    ]);
                }
                ?>
                <?php
                $btns = '';
                if ($eventsModule->enableContentDuplication && Yii::$app->user->can('EVENT_UPDATE', ['model' => $model])) {
                    $btns .= ModalUtility::addConfirmRejectWithModal([
                        'modalId' => 'duplicate-content-modal-id-' . $model->id,
                        'modalDescriptionText' => AmosEvents::t('amosevents', '#duplicate_content_modal_text'),
                        'btnText' => AmosEvents::t('amosevents', '#duplicate_content'),
                        'btnLink' => Url::to(['/events/event/duplicate-content', 'id' => $model->id]),
                        'btnOptions' => [
                        'title' => AmosEvents::t('amosevents', '#duplicate_content'),
                        'class' => 'btn btn-primary pull-right m-l-20'
                        ]
                    ]);
                }

                if (EventsUtility::checkManager($model)) {
                    $btns .= Html::a(
                        AmosEvents::txt('Download ICS') . ' ' . AmosIcons::show('calendar',['class' => 'm-l-5']),
                        [
                            '/events/event/force-download-ics',
                            'eid' => $model->id,
                        ],
                        [
                            'class' => 'btn btn-primary pull-right',
                        ]
                    );
                    } else {
                        $invitation = $eventInvitationModel::findOne(['user_id' => \Yii::$app->user->id, 'event_id' => $model->id]);
                            if ($invitation && !empty($invitation)) {
                                $btns .= (($model->has_tickets && $invitation->everyoneInSameInvitationHasAccreditationList())
                                    ? Html::a(
                                        AmosEvents::txt('Download ticket'),[
                                            '/events/event/download-tickets',
                                            'eid' => $model->id,
                                            'iid' => $invitation->id,
                                            'code' => $invitation->code,
                                        ],
                                        [
                                            'class' => 'btn btn-primary pull-right m-l-5',
                                            'target' => '_blank'
                                        ]
                                    )
                                    : '')

                                    . ' ' . Html::a(
                                        AmosEvents::txt('Download ICS'),
                                        [
                                            '/events/event/download-ics',
                                            'eid' => $model->id,
                                            'iid' => $invitation->id,
                                            'code' => $invitation->code,
                                        ],
                                        [
                                            'class' => 'btn btn-primary pull-right',
                                        ]
                                    );
                                }
                            }
                        ?>
                <?= $btns; ?>
            </div>
        </div>
        <div class="map-callout-container<?= (\Yii::$app->user->can('ADMIN')) ? '-admin col-md-12' : 'col-md-12' ?>">
            <div class="row">
                <div class="map-container <?= (\Yii::$app->user->can('ADMIN')) ? 'col-xs-12 col-md-6' : '' ?>">
                    <?php
                        $module = Yii::$app->getModule(AmosEvents::getModuleName());
                        if ($module->enableGoogleMap) { ?>
                            <?= MapWidget::Widget(['position' => $position, 'markerTitle' => $model->event_location, 'zoom' => 10]) ?>
                    <?php } ?>  
                </div>
                
                <?php if (\Yii::$app->user->can('ADMIN')) { ?>
                    <div class="organizzazione-evento <?= (\Yii::$app->user->can('ADMIN')) ? 'col-xs-12 col-md-6' : '' ?>">
                        <div class="callout">
                            <div class="callout-title">
                                <?= AmosIcons::show('info-outline') ?>
                                <?= AmosEvents::tHtml('amosevents', 'Sezione visibile solo al ruolo ADMIN') ?>
                            </div>
                            <div class="row"> 
                                <div class="timing-info">
                                    <p ><?= $model->getAttributeLabel('begin_date_hour') . ': '; ?><span class="boxed-data"><?= Yii::$app->getFormatter()->asDatetime($model->begin_date_hour) ?></span></p>    
                                    <p ><?= $model->getAttributeLabel('end_date_hour') . ': '; ?> <span class="boxed-data"><?= ($model->end_date_hour ? Yii::$app->getFormatter()->asDatetime($model->end_date_hour) : 'Data fine non definita') ?></span></p>
                                </div>
                                <p ><?= $model->getAttributeLabel('visible_in_the_calendar') . ': '; ?> <span class="boxed-data"><?= ($model->visible_in_the_calendar) ? Yii::$app->getFormatter()->asBoolean($model->visible_in_the_calendar) : 'Visibilità non definita' ?></span></p>
                                <p ><?= $model->getAttributeLabel('publish_in_the_calendar') . ': '; ?><span class="boxed-data"><?= ($model->publish_in_the_calendar) ? Yii::$app->getFormatter()->asBoolean($model->publish_in_the_calendar) : '-' ?></span></p>
                                <p ><?= $model->getAttributeLabel('eventMembershipType') . ': '; ?> <span class="boxed-data"><?= (!is_null($model->eventMembershipType)) ? $model->eventMembershipType->title : 'Non definita' ?></span></p>
                                <p ><?= $model->getAttributeLabel('registration_limit_date') . ': '; ?> <span class="boxed-data"><?= ($model->registration_limit_date) ? Yii::$app->getFormatter()->asDate($model->registration_limit_date) : 'Non ci sono limiti' ?></span></p>
                                <p ><?= $model->getAttributeLabel('seats_available') . ': '; ?> <span class="boxed-data"><?= ($model->seats_available) ? $model->seats_available : 'Non definito' ?></span></p>
                                <p ><?= $model->getAttributeLabel('paid_event') . ': '; ?> <span class="boxed-data"><?= ($model->paid_event) ? Yii::$app->getFormatter()->asBoolean($model->paid_event) : 'Non definito' ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    
        <?php if (!$eventTypeInformative || $eventsModule->showAllTypesEventStaffAndPartecipants): ?>
        <div class="staff col-md-12">
            <?php if (!$eventsModule->hideStaffBlock && $communityPresent && (($model->status != Event::EVENTS_WORKFLOW_STATUS_DRAFT) || $model->validated_at_least_once)): ?>
                <div class="attachments m-t-30">
                    <h3><?= AmosEvents::tHtml('amosevents', 'Staff') ?></h3>
                    <?php
                    if (!$model->isNewRecord) {
                        //echo CommunityMembersWidget::widget([
                        echo CommunityEventMembersWidget::widget([
                            'model' => $model,
                            'showRoles' => [
                                Event::EVENT_MANAGER,
                                Event::EVENTS_CHECK_IN,
                                $model->getManagerRole(),
                            ],
                            'checkManagerRole' => true,
                            'targetUrlParams' => [
                                'viewM2MWidgetGenericSearch' => true
                            ],
                            'viewEmail' => true,
                            'viewInvitation' => true,
                            'addPermission' => 'EVENT_UPDATE',
                            'manageAttributesPermission' => 'EVENT_UPDATE',
                            'pageUrl' => '/events/event/view?id=' . $model->id,
                            'forceActionColumns' => (Yii::$app->user->can('EVENTS_VALIDATOR', ['model' => $model]) || Yii::$app->user->can('PLATFORM_EVENTS_VALIDATOR', ['model' => $model]) || EventsUtility::checkManager($model)),
                            'actionColumnsTemplate' => '{confirmManager}{acceptUser}{rejectUser}{relationAttributeManage}{deleteRelation}'
                        ]);
                    }
                    ?>
                </div>
            <?php endif; ?>
            <hr>
        </div>

        <div class="partecipanti col-md-12">
            <?php if ($communityPresent && $model->validated_at_least_once): ?>
                <!--< ?php $this->beginBlock('tab-participants'); ? >-->
                <div class="">
                    <h3><?= AmosEvents::tHtml('amosevents', 'Participants') ?></h3>

                    <?php
                    Pjax::begin(['timeout' => 10000, 'id' => 'pjax-participants-widget', 'enablePushState' => false]);
                    echo CommunityEventMembersWidget::widget([
                        'model' => $model,
                        'showRoles' => [
                            Event::EVENT_PARTICIPANT
                        ],
                        'checkManagerRole' => true,
                        'showAdditionalAssociateButton' => $model->has_tickets,
                        'targetUrlParams' => [
                            'viewM2MWidgetGenericSearch' => true
                        ],
                        'pjaxId' => 'pjax-participants-widget',
                        'pageUrl' => '/events/event/view?id=' . $model->id,
                        'enableAdditionalButtons' => true,
                        'enableExport' => (isset($eventsModule->params['viewParticipantsExport']) ? $eventsModule->params['viewParticipantsExport'] : false),
                        'showSearch' => (isset($eventsModule->params['viewParticipantsSearch']) ? $eventsModule->params['viewParticipantsSearch'] : true),
                        'viewEmail' => (isset($eventsModule->params['viewParticipantsEmail']) ? $eventsModule->params['viewParticipantsEmail'] : false),
                    ]);
                    Pjax::end();
                    ?>
                </div>
                <!--< ?php $this->endBlock(); ?>-->
            <?php endif; ?>
            <hr>
        </div>
        <?php endif; ?>

        <div class="allegati col-md-12">
            <div class="row nom">
                <?php
                    $attachmentsWidget = '';
                    $attachmentsWidget = AttachmentsList::widget([
                        'model' => $model,
                        'attribute' => 'eventAttachments',
                        'viewDeleteBtn' => false,
                        'viewDownloadBtn' => true,
                        'viewFilesCounter' => true,
                    ]);
                ?>
                <?= $attachmentsWidget ?>
            </div>
            <hr>
        </div>

        <?php   $arrayImg= $model->getGalleriaUrl();
        if ($enableGalleryAttachment && $arrayImg != NULL) { ?>
            <div class="section-gallery col-md-12">
                <div class="row nom">
                    <strong class="text-uppercase"><?= AmosEvents::t('amosevents', 'Gallery') ?></strong>
                    <div class="row m-t-20">
                        <?php
                        foreach($arrayImg as $image){ ?>
                            <div class="col-md-4 col-xs-6 m-b-30">
                                <img alt="<?=AmosEvents::t('amosevents', 'Immagine galleria')?>" src="<?= $image ?>" class="img-responsive">
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <?php
        }
        ?>

        <?php
        if (($enableRelatedEvents) && ($dataProviderEvents->getTotalCount() > 0)) {
            ?>
            <div class="clearfix"></div>
            
            <div class="event-index col-md-12 ">
            <hr>
                <strong class="text-uppercase"><?= AmosEvents::t('amosevents', 'Eventi Correlati') ?></strong>

                <?php
                echo DataProviderView::widget([
                    'dataProvider' => $dataProviderEvents,
                    'currentView' => $currentView,
                    'gridView' => [
                        'columns' => [

                            'title' => [
                                'attribute' => 'title',
                                'label' => 'Titolo',
                            ],

                            'eventType' => [
                                'attribute' => 'eventType.title',
                                'label' => 'Tipo',
                                'value' => 'eventType.title'
                            ],

                            'begin_date_hour:datetime' => [
                                'attribute' => 'begin_date_hour',
                                'label' => 'Ora di inizio',
                                'format' => ['date', 'php:d/m/Y H:i:s'],
                            ],

                            'end_date_hour:datetime' => [
                                'attribute' => 'end_date_hour',
                                'label' => 'Ora di fine',
                                'format' => ['date', 'php:d/m/Y H:i:s'],
                                'visible' => true
                            ],

                            'status' => [
                                'attribute' => 'status',
                                'value' => function ($events) {
                                    /** @var \open20\amos\events\models\Event $events */
                                    return $events->getWorkflowBaseStatusLabel();
                                }
                            ],
                        ],
                        'enableExport' => $eventsModule->enableExport
                    ],
                    'iconView' => [
                        'itemView' => '_icon'
                    ],
                    'calendarView' => [
                        'itemView' => '_calendar',
                        'options' => [
                            'lang' => 'it',
                        ],
                        'eventConfig' => [
                            'id' => 'id',
                            'title' => 'eventTitle',
                            'start' => 'begin_date_hour',
                            'end' => 'end_date_hour',
                            'color' => 'eventColor',
                            'url' => 'eventUrl',
                        ],

                        'array' => false, //se ci sono più eventi legati al singolo record
                        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
                    ]
                ]);
                ?>
            </div>
            <?php
        }
        ?>

        <div class="tag-container col-md-12">
            <?php
                $tagsWidget = '';
                $tagsWidget = \open20\amos\core\forms\ListTagsWidget::widget([
                    'userProfile' => $model->id,
                    'className' => $model->className(),
                    'viewFilesCounter' => true,
                ]);
            ?>
            <?= $tagsWidget ?>
        </div>
    </div>
</div>


<div class="container btn-container">
    <div class="event-view"> 
        <?php $this->beginBlock('overview'); ?>
            <div class="clearfix"></div>
                <?php $baseUrl = (!empty(Yii::$app->params['platform']['backendUrl']) ? Yii::$app->params['platform']['backendUrl'] : '') ?>
                    <div class="widget-body-content col-xs-12 nop">
                        <?= SocialShareWidget::widget([
                            'mode' => SocialShareWidget::MODE_DROPDOWN,
                            'model' => $model,
                            'configuratorId' => 'socialShare',
                            'url' => Url::to($baseUrl . '/events/event/view?id=' . $model->id, true),
                            'title' => $model->getTitle(),
                            'description' => $model->getDescription(true),
                            /* 'imageUrl'      => !empty($model->getDiscussionsTopicImage()) ? $model->getDiscussionsTopicImage()->getWebUrl('square_small') : '', */
                        ]); ?>
                    </div>
                <?php
            $visualizationsNum = 0; // ($model->hits) ? $model->hits : 0;
            $attachmentsNum = 0; // count($model->attachmentsForItemView);
            $tagsNum = 0;  // TODO
            ?>
        </div>
        <?php $this->endBlock(); ?>

        <?php if ($eventsModule->showInvitationsInEventView && $hasPrivilegesLoggedUser && $model->validated_at_least_once): ?>
            <?php $this->beginBlock('tab-invited-list'); ?>
            <div>
                <?= InvitedToEventWidget::widget(['model' => $model]); ?>
            </div>
            <?php $this->endBlock();
            ?>
            <?php
            $itemsTab[] = [
                'label' => AmosEvents::t('amosevents', '#invited_list'),
                'content' => $this->blocks['tab-invited-list'],
                'options' => ['id' => 'tab-invited-list']
            ];
            ?>
        <?php endif; ?>


        <?php
        if ($model->slots_calendar_management && (EventsUtility::isEventParticipant($model->id, \Yii::$app->user->id) || $hasPrivilegesLoggedUser)) {
            $this->beginBlock('calendars'); ?>
            <div class="attachments m-t-30">
                <div>
                    <h3><?= AmosEvents::tHtml('amosevents', 'Calendars') ?></h3>
                </div>
                <?php if ($hasPrivilegesLoggedUser) { ?>
                    <div>
                        <?php echo \yii\helpers\Html::a(AmosEvents::t('amosevents', "Aggiungi calendario"), ['/events/event-calendars/create', 'id' => $model->id], [
                            'class' => 'btn btn-primary pull-left'
                        ]) ?>
                    </div>
                <?php } ?>
                <div>
                    <?php echo AmosGridView::widget([
                        'dataProvider' => $dataProviderSlots,
                        'columns' => [
                            [
                                'attribute' => 'group',
                                'group' => true,
                                'format' => 'html',
                                'label' => AmosEvents::t('amosevents', 'Area'),
                            ],
                            [
                                'attribute' => 'title',
                                'label' => AmosEvents::t('amosevents', 'Progetto'),
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'short_description',
                                'label' => AmosEvents::t('amosevents', 'Partner'),
                                'format' => 'html',
                            ],
                            /* [
                                'value' => function ($model) {
                                    return $model->getTotNumberSlots();
                            },
                            'label' => AmosEvents::t('amosevents', 'Number of slot')
                            ], */
                            [
                                'value' => function ($model) {
                                    return $model->getNumberEmptySlots();
                                },
                                'label' => AmosEvents::t('amosevents', 'Number of available slot')
                            ],
                            [
                                'controller' => 'event-calendars',
                                'class' => ActionColumn::className(),
                                'template' => '{view}{update}{delete}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a(AmosIcons::show('calendar-check-o', [], 'dash'), $url, [
                                            'class' => 'btn btn-tools-secondary',
                                            'title' => AmosEvents::t('amosevents', "Vedi/Prenota slot")
                                        ]);
                                    },
                                    'update' => function ($url, $model) use ($hasPrivilegesLoggedUser) {
                                        if ($hasPrivilegesLoggedUser) {
                                            return Html::a(AmosIcons::show('edit'), $url, [
                                                'class' => 'btn btn-tools-secondary',
                                                'title' => AmosEvents::t('amosevents', "Aggiorna calendario"),
                                            ]);
                                        }
                                        return '';
                                    },
                                    'delete' => function ($url, $model) use ($hasPrivilegesLoggedUser) {
                                        if ($hasPrivilegesLoggedUser) {
                                            return Html::a(AmosIcons::show('delete'), $url, [
                                                'class' => 'btn btn-danger-inverse',
                                                'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di eliminare l'intero calendario?"),
                                                'title' => AmosEvents::t('amosevents', "Elimina calendario"),

                                            ]);
                                        }
                                        return '';
                                    },
                                ]
                            ]
                        ]
                    ]) ?>
                </div>
            </div>
            <?php $this->endBlock();

            $itemsTab[] = [
                'label' => AmosEvents::t('amosevents', 'Calendars'),
                'content' => $this->blocks['calendars'],
                'options' => ['id' => 'tab-calendars'],
            ];

            $this->beginBlock('mybooking'); ?>

            <?php
            $modelSearch = new \open20\amos\events\models\search\EventCalendarsSlotsSearch();
            $modelSearch->event = $model->id;
            $dataProvider = $modelSearch->mySlotsAllSearch([]);
            echo $this->render('../event-calendars-slots/my-booking', [
                'event' => $model,
                'dataProvider' => $dataProvider,
            ]);
            ?>

            <?php $this->endBlock();
            $itemsTab[] = [
                'label' => AmosEvents::t('amosevents', 'My calendar'),
                'content' => $this->blocks['mybooking'],
                'options' => ['id' => 'tab-mybooking'],
            ];

        }
        ?>

    <?php if ($model->seats_management && $hasPrivilegesLoggedUser) { ?>
                <!--< ?php $this->beginBlock('seats_management'); ? >-->
                <div class="attachments row m-t-30">
                    <h2><?= AmosEvents::tHtml('amosevents', 'Seats management') ?></h2>
                    <?php
                    $totSeats = $model->getEventSeats()->count();
                    $totEmptySeats = $model->getEventSeats()
                        ->andWhere(['status' => [EventSeats::STATUS_EMPTY, EventSeats::STATUS_TO_REASSIGN]])->count();
                    ?>


                    <div class="col-xs-6">
                        <p><strong><?= AmosEvents::tHtml('amosevents', 'Totale posti') . ': ' ?></strong><?= $totSeats ?></p>
                        <p>
                            <strong><?= AmosEvents::tHtml('amosevents', 'Posti disponibili') . ': ' ?></strong><?= $totEmptySeats ?>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <?php echo Html::button(AmosEvents::t('amosevents', "Importa posti"), [
                            'class' => 'btn btn-primary pull-right',
                            'data-toggle' => 'modal',
                            'data-target' => '#modalImport',
                        ]); ?>
                    </div>
                    <hr>
                    <div class="col-xs-12">
                        <h3><?= AmosEvents::tHtml('amosevents', 'Settori') ?></h3>
                        <div>
                            <?= AmosGridView::widget([
                                'dataProvider' => $dataProviderSeats,
                                'columns' => [
                                    [
                                        'attribute' => 'sector',
                                        'label' => AmosEvents::t('amosevents', 'Sector')
                                    ],
                                    [
                                        'value' => function ($model) use ($eventSeatsModel) {
                                            $count = $eventSeatsModel::find()
                                                ->andWhere(['sector' => $model['sector']])
                                                ->andWhere(['event_id' => $model['event_id']])
                                                ->groupBy('sector, [[row]]')->count();
                                            return ($count);
                                        },
                                        'label' => AmosEvents::t('amosevents', 'Number of rows')
                                    ],
                                    [
                                        'attribute' => 'seats',
                                        'label' => AmosEvents::t('amosevents', 'Number of seats')
                                    ],
                                    [
                                        'attribute' => 'empty_seats',
                                        'label' => AmosEvents::t('amosevents', 'Number of empty seats')
                                    ],
                                    [
                                        'class' => ActionColumn::className(),
                                        'template' => '{view}{delete}',
                                        'buttons' => [
                                            'view' => function ($url, $model) {
                                                return Html::a(AmosIcons::show('file'), ['view-sector', 'id' => $model['id']], [
                                                    'class' => 'btn btn-tools-secondary'
                                                ]);
                                            },
                                            'delete' => function ($url, $model) use ($eventSeatsModel) {
                                                if ($eventSeatsModel::isEventSeatDeletable($model['sector'], $model['event_id'])) {
                                                    return Html::a(AmosIcons::show('delete'), ['delete-sector', 'id' => $model['id']], [
                                                        'class' => 'btn btn-danger-inverse',
                                                        'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di eliminare l'intero settore?")
                                                    ]);
                                                }
                                            }
                                        ]
                                    ]
                                ]
                            ]) ?>
                        </div>
                    </div>
                </div>
                <!--< ?php $this->endBlock(); ? >-->

                <?php /*
                $itemsTab[] = [
                    'label' => AmosEvents::t('amosevents', 'Seats management'),
                    'content' => $this->blocks['seats_management'],
                    'options' => ['id' => 'tab-seats_management'],
                ]; */
                ?>
            <?php } ?>

        <?= Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => $itemsTab
            ]);
        ?>

        <?php if ($eventsModule->showButtonTornaAgliEventi) { ?>
            <?= CloseButtonWidget::widget([
                'title' => AmosEvents::t('amosevents', 'Back to events'),
                'layoutClass' => ' pull-right',
                'urlClose' => Yii::$app->session->get('previousUrl')
            ]) ?>
        <?php
            }
        ?>
    </div>

    <?= $this->render('_modal_import', ['model' => $model]); ?>
</div>
