<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\comuni\AmosComuni;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\comuni\models\search\IstatComuniSearch $model
 */

$this->title = AmosComuni::t('amoscomuni', 'Elenco comuni');
$this->params['breadcrumbs'][] = ['label' => 'Comuni', 'url' => ['/comuni/dashboard/index']];
$this->params['breadcrumbs'][] = AmosComuni::t('amoscomuni', 'Elenco comuni');
?>
<div class="istat-comuni-index">
    <?php // echo $this->render('_search', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a('Nuovo Istat Comuni'        , ['create'], ['class' => 'btn btn-amministration-primary'])*/ ?>
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
                'progressivo',
                'nome_tedesco',
                'cod_ripartizione_geografica',
//            'ripartizione_geografica', 
//            'comune_capoluogo_provincia', 
//            'cod_istat_alfanumerico', 
//            'codice_2006_2009', 
//            'codice_1995_2005', 
//            'codice_catastale', 
//            'popolazione_20111009', 
//            'codice_nuts1_2010', 
//            'codice_nuts2_2010', 
//            'codice_nuts3_2010', 
//            'codice_nuts1_2006', 
//            'codice_nuts2_2006', 
//            'codice_nuts3_2006', 
//            'soppresso', 
//            'istat_unione_dei_comuni_id', 
//            'istat_regioni_id', 
//            'istat_province_id', 
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
