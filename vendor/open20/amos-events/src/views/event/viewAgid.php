<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsTable;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\MapWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventMembershipType;
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
 */

$this->title = strip_tags($model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

/** @var \amos\sitemanagement\Module|AmosModule|CmsModuleInterface $siteManagementModule */
$siteManagementModule = \Yii::$app->getModule('sitemanagement');

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
$hasPrivilegesLoggedUser = EventsUtility::hasPrivilegesLoggedUser($model);

$this->registerJs(<<<JS
    $(document).on("pjax:timeout", function(event) {

    // Prevent default timeout redirection behavior

    event.preventDefault()

});
JS
    , \yii\web\View::POS_LOAD);

$invitedGridId = 'pjax-invited-list-grid';

?>
<div class="container">
<div class="event-view col-xs-12 nop">
    <?php $this->beginBlock('overview'); ?>
    <h1><?= AmosEvents::t('amosevents', 'Overview'); ?></h1>
    <div class="col-xs-12 nom nop">
        <?= ItemAndCardHeaderWidget::widget([
            'model' => $model,
            'publicationDateField' => 'created_at',
        ]) ?>

        
        <div class='col-sm-7 col-xs-12 nop'>
            <div class="nop media">
                <div class="col-xs-12 col-sm-3 nopl">
                    <?php
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->getEventLogo())) {
                        $url = $model->getEventLogo()->getUrl('original', false, true);
                    }
                    ?>
                    <?= Html::img($url, [
                        'title' => $model->getAttributeLabel('eventLogo'),
                        'class' => 'img-responsive'
                    ]); ?>
                </div>

                <div class="media-body">
                    <div class="col-xs-9">
                        <p class="media-heading">
                            <?= AmosEvents::t('amosevents', 'Event'); ?>
                        </p>
                        <h2 class="media-heading">
                            <?= $model->title ?>
                        </h2>
                    </div>
                    <?= ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/events/event/update?id=" . $model->id,
                        'actionDelete' => "/events/event/delete?id=" . $model->id,
                        'modelValidatePermission' => 'EventValidate'
                    ]) ?>

                    <div class="col-xs-12 nop m-t-15">
                        <div class="col-sm-4 col-xs-12">
                            <?= $model->getAttributeLabel('agid_event_typology_id') ?>
                            <br/>
                            <span class="bold"><?= $model->agidEventTypology->name ?></span>
                        </div>
                    </div>

                </div>
            </div>
            <hr class="nom">

            <div class="clearfix"></div>
            <p class="italic m-t-15"><?= $model->getAttributeLabel('summary') ?><br><?= $model->summary ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_description') ?><br><?= $model->agid_description ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agidRelatedEventsMm') ?><br><?php
                $agidRelatedEvents = $model->agidRelatedEvents;
                ?>
                <?php foreach ($agidRelatedEvents as $agidRelatedEvent): ?>
                    - <?= $agidRelatedEvent->getTitle(); ?><br>
                <?php endforeach; ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('description') ?><br><?= $model->description ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('publication_date_begin') ?>
                <br><?= ($model->publication_date_begin ? Yii::$app->getFormatter()->asDatetime($model->publication_date_begin) : '-') ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('publication_date_end') ?>
                <br><?= ($model->publication_date_end ? Yii::$app->getFormatter()->asDatetime($model->publication_date_end) : '-') ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agidAdministrativePersonsMm') ?><br><?php
                $agidAdministrativePersons = $model->agidAdministrativePersons;
                ?>
                <?php foreach ($agidAdministrativePersons as $agidAdministrativePerson): ?>
                    - <?= $agidAdministrativePerson->getSurnameName(); ?><br>
                <?php endforeach; ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_dates_and_hours') ?><br><?= $model->agid_dates_and_hours ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_price') ?><br><?= ($model->agid_price ? $model->agid_price : '-') ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agidEventDocumentsMm') ?><br><?php
                $agidEventDocuments = $model->agidEventDocuments;
                ?>
                <?php foreach ($agidEventDocuments as $agidEventDocument): ?>
                    - <?= $agidEventDocument->getTitle(); ?><br>
                <?php endforeach; ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_organized_by') ?><br><?= ($model->agid_organized_by ? $model->agid_organized_by : '-') ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_contact') ?><br><?= ($model->agid_contact ? $model->agid_contact : '-') ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_website') ?><br><?= ($model->agid_website ? $model->agid_website : '-') ?></p>
            <p class="m-t-15"><?= $model->getAttributeLabel('agid_other_informations') ?><br><?= ($model->agid_other_informations ? $model->agid_other_informations : '-') ?></p>
            
            <?php if (!is_null($siteManagementModule)): ?>
                <?= \amos\sitemanagement\widgets\SMSliderWidget::widget(['sliderId' => $model->agid_image_slider_id]); ?>
                <?= \amos\sitemanagement\widgets\SMSliderWidget::widget(['sliderId' => $model->agid_video_slider_id]); ?>
            <?php endif; ?>
            
            <?php $baseUrl = (!empty(Yii::$app->params['platform']['backendUrl']) ? Yii::$app->params['platform']['backendUrl'] : '') ?>
            <div class="widget-body-content col-xs-12 nop">
                <?= SocialShareWidget::widget([
                    'mode' => SocialShareWidget::MODE_DROPDOWN,
                    'model' => $model,
                    'configuratorId' => 'socialShare',
                    'url' => Url::to($baseUrl . '/events/event/view?id=' . $model->id, true),
                    'title' => $model->getTitle(),
                    'description' => $model->getDescription(true),
//                'imageUrl'      => !empty($model->getDiscussionsTopicImage()) ? $model->getDiscussionsTopicImage()->getWebUrl('square_small') : '',
                ]); ?>
            </div>
        </div>
        <div class="col-sm-5 col-xs-12">
            <?= AmosEvents::t('amosevents', 'Informations'); ?>
            <div class="container-sidebar col-xs-12 nop">
                <div class="box col-xs-12 nop">
                    <div class="col-sm-6">
                        <span><?= $model->getAttributeLabel('begin_date_hour'); ?></span>
                        <p class="boxed-data"><?= Yii::$app->getFormatter()->asDatetime($model->begin_date_hour) ?></p>
                    </div>

                    <div class="col-sm-6">
                        <span><?= $model->getAttributeLabel('end_date_hour'); ?></span>
                        <p class="boxed-data"><?= ($model->end_date_hour ? Yii::$app->getFormatter()->asDatetime($model->end_date_hour) : '-') ?></p>
                    </div>

                    <div class="col-xs-12">
                        <span><?= $model->getAttributeLabel('event_location') ?></span>
                        <p class="boxed-data">
                            <?= ($model->event_location) ? $model->event_location : '' ?>
                        </p>
                    </div>
                    <div class="col-xs-12 inline-boxed">
                        <div class="col-md-2 col-sm-12">
                            <p class="boxed-data ">
                                <?= ($model->event_address_cap) ? $model->event_address_cap : '' ?>
                            </p>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <p class="boxed-data">
                                <?= ($model->cityLocation) ? $model->cityLocation->nome . ' ' : '-' ?>
                                <?= ($model->provinceLocation) ? ' (' . $model->provinceLocation->sigla . ') ' : '' ?>
                            </p>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <p class="boxed-data">
                                <?= ($model->countryLocation) ? $model->countryLocation->nome . ' ' : '' ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php
                    $module = Yii::$app->getModule(AmosEvents::getModuleName());
                    if ($module->enableGoogleMap) { ?>
                        <div class="col-xs-12">
                            <?= MapWidget::widget(['placeId' => $model->agid_geolocation, 'markerTitle' => $model->agid_geolocation, 'zoom' => 10]) ?>
                        </div>
                    <?php } ?>
                    <div class="col-sm-6">
                        <span><?= $model->getAttributeLabel('registration_limit_date'); ?></span>
                        <p class="boxed-data"><?= ($model->registration_limit_date) ? Yii::$app->getFormatter()->asDate($model->registration_limit_date) : '-' ?></p>
                    </div>

                    <div class="col-xs-12">
                        <?php
                        $showButton = (
                            $communityPresent &&
                            ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) &&
                            (
                                (!$eventsModule->enableAutoInviteUsers && ($model->event_type_id != EventType::TYPE_UPON_INVITATION)) ||
                                ($eventsModule->enableAutoInviteUsers && !is_null($loggedUserRegisteredInvitation) && ($model->event_type_id != EventType::TYPE_INFORMATIVE))
                            )
                        ); //&& $model->show_community);
                        $showButtonSignup = ($communityPresent && ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) && $model->has_tickets);
                        $button = [
                            'text' => '',
                            'url' => '#',
                            'options' => [
                                'class' => 'btn btn-primary pull-right',
                            ]
                        ];
                        $label = '';
                        
                        $userInList = 0;
                        $userStatus = '';
                        /** @var CommunityUserMm $userCommunity */
                        foreach ($model->communityUserMm as $userCommunity) { // User not yet subscribed to the event
                            if ($userCommunity->user_id == Yii::$app->user->id) {
                                $userInList = 1;
                                $userStatus = $userCommunity->status;
                                break;
                            }
                        }
                        
                        if (!is_null($model->registration_limit_date)) {
                            $today = date('Y-m-d');
                            if ($today > $model->registration_limit_date) {
                                $messagge = AmosEvents::t('amosevents', '#registration_limit_date_expired');
                            }
                        }
                        
                        if (!$userInList) {
                            $button['text'] = AmosEvents::t('amosevents', 'Subscribe');
                            //OLD LINK : $button['url'] = ['/events/event/subscribe', 'eventId' => $model->id];
                            $button['url'] = ['/events/event/event-signup', 'eid' => $model->id];
                            if ($eventsModule->enableAutoInviteUsers && !is_null($loggedUserRegisteredInvitation)) {
                                $button['url']['pCode'] = $loggedUserRegisteredInvitation->code;
                            } else {
                                $button['options']['target'] = '_blank';
                            }
                            //$button['url'] = ['/events/event/subscribe', 'eventId' => $model->id];
                            //$button['options']['data']['confirm'] = isset($messagge) ? $messagge : AmosEvents::t('amosevents', 'Do you really want to subscribe?');
                        } else {
                            switch ($userStatus) {
                                case CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER:
                                    $button['text'] = AmosEvents::t('amosevents', 'Request sent');
                                    $button['options']['class'] .= ' disabled';
                                    break;
                                case CommunityUserMm::STATUS_WAITING_OK_USER:
                                    $button['text'] = AmosEvents::t('amosevents', 'Accept invitation');
                                    $button['url'] = ['/community/community/accept-user', 'communityId' => $model->community_id, 'userId' => Yii::$app->user->id];
                                    $button['options']['data']['confirm'] = isset($messagge) ? $messagge : AmosEvents::t('amosevents', 'Do you really want to accept invitation?');
                                    break;
                                case CommunityUserMm::STATUS_ACTIVE:
                                    if ($model->event_membership_type_id == EventMembershipType::TYPE_OPEN) {
                                        $label = AmosEvents::t('amosevents', 'Already subscribed');
                                    }
                                    if ($model->event_membership_type_id == EventMembershipType::TYPE_ON_INVITATION) {
                                        $label = AmosEvents::t('amosevents', 'Invitation accepted');
                                    }
                                    $createUrlParams = [
                                        '/community/join',
                                        'id' => $model->community_id
                                    ];
                                    $button['text'] = AmosEvents::t('amosevents', 'Go to the community');
                                    $button['url'] = Yii::$app->urlManager->createUrl($createUrlParams);
                                    $showButton = ($showButton && EventsUtility::showCommunityButtonInView($model, $eventsModule));
                                    break;
                            }
                        }
                        
                        ?>
                        <div class="pull-left">
                            <?= $label ?>
                        </div>
                        
                        <?php if ($showButton): ?>
                            <?= Html::a($button['text'], $button['url'], $button['options']) ?>
                        <?php endif; ?>

                    </div>

                    <div class="col-xs-12">
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
                                AmosEvents::txt('Download ICS'),
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
                                            AmosEvents::txt('Download ticket'),
                                            [
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
            </div>
        </div>
        
        
        <?php
        $visualizationsNum = 0; // ($model->hits) ? $model->hits : 0;
        $attachmentsNum = 0; // count($model->attachmentsForItemView);
        $tagsNum = 0;  // TODO
        ?>
    </div>
    <?php $this->endBlock(); ?>
    
    <?php
    $itemsTab[] = [
        'label' => AmosEvents::t('amosevents', 'Overview'),
        'content' => $this->blocks['overview'],
        'options' => ['id' => 'tab-overview'],
    ];
    ?>
    
    <?php
    if (\Yii::$app->user->can('ADMIN')) {
        $this->beginBlock('organization'); ?>
        <div class="col-xs-12 nop">
            <h1><?= AmosEvents::tHtml('amosevents', 'Event Organization') ?></h1>
            <div class="col-sm-6 nop">
                <span><?= $model->getAttributeLabel('publish_in_the_calendar'); ?></span>
                <span class="boxed-data"><?= ($model->publish_in_the_calendar) ? Yii::$app->getFormatter()->asBoolean($model->publish_in_the_calendar) : '-' ?></span>
            </div>
            <!--div class="col-sm-6">
            <span><?= $model->getAttributeLabel('event_management'); ?></span>
            <span class="boxed-data"><?= ($model->event_management) ? Yii::$app->getFormatter()->asBoolean($model->event_management) : '-' ?></span>
        </div-->
            <div class="col-sm-6 nop">
                <span><?= $model->getAttributeLabel('registration_limit_date'); ?></span>
                <span class="boxed-data"><?= ($model->registration_limit_date) ? Yii::$app->getFormatter()->asDate($model->registration_limit_date) : '-' ?></span>
            </div>
            <div class="col-sm-6 nop">
                <span><?= $model->getAttributeLabel('seats_available'); ?></span>
                <span class="boxed-data"><?= ($model->seats_available) ? $model->seats_available : '-' ?></span>
            </div>
            <div class="col-sm-6 nop">
                <span><?= $model->getAttributeLabel('eventMembershipType'); ?></span>
                <span class="boxed-data"><?= (!is_null($model->eventMembershipType)) ? $model->eventMembershipType->title : '-' ?></span>
            </div>
            <div class="col-sm-6 nop">
                <span><?= $model->getAttributeLabel('paid_event'); ?></span>
                <span class="boxed-data"><?= ($model->paid_event) ? Yii::$app->getFormatter()->asBoolean($model->paid_event) : '-' ?></span>
            </div>
            <div class="col-sm-6 nop">
                <span><?= $model->getAttributeLabel('visible_in_the_calendar'); ?></span>
                <span class="boxed-data"><?= ($model->visible_in_the_calendar) ? Yii::$app->getFormatter()->asBoolean($model->visible_in_the_calendar) : '-' ?></span>
            </div>
        </div>
        <?php $this->endBlock(); ?>
        
        <?php
        $itemsTab[] = [
            'label' => AmosEvents::t('amosevents', 'Event Organization'),
            'content' => $this->blocks['organization'],
            'options' => ['id' => 'tab-organization'],
        ];
    }
    ?>
    
    <?php
    if (!empty($model->eventAttachments)) {
        $this->beginBlock('attachments'); ?>
        <div class="attachments col-xs-12 nop">
            <!-- TODO sostituire il tag h3 con il tag p e applicare una classe per ridimensionare correttamente il testo per accessibilità -->
            <h3><?= AmosEvents::tHtml('amosevents', 'Attachments') ?></h3>
            <?= AttachmentsTable::widget([
                'model' => $model,
                'attribute' => 'eventAttachments',
                'viewDeleteBtn' => false
            ]) ?>
        </div>
        <?php $this->endBlock(); ?>
        
        <?php
        
        $itemsTab[] = [
            'label' => AmosEvents::t('amosevents', 'Attachments'),
            'content' => $this->blocks['attachments'],
            'options' => ['id' => 'tab-attachments'],
        ];
    }
    ?>
    
    <?php $this->beginBlock('publication'); ?>
    <div class="col-xs-12 nop">
        <h3><?= AmosEvents::tHtml('amosevents', 'Publication') ?></h3>
    </div>
    <?php $this->endBlock(); ?>
    
    <?php
    //    $itemsTab[] = [
    //        'label' => AmosEvents::t('amosevents', 'Publication'),
    //        'content' => $this->blocks['publication'],
    //        'options' => ['id' => 'tab-publication'],
    //    ];
    ?>
    
    <?php if ($eventsModule->showInvitationsInEventView && $hasPrivilegesLoggedUser && $model->validated_at_least_once): ?>
        <?php $this->beginBlock('tab-invited-list'); ?>
        <div class="col-xs-12 nop">
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
    
    <?php if ($communityPresent && $model->validated_at_least_once): ?>
        <?php $this->beginBlock('tab-participants'); ?>
        <div class="col-xs-12 nop">
            <h1><?= AmosEvents::tHtml('amosevents', 'Participants') ?></h1>
            
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
        <?php $this->endBlock(); ?>
        
        <?php
        if ($hasPrivilegesLoggedUser) {
            $itemsTab[] = [
                'label' => AmosEvents::t('amosevents', 'Participants'),
                'content' => $this->blocks['tab-participants'],
                'options' => ['id' => 'tab-participants'],
            ];
        }
        ?>
    <?php endif; ?>
    
    <?php $this->beginBlock('feedback'); ?>
    <div class="attachments col-xs-12 nop">
        <h3><?= AmosEvents::tHtml('amosevents', 'Feedback') ?></h3>
    </div>
    <?php $this->endBlock(); ?>
    
    <?php
    //    $itemsTab[] = [
    //        'label' => AmosEvents::t('amosevents', 'Feedback'),
    //        'content' => $this->blocks['feedback'],
    //        'options' => ['id' => 'tab-feedback'],
    //    ];
    ?>
    
    <?php
    if ($model->slots_calendar_management && (EventsUtility::isEventParticipant($model->id, \Yii::$app->user->id) || $hasPrivilegesLoggedUser)) {
        $this->beginBlock('calendars'); ?>
        <div class="attachments col-xs-12 nop">
            <div class="col-xs-12">
                <h3><?= AmosEvents::tHtml('amosevents', 'Calendars') ?></h3>
            </div>
            <?php if ($hasPrivilegesLoggedUser) { ?>
                <div class="col-xs-12">
                    <?php echo \yii\helpers\Html::a(AmosEvents::t('amosevents', "Aggiungi calendario"), ['/events/event-calendars/create', 'id' => $model->id], [
                        'class' => 'btn btn-primary pull-left'
                    ]) ?>
                </div>
            <?php } ?>
            <div class="col-xs-12">
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
//                        [
//                            'value' => function ($model) {
//                                return $model->getTotNumberSlots();
//                            },
//                            'label' => AmosEvents::t('amosevents', 'Number of slot')
//                        ],
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
                                },
                                'delete' => function ($url, $model) use ($hasPrivilegesLoggedUser) {
                                    if ($hasPrivilegesLoggedUser) {
                                        return Html::a(AmosIcons::show('delete'), $url, [
                                            'class' => 'btn btn-danger-inverse',
                                            'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di eliminare l'intero calendario?"),
                                            'title' => AmosEvents::t('amosevents', "Elimina calendario"),
                                        
                                        ]);
                                    }
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
        <?php $this->beginBlock('seats_management'); ?>
        <div class="attachments col-xs-12 nop">
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
                <div class="col-xs-12">
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
        <?php $this->endBlock(); ?>
        
        <?php
        $itemsTab[] = [
            'label' => AmosEvents::t('amosevents', 'Seats management'),
            'content' => $this->blocks['seats_management'],
            'options' => ['id' => 'tab-seats_management'],
        ];
        ?>
    <?php } ?>
    
    <?php if ($communityPresent && (($model->status != Event::EVENTS_WORKFLOW_STATUS_DRAFT) || $model->validated_at_least_once)): ?>
        <?php $this->beginBlock('staff'); ?>
        <div class="attachments col-xs-12 nop">
            <h1><?= AmosEvents::tHtml('amosevents', 'Staff') ?></h1>
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
        <?php $this->endBlock(); ?>
        
        <?php
        if ($communityPresent && EventsUtility::checkManager($model->community)) {
            $itemsTab[] = [
                'label' => AmosEvents::t('amosevents', 'Staff'),
                'content' => $this->blocks['staff'],
                'options' => ['id' => 'tab-staff'],
            ];
        }
        ?>
    <?php endif; ?>
    
    <?= Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]);
    ?>
    
    <?= CloseButtonWidget::widget([
        'title' => AmosEvents::t('amosevents', 'Close'),
        'layoutClass' => 'col-xs-12 pull-right',
        'urlClose' => Yii::$app->session->get('previousUrl')
    ]) ?>
</div>
</div>

<?= $this->render('_modal_import', ['model' => $model]); ?>
