<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-type
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\events\AmosEvents;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventTypeSearch $model
 * @var string $currentView
 */

$this->title = AmosEvents::t('amosevents', 'Event Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-type-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a('New Event Type'        , ['create'], ['class' => 'btn btn-amministration-primary'])*/ ?>
    </p>

    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'title',
                'description:html',
                'color' => [
                    'format' => 'raw',
                    'attribute' => 'color',
                    'value' => function ($model) {
                        return \yii\helpers\Html::tag('div', '&nbsp;', ['style' => "background-color:{$model->color}"]);
                    }
                ],
                // [
                //     'label' => AmosEvents::t('amosevents', 'Event context'),
                //     'value' => function ($model) {
                //         /** @var \open20\amos\events\models\EventType $model */
                //         return $model->eventTypeContext->title;
                //     }
                // ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ]
            ]
        ],
        /*'listView' => [
        'itemView' => '_item'
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
        'icon' => 'iconaMarker',
        ]
        ],
        'calendarView' => [
        'itemView' => '_calendar',
        'clientOptions' => [
        //'lang'=> 'de'
        ],
        'eventConfig' => [
        //'title' => 'titoloEvento',
        //'start' => 'data_inizio',
        //'end' => 'data_fine',
        //'color' => 'coloreEvento',
        //'url' => 'urlEvento'
        ],
        'array' => false,//se ci sono più eventi legati al singolo record
        //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
        ]*/
    ]); ?>
</div>
