<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\widgets\graphics\WidgetGraphicsEvents;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

EventsAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $listEvents
 * @var WidgetGraphicsEvents $widget
 * @var string $toRefreshSectionId
 */

$moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
$listaEventsModels = $listEvents->getModels();

?>
<div class="grid-item grid-item--width2 grid-item--height2">
    <div class="box-widget latest-news">
        <div class="box-widget-toolbar">
            <h1 class="box-widget-title col-xs-10 nop"><?= AmosEvents::t('amosevents', 'Ultimi Eventi') ?></h1>
            <?php
            if (isset($moduleEvents) && !$moduleEvents->hideWidgetGraphicsActions) {
                WidgetGraphicsActions::widget([
                    'widget' => $widget,
                    'tClassName' => AmosEvents::className(),
                    'actionRoute' => '/events/event/create',
                    'toRefreshSectionId' => $toRefreshSectionId
                ]);
            } ?>
        </div>
        <section class="clearfixplus">
            <h2 class="sr-only"><?= AmosEvents::t('amosevents', 'Ultimi Eventi') ?></h2>
            <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
            <?php if (count($listaEventsModels) == 0): ?>
                <?php
                $textReadAll = AmosEvents::t('amosevents', '#addEvent');
                $linkReadAll = '/events/event/create';
                $checkPermNew = true;
                ?>
            <?php else: ?>
                <?php
                $textReadAll = AmosEvents::t('amosevents', '#showAll');
                $linkReadAll = ['/events/event/all-events'];
                $checkPermNew = false;
                ?>
                <div class="list-items">
                    <?php
                    foreach ($listaEventsModels as $event):
                        ?>
                        <div class="col-xs-12 col-sm-4 col-md-4 widget-listbox-option" role="option">
                            <article class="col-xs-12 nop">
                                <div class="container-img">
                                    <?php
                                    $url = '/img/img_default.jpg';
                                    if (!is_null($event->eventLogo)) {
                                        $url = $event->eventLogo->getUrl('original', false, true);
                                    }
                                    ?>
                                    <?=
                                    Html::img($url, [
                                        'title' => $event->getAttributeLabel('eventLogo'),
                                        'class' => 'img-responsive img-round'
                                    ]);
                                    ?>
                                </div>
                                <div class="container-text clearfixplus">
                                    <div class="col-xs-9">
                                        <p class="media-heading">
                                            <?= AmosEvents::t('amosevents', 'Event'); ?>
                                        </p>
                                        <p class="media-heading">
                                            <?= $event->title ?>
                                        </p>
                                    </div>
                                    <div class="col-xs-12 nop m-t-15">
                                        <div class="col-sm-4 col-xs-12">
                                            <?= $event->getAttributeLabel('eventType') ?>
                                            <br/>
                                            <span class="bold"><?= !is_null($event->eventType) ? $event->eventType->title : '-' ?></span>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <?= AmosEvents::t('amosevents', 'Country') ?>
                                            <br/>
                                            <span class="bold"><?= ($event->countryLocation) ? $event->countryLocation->nome : '-' ?></span>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <?= AmosEvents::t('amosevents', 'City') ?>
                                            <br/>
                                            <span class="bold"><?= ($event->cityLocation) ? $event->cityLocation->nome : '-' ?>
                                                <?= ($event->provinceLocation) ? ' (' . $event->provinceLocation->sigla . ')' : '' ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-listbox col-xs-12 m-t-5 nop">
                                    <?= Html::a(AmosEvents::t('amosevents', 'LEGGI TUTTO'), ['/events/event/view', 'id' => $event->id], ['class' => 'btn btn-navigation-primary']); ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </section>
        <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
    </div>
</div>
