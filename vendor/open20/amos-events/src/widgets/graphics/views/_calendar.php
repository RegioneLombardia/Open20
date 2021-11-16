<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    retecomuni\frontend\views\site\parts
 * @category   CategoryName
 */

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
 */


$jsAjax = <<<JS

$('body').on('click', '.fc-basicDay-view .fc-content', function (e) {
	
    //e.preventDefault();
	
	var attrId = $(this).attr('id');
    var attrIdSplit = attrId.split('-');
	var id = attrIdSplit[attrIdSplit.length-1];
	
	location.href = window.location.origin + '/events/event/view?id=' + id;
	
    /*
    $.pjax({
	    type: "POST",
	    url: "events/event/event-calendar-widget#container-calendary",
	    data: { id:  id},
	    push: false,
	    container: '#event-calendar-pjax'
    });*/
 });

JS;
$this->registerJs($jsAjax, View::POS_READY);
?>

