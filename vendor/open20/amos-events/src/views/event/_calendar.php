<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

use yii\bootstrap\Modal;
use yii\web\View;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 * tutto quello che si inserirà qui comparirà dopo il calendario per inserire
 * del codice HTML prima del calendario usare il campo intestazione della
 * configurazione della vista nella pagina index.php
 */
/**
 * @var \open20\amos\events\models\Event $model
 * @var $this View
 */

// Modal render for each event (previous code)
/*
$jsAjax = <<<JS

$('body').on('click', '.fc-content', function (e) {
    $.ajax({
	    type: "POST",
	    url: "get-event-by-id",
	    data: { id: $(this).attr('id') },
	    cache: false,
	    success: function(response){
            $('#event-modal-body').html(response);
            $('#event-modal').modal('show');
    	}
    });
});

JS;

$this->registerJs($jsAjax, View::POS_READY);

// END PHP TAG
?
>
<div class="event-modal">
    <?php
    Modal::begin([
        'options' => [
            'id' => 'event-modal',
            'tabindex' => false,
        ],
        'size' => Modal::SIZE_LARGE,
        'header' => '<span class="event-modal-title"></span>',
    ]);
    ?>
    <div class="event-modal-body" id="event-modal-body"></div>
    <?php
    Modal::end();
    ?>
</div>

*/

// Open the event page directly without modals
$this->registerJs(<<<JS
    if(typeof globalCalendar == 'undefined'){
            globalCalendar = [];
    }
    globalCalendar[parseInt({$model->id}, 10)] = "{$model->getFullViewUrl()}";
    $('body').on('click', '.fc-content', function (e) {
        var elemId = $(this).attr('id').toString();
        var titleEl = $(this).data('title');
        if(elemId !== undefined && elemId !== "") {    
            eventId = parseInt(elemId.split("-")[2],10);
            window.open(globalCalendar[eventId], "_self");
        }
    });
JS
    );
