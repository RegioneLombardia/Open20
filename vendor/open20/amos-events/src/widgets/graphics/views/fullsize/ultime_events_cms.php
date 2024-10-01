<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\widgets\graphics\WidgetGraphicsEvents;
use kv4nt\owlcarousel\OwlCarouselWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var \yii\web\View $this
 * @var ActiveDataProvider $listEvents
 * @var \open20\amos\events\models\Event[] $eventsForCarousel
 * @var WidgetGraphicsEvents $widget
 * @var string $toRefreshSectionId
 * @var int $numEvents
 * @var string $orderEvents
 */

EventsAsset::register($this);

$thereAreEvents = ((count($eventsForCarousel) == 0) ? false : true);

$modelLabel = AmosEvents::t('amosevents', '#events');

$urlLinkAll = '/events/event/all-events';
$labelLinkAll = AmosEvents::t('amosevents', '#all_events');
$titleLinkAll = AmosEvents::t('amosevents', '#widget_icon_all_events_description');

$labelCreate = AmosEvents::t('amosevents', 'Add new event');
$titleCreate = AmosEvents::t('amosevents', '#add_to_event');
$labelManage = AmosEvents::t('amosevents', 'Gestisci');
$titleManage = AmosEvents::t('amosevents', 'Gestisci gli eventi');
$urlCreate = '/events/event/create';

/* $jsButtonMese = <<<JS

$('document').ready(function () {
    $('.fc-month-button').attr("style", "display: none !important");
    $('.fc-month-button').on( "click", function() {
        $(this).attr("style", "display: none !important");
    }); 
    $('.fullcalendar .fc-day-number').each(function(){
        $(this).on( "click", function() {
            $('.fc-month-button').attr("style", "display: block !important");
         });
    });
});

JS;
$this->registerJs($jsButtonMese, View::POS_READY); */

$jsButtonCarousel = <<<JS

$('document').ready(function () {
    $(".owl-prev").html('<span class="mdi mdi-chevron-left"></span><span class="sr-only">Slider precedenti</span>');
    $(".owl-next").html('<span class="mdi mdi-chevron-right"></span><span class="sr-only">Slider successive</span>');
});

JS;
$this->registerJs($jsButtonCarousel, View::POS_READY);

?>

<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
        <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => $isSetScope,
                'modelLabel' => $modelLabel,
                'titleSection' => $widget->getLabel(),
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
                'canCreateCommunityWidget' => \Yii::$app->user->can('EVENT_CREATE'),
            ]
        );
        ?>
    </div>

    <div class="event-icon latest-events">
        <section>
            <h2 class="sr-only"><?= AmosEvents::t('amosevents', 'Ultimi Eventi') ?></h2>
            <div class="wrap-event">
                <div class="<?= ($thereAreEvents ? 'col-sm-6' : '') ?> col-xs-12 nop" id="event-calendar-pjax">
                    <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
                    
                    <?php if (!$thereAreEvents): ?>
                        <div class="list-items list-empty"><h3><?= AmosEvents::t('amosevents', '#no_events') ?></h3></div>
                    <?php else: ?>
                        <?php
                        $configuration = [
                            'containerOptions' => [
                                'id' => 'eventsOwlCarousel'
                            ],
                            'pluginOptions' => [
                                'autoplay' => true,
                                'items' => 1,
                                'loop' => true,
                                'nav' => true,
                                'dots' => false
                            ]
                        ];
                        OwlCarouselWidget::begin($configuration);
                        ?>
                        
                        <?php foreach ($eventsForCarousel as $event): ?>
                            <?php /** @var \open20\amos\events\models\Event $event */ ?>
                            <div class="event-container flex-column light-theme">
                                <div class="calendar-img-container m-b-15">
                                    <div class="d-flex flex-column-reverse flex-md-row">
                                        <div class=" box-calendar d-flex flex-row flex-md-column ">
                                            <div class="date pt-3 py-md-2 px-md-4 mr-0 mr-md-3 mb-1 d-flex flex-md-column justify-content-md-center align-items-md-center text-uppercase flex-md-grow-1 lightgrey-bg-c1">
                                                <p class="pr-2 pr-md-0 font-weight-bold mb-0 h2 d-none d-md-block"><?= date("d", strtotime($event->begin_date_hour)) ?></p>
                                                <!-- <p class="pr-2 pr-md-0 font-weight-bold mb-0 h4 d-block d-md-none ">21</p> -->
                                                <p class="font-weight-bold pr-2 pr-md-0 mb-0 h4"><?= Yii::$app->formatter->asDate($event->begin_date_hour, 'MMM') ?></p>
                                                <p class="font-weight-normal mb-0 h4"><?= date("Y", strtotime($event->begin_date_hour)) ?></p>
                                            </div>
                                            <div class="hour d-none d-md-flex align-items-center justify-content-start mt-1 mr-3 py-4 px-5 bg-tertiary">
                                                <span class="am am-time"></span> <span
                                                        class="mb-0 lead text-white"><?= ($event->begin_date_hour ? Yii::$app->getFormatter()->asTime($event->begin_date_hour) : '-') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="external-image-container">
                                        <div class="image-wrapper">
                                            <?php
                                            $url = '/img/img_default.jpg';
                                            if (!is_null($event->getEventLogo())) {
                                                $url = $event->getEventLogo()->getUrl('original', false, true);
                                            }
                                            ?>
                                            <?php
                                            
                                            $url = $event->getEventsImageUrl('square_large', true);
                                            $logo = Html::img($url, [
                                                'alt' => $event->getAttributeLabel('eventLogo'),
                                                'class' => 'community-image img-fluid w-100'
                                            ]);
                                            ?>
                                            <?= Html::a(
                                                $logo,
                                                $event->getFullViewUrl(),
                                                [
                                                    'class' => '',
                                                    'title' => AmosEvents::t('amosevents', '#widget_title_view_event')
                                                ]
                                            )
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?= Html::a(Html::tag('h3', $event->title, ['class' => 'font-weight-bold mt-md-4 mb-0']), $event->getFullViewUrl(), ['class' => 'link-list-title', 'title' => 'Vedi evento ' . $event->title]) ?>
                                <p class="mb-0 subtitle"><?= $event->summary ?></p>
                            </div>

                            <!--  <div>
                                 <a href="< ?= $event->getFullViewUrl() ?>" title="< ?= AmosEvents::t('amosevents', '#widget_title_view_event') ?>">
                                     <div class="wrap-img">
                                         < ?=
                                         Html::img($event->getEventsImageUrl('original', true), [
                                             'alt' => $event->getAttributeLabel('eventLogo'),
                                             'class' => 'img-responsive'
                                         ]);
                                         ?>
                                     </div>
                                     <div class="abstract">
                                         <h2 class="box-widget-subtitle">
                                             < ?= $event->title ?>
                                         </h2>
                                         <h3 class="box-widget-subtitle">
                                             < ?= $event->summary ?>
                                         </h3>
                                         <p>< ?= \Yii::$app->getFormatter()->asDate($event->begin_date_hour) ?></p>
                                     </div>
                                 </a>
                             </div> -->
                        <?php endforeach; ?>
                        
                        <?php OwlCarouselWidget::end(); ?>
                    
                    <?php endif; ?>
                    <?php Pjax::end(); ?>
                </div>
                
                <?php if ($thereAreEvents): ?>
                    <div id="container-calendary" class="col-sm-6 col-xs-12 nop container-calendary">
                        <?= $this->render("calendary", ['events' => $listEvents]); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
