<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var amos\podcast\models\search\PodcastSearch $model
 */

$this->title = Yii::t('amoscore', 'Podcast');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/podcast']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podcast-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?php $columns = [
        'title',
        'podcast_category_id' => [
            'attribute' => 'podcastCategory',
            'format' => 'html',
            'label' => \amos\podcast\Module::t('amospodcast','Category'),
            'value' => function ($model) {
                return $model->podcastCategory->name;
            }
        ],

    ];
    if(\Yii::$app->controller->action->id == 'admin' ||\Yii::$app->controller->action->id == 'to-validate' ){
        $columns []=   [
            'attribute' => 'workflowStatus.label',
            'label' => \amos\podcast\Module::t('amospodcast', 'Status')
        ];
    }
    if(\Yii::$app->controller->action->id == 'to-validate' || \Yii::$app->controller->action->id == 'admin'){
        $urlexpand =  ['/podcast/podcast/to-validate-episodes'];
        if(\Yii::$app->controller->action->id == 'admin'){
           $urlexpand =  ['/podcast/podcast/to-validate-episodes','showall' => true];
        }

        $columns []=   [
            'value' => function($model){
               return  $model->getPodcastEpisodes()->andWhere(['status' => \amos\podcast\models\PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_REQUESTPUBLICATION])->count();
            },
            'label' => \amos\podcast\Module::t('amospodcast', 'Episodes to validate')
        ];
        $columns []= [
                'class' => 'kartik\grid\ExpandRowColumn',
                'expandAllTitle' => 'Tasks',
                'allowBatchToggle' => false,
                'header' => \amos\podcast\Module::t('amospodcast', 'Show/Hide'),
                'headerOptions' => [
                    'style' => 'white-space: nowrap;'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'value' => function ($model, $key, $index, $column) {
                    return \kartik\grid\GridView::ROW_COLLAPSED;
                },
                'detailUrl' => \yii\helpers\Url::to($urlexpand)
        ];
    }
    $columns []=      [
        'class' => 'open20\amos\core\views\grid\ActionColumn',
    ];
    ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
  
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
        'currentView' => $currentView,
        'gridView' => [
            'columns' =>$columns
        ],
        /*'iconView' => [
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

</div>
