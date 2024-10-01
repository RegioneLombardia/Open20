<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\comuni\models\search\IstatProvinceSearch $model
 */

$this->title = 'Elenco province';
$this->params['breadcrumbs'][] = ['label' => 'Comuni', 'url' => ['/comuni/dashboard/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="istat-province-index">
    <?php // echo $this->render('_search', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a('Nuovo Istat Province'        , ['create'], ['class' => 'btn btn-amministration-primary'])*/ ?>
    </p>

    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'nome',
                'sigla',
                'capoluogo',
                'soppressa',
//            'istat_citta_metropolitane_id', 
//            'istat_regioni_id', 
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
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
