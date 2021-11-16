<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    retecomuni\frontend\views\site\parts
 * @category   CategoryName
 */

?>

<div class="calendary">
    <?= \open20\amos\core\views\CalendarView::widget([
        'dataProvider' => $events,
        'itemView' => '_calendar',
        'clientOptions' => [
            'locale' => 'IT',
            'buttonText' => [
                'month' => 'Mese'
            ],
            'height' => 'auto',
            'navLinks' => true,
            'header' => [
                'left' => 'prev,next',
                'center' => 'title',
                'right' => 'month'
            ],
            'dayNamesShort' => ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
            'eventBackgroundColor' => '#FC511D',
        ],
        'eventConfig' => [
            'id' => 'id',
            'title' => 'eventTitle',
            'start' => 'begin_date_hour',
            'end' => 'end_date_hour',
            'color' => 'eventColor',
            'url' => 'eventUrl',
        ],
        'array' => false,
        //se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
    ]) ?>
</div>
