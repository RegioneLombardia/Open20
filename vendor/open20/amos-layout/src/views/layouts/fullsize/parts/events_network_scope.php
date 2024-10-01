<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\fullsize\parts
 * @category   CategoryName
 */

use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\user\User;
use open20\amos\core\utilities\StringUtils;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventMembershipType;
use open20\amos\events\models\EventType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\layout\Module;
use yii\helpers\Url;


/**
 * @var \open20\amos\events\models\Event $model
 */

$asset = BaseAsset::register($this);

EventsAsset::register($this);

$jsReadMore = <<< JS

$("#moreTextJs .changeContentJs > .actionChangeContentJs").click(function(){
    $("#moreTextJs .changeContentJs").toggle();
    $('html, body').animate({scrollTop: $('#moreTextJs').offset().top - 120},1000);
});
JS;
$this->registerJs($jsReadMore);

/** @var \open20\amos\cwh\AmosCwh $cwhModule */
$cwhModule = Yii::$app->getModule('cwh');
$issetCwh = !is_null($cwhModule);

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

/** @var EventInvitation $eventInvitationModel */
$eventInvitationModel = $eventsModule->createModel('EventInvitation');
if ($eventsModule->enableAutoInviteUsers) {
    $loggedUserRegisteredInvitation = $eventInvitationModel::find()->andWhere([
        'event_id' => $model->id,
        'type' => EventInvitation::INVITATION_TYPE_REGISTERED,
        'state' => EventInvitation::INVITATION_STATE_INVITED,
        'user_id' => Yii::$app->user->id,
    ])->one();
}

$eventType = $model->eventType;
$eventTypePresent = !is_null($eventType);
if ($eventTypePresent) {
    $eventTypeInformative = ($eventType->event_type == EventType::TYPE_INFORMATIVE);
} else {
    $eventTypeInformative = false;
}
$communityPresent = (!is_null($model->community) && is_null($model->community->deleted_at));

$showGoToCommunityButton = (!$eventsModule->hasProperty('enableCommunitySections') ||
    ($eventsModule->hasProperty('enableCommunitySections') && EventsUtility::showCommunityButtonInView($model, $eventsModule)));

