<?php

use open20\amos\events\AmosEvents;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;

$this->title = $model->getTitle();

$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$url = $model->getMainImageEvent();

$beginDate = new \DateTime($model->begin_date_hour);
$endDate = new \DateTime($model->end_date_hour);
$enterTime = new \DateTime($model->enter_time);

foreach ($model->eventInternalLists as $list) {
    $listsIds [] = $list->id;
}

$statuses = \open20\amos\events\models\EventInternalList::getStatusSending($listsIds);
$isEventInformative = ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_INFORMATIVE);

?>

    <div class="mt-4 neutral-1-bg-a1 p-4 info-evento">
        <div class="row variable-gutters">
            <div class="col-lg-9 col-md-7">
                <div class="row">
                    <div class="col-lg-6">
                        <?php $utility = new \amos\cmsbridge\utility\CmsUtility();
                        $templates = $utility->getCmsTemplates();
                        $class = '';
                        foreach ($templates as $template) {
                            if ($model->eventLanding->luya_template_id == $template['id']) {
                                $class = \open20\amos\events\utility\EventsUtility::getClassPreviewLanding($template['title']);
                            }
                        }
                        $htmlTemplate[$model->eventLanding->luya_template_id] = $class;
                        ?>

                        <div class="preview-landing position-relative <?= $class ?> h-auto">
                            <img src="<?= ($url) ?>" class="img-fluid w-100">

                        </div>

                    </div>
                    <div class="col-lg-6 mt-3 mt-lg-0">
                        <div class="d-flex">
                            <div class="mr-1">
                                <svg class="icon icon-lg">
                                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_place"></use>
                                </svg>
                            </div>
                            <div>
                                <p class="lead mb-0"><strong><?= $model->eventLocation->name ?></strong></p>
                                <p><?= !empty($model->eventLocationEntrance) ? $model->eventLocationEntrance->name : '' ?></p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="mr-1">
                                <svg class="icon icon-lg px-2">
                                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_event_available"></use>
                                </svg>
                            </div>
                            <div>
                                <p class="lead mb-0"><?= AmosEvents::t('amosevents', "dal") . ' ' ?>
                                    <strong><?= $beginDate->format('d/m/Y') ?></strong> <?= " - " . $beginDate->format('H:i') ?>
                                </p>
                                <p class="lead mb-0"><?= AmosEvents::t('amosevents', "al") . ' ' ?>
                                    <strong><?= $endDate->format('d/m/Y') ?></strong> <?= " - " . $endDate->format('H:i') ?>
                                </p>
                                <?php if (!empty($enterTime)) { ?>
                                    <p class="lead mb-0"><?= AmosEvents::t('amosevents', "ingresso dalle ore") . ' ' ?>
                                        <strong><?= $enterTime->format('H:i') ?></strong></p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="mr-1">
                                <svg class="icon icon-lg px-2">
                                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_people"></use>
                                </svg>
                            </div>
                            <p class="lead mb-0"><?= $model->eventType->title ?></p>
                        </div>
                        <?php if ($model->eventType->limited_seats == true) { ?>
                            <div class="d-flex align-items-center">
                                <div class="mr-1">
                                    <svg class="icon icon-lg px-2">
                                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_event_seat"></use>
                                    </svg>
                                </div>
                                <p class="lead mb-0"><?= AmosEvents::t('amosevents', 'Numero di posti') . ': ' ?><?= !empty($model->seats_available) ? $model->seats_available : '0' ?></p>
                            </div>
                        <?php } ?>


                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-5 mt-3 mt-md-0 d-flex flex-column">
                <div>

                    <?php if ($model->status != \open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) { ?>
                        <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_check_circle></use>" ?>
                        <?= Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center mr-2']) . Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Attiva evento</span>'), ['/events/event-dashboard/publish', 'id' => $model->id], ['class' => 'btn btn-primary mb-2 w-100 btn-xs btn-icon justify-content-center']); ?>
                        <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_web></use>" ?>
                        <?php echo Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center mr-2']) . Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Preview</span>'), \open20\amos\events\utility\EventsUtility::getUrlLandingPreview($model), ['class' => 'btn btn-secondary mb-2 w-100 btn-xs btn-icon justify-content-center']); ?>

                    <?php } else { ?>
                        <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_close></use>" ?>
                        <?= Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center mr-2']) . Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Disattiva evento</span>'), ['/events/event-dashboard/un-publish', 'id' => $model->id], ['class' => 'btn btn-secondary mb-2 w-100 btn-xs btn-icon justify-content-center', 'data-confirm' => AmosEvents::t('amosevents', "Sei sicuro di disattivare l'evento?")]); ?>
                    <?php } ?>


                    <?php if (\open20\amos\events\utility\EventsUtility::isEventCommunityEnabled($model)) { ?>
                        <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_account-supervisor-circle></use>" ?>
                        <?= Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center  mr-2']) . Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Accedi alla community</span>'), ['/events/event-dashboard/join-community', 'id' => $model->id], ['class' => 'btn btn-secondary mb-2 w-100 btn-xs btn-icon justify-content-center']); ?>
                    <?php } ?>

                    <?php if ($model->status == \open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED) { ?>
                        <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_web></use>" ?>
                        <?= Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center  mr-2']) . Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Vai alla landing</span>'), \open20\amos\events\utility\EventsUtility::getUrlLanding($model), ['target' => 'blank', 'class' => 'btn btn-secondary mb-2 w-100 btn-xs btn-icon justify-content-center']); ?>
                    <?php } ?>

                    <?php if (!$isEventInformative) { ?>
                        <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_cloud_download></use>" ?>
                        <?= Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center mr-2']) . Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Scarica partecipanti</span>'), '/events/event-dashboard/export-participants?id=' . $model->id, ['class' => 'btn btn-secondary mb-2 w-100 btn-xs btn-icon justify-content-center']); ?>
                    <?php } ?>
                    <!-- alternativa al bottone -->
                    <!-- < ?php $icon = "<use xlink:href=" .  $spriteAsset->baseUrl . "/material-sprite.svg#ic_cloud_download></use>" ?>
                    < ?php $svgIcon = Html::tag('svg', $icon, ['class' => 'icon icon-xs icon-white']) ?>
                    < ?php $spanSvgIcon = Html::tag('span', $svgIcon, ['class' => 'd-flex align-items-center mr-2']) . AmosEvents::t('amosevents', '<span>Scarica partecipanti</span>') ?>
                    < ?= Html::a($spanSvgIcon, '', ['class' => 'btn btn-secondary mb-2 w-100 btn-xs btn-icon justify-content-center']); ?> -->
                    <!-- end -->
                </div>

                <div class="mt-auto">

                    <?php $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_delete_forever></use>" ?>
                    <?= Html::a(Html::beginTag('span', ['class' => 'd-flex align-items-center mr-2']) . Html::tag('svg', $icon, [
                            'class' => 'icon icon-xs icon-danger'
                        ])
                        . Html::endTag('span') . AmosEvents::t('amosevents', '<span>Elimina evento</span>'), ['/events/event-dashboard/delete-event', 'id' => $model->id], [
                        'data-confirm' => AmosEvents::t('amosevents', 'Sei sicuro di eliminare l\'evento?'),
                        'class' => 'btn btn-outline-danger mb-2 w-100 btn-xs btn-icon justify-content-center'

                    ]); ?>


                </div>
            </div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-lg-6 py-2">
            <?php
            $publishFromDate = null;
            if ($model->publication_date_begin) {
                $publishFromDate = new \DateTime($model->publication_date_begin);
            }
            ?>
            <div class="d-flex">
                <div class="mr-1">
                    <svg class="icon">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_web"></use>
                    </svg>
                </div>
                <p class="mb-0"><?= AmosEvents::t('amosevents', '<strong>DATA PUBBLICAZIONE LANDING</strong>') . ': ' . ($publishFromDate ? $publishFromDate->format('d/m/Y') : '') ?></p>
            </div>

        </div>
        <div class="col-lg-6 py-2">
            <?php
            $registerFromDate = null;
            if ($model->registration_date_begin) {
                $registerFromDate = new \DateTime($model->registration_date_begin);
            }

            $registerToDate = null;
            if ($model->registration_date_begin) {
                $registerToDate = new \DateTime($model->registration_date_end);
            }
            ?>
            <div class="d-flex justify-content-lg-end">
                <div class="mr-1">
                    <svg class="icon">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_playlist_add"></use>
                    </svg>
                </div>
                <p class="mb-0"><?= AmosEvents::t('amosevents', '<strong>REGISTRAZIONI APERTE</strong>') . ': dal '
                    . ($registerFromDate ? $registerFromDate->format('d/m/y') : '')
                    . ' al ' . ($registerFromDate ? $registerFromDate->format('d/m/y') : 'd/m/y') ?></p>
            </div>

        </div>
    </div>
    <hr class="dashed-1 mb-4">

    <h5 class="text-uppercase font-weight-bold"><?= AmosEvents::t('amosevents', "Stato invii") ?></h5>
    <div class="row variable-gutters">
        <?php
        /** @var  $list \open20\amos\events\models\EventInternalList */
        foreach ($model->eventInternalLists as $list) {
            $status = $list->getSingleStatusSending($statuses);
            if ($status == "Invio completato") {
                $class = 'success';
            } else if ($status == "In coda" || strpos($status, "Invio in corso") >= 0) {
                $class = 'warning';
            } else {
                $class = 'secondary';
            } ?>
            <div class="col-md-6">
                <div class="notification card-inviti position-relative my-3 w-100 d-block with-icon <?= $class ?>">
                    <h5 id="not2a-title">
                        <svg class="icon icon-<?= $class ?>">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail_outline"></use>
                        </svg>
                        <?= AmosEvents::t('amosevents', 'Titolo ricerca: ') . $list->name ?></h5>
                    <p>
                        <strong class="text-<?= $class ?>"><?= AmosEvents::t('amosevents', 'STATO: ') ?></strong><?= $status ?>
                        <br>
                        <?php
                        $template = $list->getTemplate();
                        if ($template) { ?>
                            <strong><?= AmosEvents::t('amosevents', 'TEMPLATE: ') ?></strong><?= $template ?><br>
                        <?php } ?>
                        <strong><?= AmosEvents::t('amosevents', 'Email spedite: ') ?></strong><?= $list->getNSent($statuses) . ' su ' ?><?= $list->n_user_found ?>

                    </p>


                </div>
            </div>
        <?php } ?>
    </div>


