<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\widgets\graphics\WidgetGraphicsEvents;
use kv4nt\owlcarousel\OwlCarouselWidget;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;

EventsAsset::register($this);

/**
 * @var \yii\web\View $this
 * @var ActiveDataProvider $listEvents
 * @var \open20\amos\events\models\Event[] $eventsForCarousel
 * @var WidgetGraphicsEvents $widget
 * @var string $toRefreshSectionId
 * @var int $numEvents
 * @var string $orderEvents
 */

$moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
$thereAreEvents = ((count($eventsForCarousel) == 0) ? false : true);

?>
<div class="box-widget-header">
    <?php
    if (isset($moduleEvents) && !$moduleEvents->hideWidgetGraphicsActions) {
        WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosEvents::className(),
            'actionRoute' => '/events/event/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
    } ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('eventi', ['class' => 'am-2'], AmosIcons::IC) ?>
            <?= AmosEvents::tHtml('amosevents', 'Ultimi Eventi') ?>
        </h2>
    </div>

    <?php
    if ($thereAreEvents) {
        $textReadAll = AmosEvents::t('amosevents', '#showAll') . AmosIcons::show('chevron-right');;
        $linkReadAll = ['/events/event/all-events'];
        $checkPermNew = false;
    } else {
        $textReadAll = AmosEvents::t('amosevents', '#addEvent');
        $linkReadAll = '/events/event/create';
        $checkPermNew = true;
    }
    ?>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
</div>

<div class="box-widget latest-events">
    <section>
        <h2 class="sr-only"><?= AmosEvents::t('amosevents', 'Ultimi Eventi') ?></h2>
        <div class="wrap-event">
            <div class="<?= ($thereAreEvents ? 'col-sm-8' : '') ?> col-xs-12 nop" id="event-calendar-pjax">
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
                        <div>
                            <a href="<?= $event->getFullViewUrl() ?>" title="<?= AmosEvents::t('amosevents', '#widget_title_view_event') ?>">
                                <div class="wrap-img">
                                    <?=
                                    Html::img($event->getEventsImageUrl('original', true), [
                                        'alt' => $event->getAttributeLabel('eventLogo'),
                                        'class' => 'img-responsive'
                                    ]);
                                    ?>
                                </div>
                                <div class="abstract">
                                    <h2 class="box-widget-subtitle">
                                        <?= $event->title ?>
                                    </h2>
                                    <h3 class="box-widget-subtitle">
                                        <?= $event->summary ?>
                                    </h3>
                                    <p><?= \Yii::$app->getFormatter()->asDate($event->begin_date_hour) ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>

                    <?php OwlCarouselWidget::end(); ?>

                <?php endif; ?>
                <?php Pjax::end(); ?>
            </div>

            <?php if ($thereAreEvents): ?>
                <div id="container-calendary" class="col-sm-4 col-xs-12 nop container-calendary">
                    <?= $this->render("calendary", ['events' => $listEvents]); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