if (isset($model)) {
    $viewUrl = $model->getFullViewUrl();
    $controller = Yii::$app->controller;
    $isActionUpdate = ($controller->action->id == 'update');
    $confirm = $isActionUpdate ? [
        'confirm' => BaseAmosModule::t('amoscore', '#confirm_exit_without_saving')
    ] : null;
?>

    <div class="event-container">
        <div class="network-scope-wrapper scope-event-wrapper <?= (\Yii::$app->controller->module->id == 'community') ? 'scope-community-wrapper' : ''  ?>">
            <div class="container border-dashed">
                <div class="scope-title-container">
                        <div class="scope-title">
                            <h1>
                                <?= $model->title ?>

                                <small class="control-community">
                                    <!-- < ?php if (!$eventTypePresent) : ?> -->
                                    <?php
                                    switch ($model->eventType->event_type):
                                        case EventType::TYPE_LIMITED_SEATS:
                                            $classType = 'limited';
                                            $textEventType = Module::t('amoslayout', 'Posti limitati');
                                            $iconEventType = AmosIcons::show('ticket-star');
                                            $tooltipEventType = Module::t(
                                                'amoslayout',
                                                'Evento a posti limitati'
                                            );
                                            break;
                                        case EventType::TYPE_OPEN:
                                            $classType = 'open';
                                            $textEventType = Module::t('amoslayout', 'Evento aperto');
                                            $iconEventType = AmosIcons::show('seat');
                                            $tooltipEventType = Module::t(
                                                'amoslayout',
                                                'Evento aperto'
                                            );
                                            break;
                                        case EventType::TYPE_UPON_INVITATION:
                                            $classType = 'invite';
                                            $textEventType = Module::t('amoslayout', 'Su invito');
                                            $iconEventType = AmosIcons::show('account-box-mail');
                                            $tooltipEventType = Module::t(
                                                'amoslayout',
                                                'Evento su invito'
                                            );
                                            break;
                                        case EventType::TYPE_INFORMATIVE:
                                            $classType = 'info';
                                            $textEventType = Module::t('amoslayout', 'Evento informativo');
                                            $iconEventType = AmosIcons::show('info-outline');
                                            $tooltipEventType = Module::t(
                                                'amoslayout',
                                                'Evento informativo'
                                            );
                                            break;
                                        default:
                                            $classType = '';
                                    endswitch;
                                    ?>
                                    <span class="event-status <?= $classType ?>">
                                        <?=
                                        Html::tag('span',  $iconEventType, ['class' => 'username'])
                                       
                                        ?>
                                        <?= $textEventType ?>
                                    </span>
                                    <?php if (\Yii::$app->controller->module->id == 'community') : ?>
                                        <?php
                                        switch ($model->community->community_type_id):
                                            case CommunityType::COMMUNITY_TYPE_CLOSED:
                                                $classType = 'closed';
                                                $textCommunityType = Module::t('amoslayout', 'Community ristretta ai partecipanti');
                                                $iconCommunityType = AmosIcons::show('eye-off');
                                                $tooltipCommunityType = Module::t(
                                                    'amoslayout',
                                                    'Community visibile ai soli partecipanti'
                                                );
                                                break;
                                            case CommunityType::COMMUNITY_TYPE_OPEN:
                                                $classType = 'open';
                                                $textCommunityType = Module::t('amoslayout', 'Community aperta');
                                                $iconCommunityType = AmosIcons::show('lock-open');
                                                $tooltipCommunityType = Module::t(
                                                    'amoslayout',
                                                    'Contenuti disponibili a tutti gli utenti della piattaforma'
                                                );
                                                break;
                                            case CommunityType::COMMUNITY_TYPE_PRIVATE:
                                                $classType = 'private';
                                                $textCommunityType = Module::t('amoslayout', 'Community riservata ai partecipanti');
                                                $iconCommunityType = AmosIcons::show('lock-outline');
                                                $tooltipCommunityType = Module::t(
                                                    'amoslayout',
                                                    'Contenuti disponibili ai soli partecipanti alla community'
                                                );
                                                break;
                                            default:
                                                $classType = '';
                                        endswitch;
                                        ?>
                                        <span class="community-status <?= $classType ?>">
                                            <?=
                                            Html::a(
                                                $iconCommunityType,
                                                'javascript::void(0)',
                                                [
                                                    'title' => $tooltipCommunityType,
                                                    'data-toggle' => 'tooltip'
                                                ]
                                            );
                                            ?>
                                            <?= $textCommunityType ?>
                                        </span>
                                    <?php endif; ?>
                                    <!-- < ?php endif; ?> -->
                                </small>

                            </h1>
                        </div>
                    
                    <div class="actions-scope">
                        <div class="wrap-icons">

                            <?php
                            echo ContextMenuWidget::widget([
                                'model' => $model,
                                'actionModify' => "/events/event/update?id=" . $model->id,
                                'actionDelete' => "/events/event/delete?id=" . $model->id,
                                'layout' => '@vendor/open20/amos-layout/src/views/widgets/context_menu_widget_network_scope.php'
                            ])
                            ?>

                            <!-- < ?php
                            $url = !empty(\Yii::$app->params['platform']['backendUrl']) ? \Yii::$app->params['platform']['backendUrl'] : "";
                            echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                                'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_DROPDOWN,
                                'configuratorId' => 'socialShare',
                                'model' => $model,
                                'url' => \yii\helpers\Url::to($url . '/events/event/public?id=' . $model->id, true), //TODO VIEW
                                'title' => $model->title,
                                'imageUrl' => $url,
                            ]);
                            ?> -->

                            <!-- < ?php
                            $reportModule = \Yii::$app->getModule('report');
                            if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                                echo \open20\amos\report\widgets\ReportDropdownWidget::widget([
                                    'model' => $model,
                                ]);
                            }
                            ?> -->
                        </div>
                    </div>
                </div>
                <div class="cta-network-scope flexbox">

                    <?php if (\Yii::$app->controller->module->id == 'community') : ?>
                        <a href="<?= $model->getFullViewUrl() ?>?reset-scope=true" class="link-all text-uppercase align-items-center" title="Vai alla pagina dell\'evento">
                            <span class="am am-arrow-left"></span>
                            <span><?= AmosEvents::t('amosevents', 'Dettagli evento') ?></span>
                        </a>
                    <?php else : ?>
                        <a href="/site/to-menu-url?url=/it/events/event/all-events?reset-scope=true" class="link-all text-uppercase align-items-center" title="Visualizza la lista degli eventi">
                            <span class="am am-arrow-left"></span>
                            <span><?= AmosEvents::t('amosevents', 'Tutti gli eventi') ?></span>
                        </a>

                    <?php endif; ?>

                    <div class="cta-events">

                        <?php
                        $unsubscribeLink = false;
                        $showButton = ($communityPresent &&
                            ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) &&
                            (
                                (!$eventsModule->enableAutoInviteUsers && ($model->event_type_id != EventType::TYPE_UPON_INVITATION)) ||
                                ($eventsModule->enableAutoInviteUsers && !is_null($loggedUserRegisteredInvitation) && ($model->event_type_id != EventType::TYPE_INFORMATIVE))));

                        $showButtonSignup = ($communityPresent && ($model->status == Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) && $model->has_tickets);
                        $button = [
                            'text' => '',
                            'url' => '#',
                            'options' => [
                                'class' => 'btn btn-primary ml-3',
                            ]
                        ];
                        $navigationLink = '';
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

                        //TODO CHECK
                        if (!is_null($model->registration_limit_date)) {
                            $today = date('Y-m-d');
                            if ($today > $model->registration_limit_date) {
                                $messagge = AmosEvents::t('amosevents', '#registration_limit_date_expired');
                            }
                        }

                        if (!is_null($model->registration_date_end)) {
                            $today = date('Y-m-d');
                            $showLimitDate = true;
                            if ($today > $model->registration_date_end) {
                                $messagge = AmosEvents::t('amosevents', '#registration_limit_date_expired');
                            }
                        }
                        //END CHECK

                        if (!$userInList) {
                            $button['text'] = AmosEvents::t('amosevents', 'Subscribe');
                            $button['url'] = ['/events/event/event-signup', 'eid' => $model->id];
                            if ($eventsModule->enableAutoInviteUsers && !is_null($loggedUserRegisteredInvitation)) {
                                $button['url']['pCode'] = $loggedUserRegisteredInvitation->code;
                            } else {
                                $button['options']['target'] = '_blank';
                            }
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
                                        '/community/join/open-join',
                                        'id' => $model->community_id
                                    ];
                                    $button['text'] =
                                        $button['url'] = Yii::$app->urlManager->createUrl($createUrlParams);
                                    $showButton = false;
                                    $unsubscribeLink = true;

                                    break;
                            }
                        }
                        ?>

                        <?php if ($eventsModule->enableCommunitySections && !$eventTypeInformative && $model->show_community): ?>
                            <?php if ($userInList) : ?>
                                <?php if (\Yii::$app->controller->module->id == 'community') :
                                    $loggedUserId = Yii::$app->getUser()->getId();
                                    if (!empty($loggedUserId)) {
                                        $userProfile = User::findOne($loggedUserId)->getProfile();
                                        $userCommunity = CommunityUtil::getMemberCommunityLogged($model->community->id);
                                    }
                                    if (!empty($userProfile) && $userProfile->validato_almeno_una_volta && !is_null($userCommunity)) {
                                        if (in_array($userCommunity->status, [CommunityUserMm::STATUS_WAITING_OK_COMMUNITY_MANAGER, CommunityUserMm::STATUS_WAITING_OK_USER])) {
                                            $isWaitingToSigned = true;
                                        } else {
                                            $isSigned = true;
                                        }
                                    } else {
                                        $isSigned = false;
                                    } ?>
                                    <?php $isCreatorCommunity = ($model->community->created_by == \Yii::$app->user->id) && !\Yii::$app->user->can("ADMIN"); ?>
                                    <?php if ($isSigned) : ?>
                                    <?php if (!$isCreatorCommunity) :
                                        $label = $label . ' ' . Module::t('amoscommunity', 'e alla community come') . ' ' . Module::t('amoslayout', "{$model->community->getRoleByUser()}");
                                    endif; ?>
                                <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>

                            <small>
                                <?php if (!$userInList) : ?>
                                    <?= ($showLimitDate) ? '<em>' . Module::t('amosevents', 'Iscrizioni aperte fino al') . '</em>' . ' ' . Yii::$app->getFormatter()->asDate($model->registration_date_end) : '' ?>
                                    <?= Html::a($button['text'], $button['url'], $button['options']) ?>
                                <?php else : ?>
                                    <?php if (!empty($label)) : ?>
                                        <?= $label . ' | ' ?>
                                    <?php endif; ?>
                                <?php endif; ?>
            
                                <?php if ($userInList && $unsubscribeLink) : ?>
                                    <a class="text-danger ml-4"
                                       href="<?= Url::to(['/events/event/elimina-m2m', 'id' => $model->id, 'targetId' => \Yii::$app->user->id, 'redirectAction' => $model->getFullViewUrl()]) ?>"
                                       title="<?= Module::t('amoslayout', 'Disiscriviti dall\'evento') . ' ' . $model->title ?>">
                                        <?= Module::t('amoslayout', 'disiscriviti') ?>
                                    </a>
                                <?php endif; ?>

                            </small>
        
                            <?php
                            if ($userInList && (\Yii::$app->controller->module->id != 'community')) {
                                echo Html::a(
                                    AmosEvents::t('amosevents', 'Go to the community') . AmosIcons::show('arrow-right'),
                                    Yii::$app->urlManager->createUrl($createUrlParams),
                                    [
                                        'title' => AmosEvents::t('amosevents', 'Visita la community dell\'evento {eventName}', ['eventName' => $model->title]),
                                        'class' => 'link-all text-uppercase'
                                    ]
                                );
                            }
                            ?>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="event-container d-flex flex-column flex-lg-row light-theme">
                    <div class="calendar-img-container">
                        <div class="d-flex flex-column-reverse flex-md-row">
                            <div class="d-flex flex-row flex-md-column ">
                                <div class="date pt-3 py-md-2 px-md-4 mr-0 mr-md-3 mb-1 d-flex flex-md-column justify-content-md-center align-items-md-center text-uppercase flex-md-grow-1 lightgrey-bg-c1">

                                    <p class="pr-2 pr-md-0 font-weight-bold mb-0 h2 d-none"><?= date("d", strtotime($model->begin_date_hour)) ?></p>
                                    <p class="pr-2 pr-md-0 font-weight-bold mb-0 h4 d-block d-md-none "><?= date("d", strtotime($model->begin_date_hour)) ?></p>
                                    <p class="font-weight-bold pr-2 pr-md-0 mb-0 h4"><?= Yii::$app->formatter->asDate($model->begin_date_hour, 'MMM') ?></p>
                                    <p class="font-weight-normal mb-0 h4"><?= date("Y", strtotime($model->begin_date_hour)) ?></p>
                                </div>
                                <div class="hour d-md-flex align-items-center justify-content-start mt-1 mr-3 py-4 px-5 bg-tertiary">
                                    <span class="am am-time"></span> <span class="mb-0 lead text-white"><?= ($model->begin_date_hour ? Yii::$app->getFormatter()->asTime($model->begin_date_hour) : '-') ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                        $url = '/img/img_default.jpg';
                        if (!is_null($model->getEventLogo())) {
                            $url = $model->getEventLogo()->getWebUrl('original', false, true);
                        }
                        ?>

                        <div class="external-image-container">
                            <div class="image-wrapper">
                                <?php
                                $url = $model->getEventsImageUrl('square_large', false);
                                $logo = Html::img($url, [
                                    'alt' => $model->getAttributeLabel('eventLogo'),
                                    'class' => 'event-image img-fluid w-100'
                                ]);
                                ?>
                                <?= $logo ?>
                            </div>
                        </div>
                    </div>

                    <div class="scope-info-wrapper">
                        <h2 class="mb-0 subtitle h3"><?= $model->summary ?></h2>
                        <p class="info-data bold">
                            <?php
                            $eventLocation = '';
                            if ($model->event_location) {
                                if ($model->hasMethod('getShortEventLocation')) {
                                    $eventLocation = $model->getShortEventLocation();
                                } else {
                                    $eventLocation = $model->event_location;
                                }
                            }
                            ?>
                            <span><?= !empty($model->event_address) ? $model->event_address . ' - ' : '' ?></span>
                            <span><?= !empty($model->event_address_house_number) ? $model->event_address_house_number : ' ' ?></span>
                            <span><?= !empty($model->event_address_cap) ? $model->event_address_cap : '' ?></span>
                            <span><?= !empty($model->cityLocation) ? $model->cityLocation->nome : '' ?><?= ($model->provinceLocation) ? ' (' . $model->provinceLocation->sigla . ')' : '' ?></span>
                            <span><?= $eventLocation ?> </span>
                            <span><?= !empty($model->countryLocation) ? $model->countryLocation->nome : '' ?></span>
                        </p>

                        <?php
                        $desclen = 250;
                        ?>
                        <?php if (strlen($model->description) <= $desclen) : ?>
                            <?= $model->description ?>
                        <?php else : ?>
                            <div id="moreTextJs" class="m-t-15">
                                <?php
                                $moreContentTextLink = Module::t('amoslayout', 'espandi descrizione') . ' ' . AmosIcons::show("chevron-down");
                                $moreContentTitleLink = Module::t('amoslayout', 'Leggi la descrizione completa');

                                $lessContentTextLink = Module::t('amoslayout', 'riduci descrizione') . ' ' . AmosIcons::show("chevron-up");
                                $lessContentTitleLink = Module::t('amoslayout', 'Riduci testo');
                                ?>
                                <div class="changeContentJs partialContent">
                                    <?=
                                    StringUtils::truncateHTML($model->description, $desclen)
                                    ?>
                                    <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>"><?= $moreContentTextLink ?></a>
                                </div>
                                <div class="changeContentJs totalContent" style="display:none">
                                    <?= $model->description ?>
                                    <a class="actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>"><?= $lessContentTextLink ?></a>
                                </div>
                            </div>
                        <?php endif ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>