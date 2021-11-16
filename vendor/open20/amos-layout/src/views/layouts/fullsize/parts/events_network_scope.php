<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout\views\layouts\fullsize\parts
 * @category   CategoryName
 */

use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\MapWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventMembershipType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\layout\assets\BaseAsset;
use open20\amos\layout\Module;
use yii\helpers\Url;
use open20\amos\events\assets\EventsAsset;


/**
 * @var \open20\amos\events\models\Event $model
 */

$asset = BaseAsset::register($this);

EventsAsset::register($this);

/** @var \open20\amos\cwh\AmosCwh $cwhModule */
$cwhModule = Yii::$app->getModule('cwh');
$issetCwh = !is_null($cwhModule);

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();

/** @var EventInvitation $eventInvitationModel */
$eventInvitationModel = $eventsModule->createModel('EventInvitation');

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
    <div class="network-scope-wrapper scope-eventi-wrapper">
        <div class="container border-dashed">
            <div class="page-content">
                <div class="scope-title-container">
                    <div class="scope-title">
                        <h1><?= $model->title ?></h1>
                        <a href="/site/to-menu-url?url=/it/events/event/all-events?reset-scope=true" class="link-all align-items-center" title="Visualizza la lista degli eventi">
                            <p>Tutti gli eventi</p>
                            <span class="am am-arrow-right"></span>
                        </a>
                    </div>
                    <div class="actions-scope">
                        <div class="wrap-icons">
                            <?php
                            $url = !empty(\Yii::$app->params['platform']['backendUrl']) ? \Yii::$app->params['platform']['backendUrl'] : "";
                            echo \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                                'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_DROPDOWN,
                                'configuratorId' => 'socialShare',
                                'model' => $model,
                                'url' => \yii\helpers\Url::to($url . '/events/event/public?id=' . $model->id, true), //TODO VIEW
                                'title' => $model->title,
                                'imageUrl' => $url,
                            ]);
                            ?>

                            <?php
                            $reportModule = \Yii::$app->getModule('report');
                            if (isset($reportModule) && in_array($model->className(), $reportModule->modelsEnabled)) {
                                echo \open20\amos\report\widgets\ReportDropdownWidget::widget([
                                    'model' => $model,
                                ]);
                            }
                            ?>

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 date-container">
                        <div class="event-container d-flex flex-column light-theme">
                            <div class="d-flex flex-column-reverse flex-md-row h-100">
                                <div class="d-flex flex-row flex-md-column ">
                                    <div class="date pt-3 py-md-2 px-md-4 mr-0 mr-md-3 mb-1 d-flex flex-md-column justify-content-md-center align-items-md-center text-uppercase flex-md-grow-1 lightgrey-bg-c1">
                                        <p class="pr-2 pr-md-0 font-weight-bold mb-0 h2 d-none d-md-block"><?= date("d", strtotime($model->begin_date_hour)) ?></p>
                                        <p class="font-weight-bold pr-2 pr-md-0 mb-0 h4"><?= Yii::$app->formatter->asDate($model->begin_date_hour, 'MMM') ?></p>
                                        <p class="font-weight-normal mb-0 h4"><?= date("Y", strtotime($model->begin_date_hour)) ?></p>
                                    </div>
                                    <div class="hour d-none d-md-flex align-items-center justify-content-start mt-1 mr-3 py-4 px-5 bg-tertiary">
                                        <span class="am am-time"></span> <span class="mb-0 lead text-white"><?= ($model->begin_date_hour ? Yii::$app->getFormatter()->asTime($model->begin_date_hour) : '-') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--se esite l'immagine-->
                    <?php
                            $url = '/img/img_default.jpg';
                            if (!is_null($model->getEventLogo())) {
                            $url = $model->getEventLogo()->getUrl('original', false, true);         
                    ?>
                    <div class="col-md-3">
                        <div class="external-image-container h-100">
                            <div class="image-wrapper">
                                <?php

                                        $url = $model->getEventsImageUrl('square_large', true);
                                        $logo = Html::img($url, [
                                            'alt' => $model->getAttributeLabel('eventLogo'),
                                            'class' => 'community-image img-fluid w-100'
                                        ]);
                                ?>
                                <?= $logo ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 scope-info-wrapper">
                    <?php }else{ ?>
                    <div class="col-md-10 scope-info-wrapper">   
                    <?php } ?>        
                        <h2 class="mb-0 subtitle h3"><?= $model->summary ?></h2>
                        <p class="info-data bold">
                            <?php
                            $eventLocation = '-';
                            if ($model->event_location) {
                                if ($model->hasMethod('getShortEventLocation')) {
                                    $eventLocation = $model->getShortEventLocation();
                                } else {
                                    $eventLocation = $model->event_location;
                                }
                            }
                            ?>
                            <span><?= ($model->event_address) ? $model->event_address . ' - ' : '' ?></span>
                            <span><?= ($model->event_address_house_number) ? $model->event_address_house_number : ' ' ?></span>
                            <span><?= ($model->event_address_cap) ? $model->event_address_cap : '-' ?></span>
                            <span><?= ($model->cityLocation) ? $model->cityLocation->nome : '-' ?><?= ($model->provinceLocation) ? ' (' . $model->provinceLocation->sigla . ')' : '' ?></span>
                            <span><?= $eventLocation ?> - </span>
                            <span><?= ($model->countryLocation) ? $model->countryLocation->nome : '-' ?></span>
                        </p>
                    
                        <p><?= $model->description ?></p>

                    </div>

                    <?php
                    if (Yii::$app->getUser()->can('VALIDATED_BASIC_USER')):
                    ?>
                        <div class="col-xs-12 m-t-15">
                            <div class="event-button-container">
                                <?php
                                $eventCommunity = $model->community;
                                if ($eventCommunity->hasMethod('isBslRegistered') && $eventCommunity->isBslRegistered(Yii::$app->user->id)):
                                    ?>
                                    <a class="btn btn-xs btn-danger my-3 m-r-10 align-self-start" href="<?= Url::to(['/community/join/unsubscribe-by-notification', 'id' => $eventCommunity->id, 'urlToRet' => Yii::$app->request->url]) ?>" title="Elimina la tua iscrizione all'evento <?= $model->title ?>">
                                        <small><?= AmosEvents::t('amosevents', 'Togli dall\'agenda') ?></small>
                                    </a>
                                    <span class="text-success align-item-center flexbox bold m-t-5"><?= AmosIcons::show('calendar-check', ['class' => 'icon-xl m-r-5'], 'am') ?><?= AmosEvents::t('amosevents', 'Sei già iscritto') ?></span>
                                    <?php
                                else:
                                    ?>
                                    <a class="btn btn-primary my-3 m-r-10 align-self-start" href="<?= Url::to(['/community/join/subscribe-by-notification', 'id' => $eventCommunity->id, 'urlToRet' => Yii::$app->request->url]) ?>" title="Iscriviti all'evento <?= $model->title ?>">
                                        <p class="mb-0"><?= AmosEvents::t('amosevents', 'Aggiungi in agenda') ?></p>
                                    </a>
                                    <!-- <span class="text-success align-item-center flexbox bold m-t-5"><?= AmosIcons::show('calendar-check', ['class' => 'icon-xl m-r-5'], 'am') ?><?= AmosEvents::t('amosevents', 'Sei già iscritto') ?></span> -->
                                    <?php
                                endif;

                                ?>
                            </div>
                        </div>
                    <?php
                    else:
                    ?>
                        <div class="col-xs-12 m-t-15">
                            <p class="small"><?= AmosEvents::t('amosevents', 'Vuoi mettere questo evento in agenda?') ?></p>
                            <a class="btn btn-primary my-3 align-self-start" href="<?= Url::to(['/site/login']) ?>" title="Iscriviti all'evento <?= $model->title ?>">
                                <?= AmosEvents::t('amosevents', 'Accedi/registrati') ?>
                            </a>
                        </div>
                    <?php
                    endif;
                    ?>

                </div>
            </div>
        </div>

    </div>
<?php } ?>