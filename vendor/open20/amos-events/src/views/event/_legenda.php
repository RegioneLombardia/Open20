<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\icons\AmosIcons;
use open20\amos\events\AmosEvents;
use open20\amos\events\assets\EventsAsset;
use open20\amos\events\models\search\EventTypeSearch;

/**
 * @var yii\web\View $this
 * @var mixed $model_type
 * @var open20\amos\events\models\search\EventSearch $model
 * @var open20\amos\events\AmosEvents $eventsModule
 * @var string $originAction
 */

EventsAsset::register($this);

$eventTypes = EventTypeSearch::listEnabledGenericContextEventTypesAndIcon();
$arrayCssClass = EventTypeSearch::ARRAYCSSCLASS;

?>
<?php if ($eventsModule->showEventLegend): ?>
    <div class="event-legenda callout callout-info m-b-30">
        <div class="callout-title">
            <span><?= AmosEvents::t('amosevents', 'Legenda') ?></span>
        </div>
        <div class="flexbox flexbox-row flexbox-wrap">
            <?php foreach ($eventTypes as $eventType): ?>
                <div class="event-status <?= $arrayCssClass[$eventType['type']]; ?>">
                    <p><?= AmosIcons::show($eventType['icon'], ['style' => "background-color:" . $eventType['color'] . "; color:" . EventTypeSearch::colorText($eventType['color'])]) . ' ' . AmosEvents::t('amosevents', $eventType['title']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
