<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;

?>
<p><strong>Risultati trovati: <?= $dataProvider->getTotalCount() ?></strong></p>
<?= DataProviderView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $model,
   'currentView' => $currentView,
    'iconView' => [
        'itemView' => '/prodotti-servizi/_icon'
    ],/*
   'gridView' => [
   'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

                    'id_product_root',
            'title',
            'description:striptags',
            'financial_description:striptags',
            'facilitation_features:striptags',
            'projects_features:striptags',
            'expenses_eligible:striptags',
            'status',
            'scope',
            'solution',
        [
        'class' => 'open20\amos\core\views\grid\ActionColumn',
        ],
        ],
        ],
        'listView' => [
        'itemView' => '_item',
        'masonry' => FALSE,

        // Se masonry settato a TRUE decommentare e settare i parametri seguenti
        // nel CSS settare i seguenti parametri necessari al funzionamento tipo
        // .grid-sizer, .grid-item {width: 50&;}
        // Per i dettagli recarsi sul sito http://masonry.desandro.com

        //'masonrySelector' => '.grid',
        //'masonryOptions' => [
        //    'itemSelector' => '.grid-item',
        //    'columnWidth' => '.grid-sizer',
        //    'percentPosition' => 'true',
        //    'gutter' => '20'
        //]
        ],
        'iconView' => [
        'itemView' => '_icon'
        ],
        'mapView' => [
        'itemView' => '_map',
        'markerConfig' => [
        'lat' => 'domicilio_lat',
        'lng' => 'domicilio_lon',
        'icon' => 'iconMarker',
        ]
        ],
        'calendarView' => [
        'itemView' => '_calendar',
        'clientOptions' => [
        //'lang'=> 'de'
        ],
        'eventConfig' => [
        //'title' => 'titleEvent',
        //'start' => 'data_inizio',
        //'end' => 'data_fine',
        //'color' => 'colorEvent',
        //'url' => 'urlEvent'
        ],
        'array' => false,//se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
        ]*/
]); ?>