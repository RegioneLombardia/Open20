<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/socialwall/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\socialwall\models\SocialwallTokensSearch $model
 */

$this->title = \open20\socialwall\Module::t('amoscore', 'Account Social');
$this->params['titleSection'] = $this->title;
$this->params['canCreate'] = true;
$this->params['createNewBtnParams'] = [
    'urlCreateNew' => '/socialwall/socialwall-tokens/create',
    'createNewBtnLabel' => \open20\socialwall\Module::t('amoscore', 'Aggiungi account'),
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socialwall-tokens-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                'type',
//                'data:striptags',
//                'secret',
                [
                    'attribute' => 'expires_at',
                    'value' => function ($model) {
                        if(!empty($model->expires_at)) {
                            return Yii::$app->formatter->asDate(
                                strtotime($model->expires_at),
                                isset(
                                    \Yii::$app->components['formatter']['datetimeFormat']) ?
                                    \Yii::$app->components['formatter']['datetimeFormat'] :
                                    'd-m-Y H:i:s');
                        } else {
                            return "(" . \open20\socialwall\Module::t('socialwall', 'Aggiunto manualmente') . ")";
                        }
                    },
                ],
//                [
//                    'attribute' => 'expires_at',
//                    'format' => [
//                        'datetime',
//                        (isset(\Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? \Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']
//                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
        /*'listView' => [
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

</div>
