<?php

use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiRisposteSearch $searchModel
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Sondaggi Rispostes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sondaggi-risposte-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
    'modelClass' => 'Sondaggi Risposte',
])        , ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>

    <?php echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'risposta:ntext',
                'sondaggi_domande_id',
                'pei_accessi_servizi_facilitazione_id',
                'sondaggi_risposte_sessioni_id',
//            ['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            'created_by', 
//            'updated_by', 
//            'deleted_by', 
//            'version', 
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
        /*'listView' => [
            'itemView' => '_item'
        ],
        'iconView' => [
            'itemView' => '_icon'
        ],
        'mapView' => [
            'itemView' => '_map',
            'markerConfig' => [
                'lat' => 'domicilio_lat',
                'lng' => 'domicilio_lon',
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
        ]*/
    ]); ?>
</div>
