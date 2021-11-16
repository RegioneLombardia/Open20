<?php

use amos\sitemanagement\Module;
use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var amos\sitemanagement\models\search\SiteManageContElemPubblicationSearch $model
 */

$this->title = Module::t('amossitemanagement', 'Site Manage Cont Elem Pubblication');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-manage-cont-elem-pubblication-index">
    <?php // echo $this->render('_search', ['model' => $model]); ?>

    <p>
        <?php /* echo         Html::a(Module::t('amossitemanagement', 'Nuovo {modelClass}', [
    'modelClass' => 'Site Manage Cont Elem Pubblication',
])        , ['create'], ['class' => 'btn btn-amministration-primary'])*/ ?>
    </p>

    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'container_id',
                'container_elem_id',
                'pubblication_type_id',
                ['attribute' => 'start_date', 'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'end_date','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'start_time','format'=>['time',(isset(Yii::$app->modules['datecontrol']['displaySettings']['time'])) ? Yii::$app->modules['datecontrol']['displaySettings']['time'] : 'H:i:s A']], 
//            ['attribute'=>'end_time','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            'created_by', 
//            'updated_by', 
//            'deleted_by', 
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
