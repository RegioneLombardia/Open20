<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/prodotti-e-servizi/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\prodottiservizi\utility\ProdottiServiziUtility;
use open20\prodottiservizi\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var frontend\models\ProdottiSoluzioniSearch $model
 */

$this->title = Yii::t('prodottiservizi', 'Gestisci Soluzioni');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/soluzioni']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Prodotti e Servizi', 'url' => ['/prodotti-e-servizi/prodotti-servizi']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="prodotti-soluzioni-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'icon' => [
                    'attribute' => 'icon',
                    'format' => 'html',
                    'label' => Yii::t('prodottiservizi', 'Icon'),
                    'value' => function($model){
                        return '<span class="mdi mdi-' . $model->icon . '"></span>';
                    }
                ],
                'name',
                'associazione servizi' => [
                    'label' => Yii::t('prodottiservizi', 'Prodotti e servizi associati'),
                    'value' => function($model){
                        return count($model->prodotti);
                    }
                ],
                'created_at' => [
                    'label' => Module::t('prodottiservizi', 'Created At'),
                    'value' => function($model) {
                        return Yii::$app->getFormatter()->asDate($model->created_at);
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $prodotti = $model->prodotti;
                            $action = '/prodotti-e-servizi/prodotti-soluzioni/delete';
                            $messages = [
                                '#errorModalDeleteSoluzioniSinglePS_1',
                                '#errorModalDeleteSoluzioniSinglePS_2'
                            ];
                            $options = ['title' => Yii::t('amoscore', 'Cancella')];
                            if(count($prodotti) > 0) {
                                $options = ProdottiServiziUtility::getModalDeleteSinglePS($model, $prodotti, $action, $messages, $options);
                            }
                            else {
                                $options = \yii\helpers\ArrayHelper::merge($options, ['data-confirm' => Module::t('prodottiservizi', '#solutionDeleteConfirm', [
                                    'name' => "<strong>{$model->name}</strong>"
                                ])]);
                            }
                            return Html::a('Cancella', [$action, 'id' => $model->id], $options);
                        }
                    ]
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