<?php if (!$isEventInformative) { ?>
    <hr class="my-4">

    <h5 class="text-uppercase font-weight-bold mb-2"><?= AmosEvents::t('amosevents', "Report") ?></h5>
    <div class="row variable-gutters">
        <div class="col-md-8">
            <div class="row variable-gutters mb-5">
                <div class="col-md-6">
                    <div class="border-bottom h-100 py-3">
                        <?php $allInvitedUsers = $model->getAllInvitedUsers(true) ?>
                        <p>
                            <svg class="icon icon-sm mr-1">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_contact_mail"></use>
                            </svg>
                            <strong><?= AmosEvents::t('amosevents', "INVITATI") ?></strong>
                        </p>
                        <?= AmosEvents::t('amosevents', "Utenti") . ': ' . $allInvitedUsers ?>
                    </div>

                </div>

                <?php
                $ticketssent = $model->getTicketsSent(true);
                $totInvitationsInternal = $model->getEventInvitations()
                    ->innerJoin('event_invitation_sent', 'event_invitation_sent.user_id = event_invitation.user_id')
                    ->groupBy('user_id')
                    ->count();
                $totParticipants = $model->getTotConfirmedParticipants()

                ?>
                <?php if ($model->has_tickets) { ?>
                    <div class="col-md-6">
                        <div class="border-bottom h-100 py-3">
                            <p>
                                <svg class="icon icon-sm mr-1">
                                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail"></use>
                                </svg>
                                <strong><?= AmosEvents::t('amosevents', "BIGLIETTI") ?></strong>
                            </p>

                            <?= AmosEvents::t('amosevents', "Bliglietti inviati") . ': ' . $ticketssent . ' su ' . $totParticipants ?>
                            <br>
                            <?= AmosEvents::t('amosevents', "Bliglietti scaricati") . ': ' . $model->getTicketsDownloaded(true) . ' su ' . $totParticipants ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="border-bottom h-100 py-3">
                        <p class="primary-color font-weight-bold d-flex align-items-center">
                            <svg class="icon icon-primary icon-sm mr-1">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_check_circle"></use>
                            </svg>
                            <strong><?= AmosEvents::t('amosevents', "CONFERME") ?></strong>
                        </p>
                        <?= AmosEvents::t('amosevents', "Utenti") . ': ' . $totInvitationsInternal . ' su ' . $allInvitedUsers ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-bottom h-100 py-3">
                        <?php $notConfirmed = $allInvitedUsers - $totInvitationsInternal ?>
                        <p class="text-danger font-weight-bold d-flex align-items-center">
                            <svg class="icon icon-danger icon-sm mr-1">
                                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_remove_circle"></use>
                            </svg>
                            <strong><?= AmosEvents::t('amosevents', "ANCORA NON CONFERMATE") ?></strong>
                        </p>
                        <?= AmosEvents::t('amosevents', "Utenti") . ': ' . $notConfirmed . ' su ' . $allInvitedUsers ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php if (!$isEventInformative) { ?>
                <div class="border-bottom h-100 py-3">
                    <?= $this->render('_parts/_report_seats', ['model' => $model, 'totParticipants' => $totParticipants]) ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>