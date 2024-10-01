<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    retecomuni\frontend\views\site\parts
 * @category   CategoryName
 */
use open20\amos\events\AmosEvents;

?>

<div class="calendary">
    <?= \open20\amos\core\views\CalendarView::widget([
        'dataProvider' => $events,
        'itemView' => '_calendar',
        'clientOptions' => [
            'defaultView' => 'basicWeek',
            'locale' => 'IT',
            // 'buttonText' => [
            //     'month' => AmosEvents::t('amosevents', '#month_view')
            // ],
            // 'height' => 'auto',
            'navLinks' => true,
            'header' => [
                // 'left' => 'prev,next',
                // 'center' => 'title',
                // 'right' => 'month'
                'left' =>  'title',
                'right' =>  'prev, next',
                'center' => '',
            ],
            'dayNamesShort' => ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
             'eventBackgroundColor' => '#007db3',
        ],
        'eventConfig' => [
            'id' => 'id',
            'title' => 'eventTitle',
            'start' => 'begin_date_hour',
            'end' => 'end_date_hour',
            'color' => 'eventColor',
            // 'url' => 'eventUrl',
            'url' => 'agid_website'
        ],
        'array' => false,
        //se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
    ]) ?>
</div>



<?php 

$script = <<< JS
var heading=$(".fc-day-header a");
for(i=0;i<heading.length;i++){
     firstWords =(heading[i].innerHTML[0].concat(heading[i].innerHTML[1])).concat(heading[i].innerHTML[2]);
     secondWord =heading[i].innerHTML.substring(4,6);
   
    if(secondWord[1]=='/'){
        value=secondWord[0];
        secondWord='0'+value;
        
    }
    heading[i].innerHTML="<span class='day-name'>"+secondWord+"</span><br><span class='h5 day-number'>"+firstWords+"</span>";
    
}

JS;
$this->registerJs($script); 

?>
