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
 * @var frontend\models\ProdottiDestinatariSearch $model
 */

$this->title = Yii::t('prodottiservizi', 'Gestisci Destinatari');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/destinatari']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Prodotti e Servizi', 'url' => ['/prodotti-e-servizi/prodotti-servizi']];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
?>
<div class="prodotti-destinatari-index">
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
                'name' => [
                    'label' => Yii::t('prodottiservizi', 'Title'),
                    'value' => function($model){
                        return $model->name;
                    }
                ],
                'parent' => [
                    'label' => Yii::t('prodottiservizi', 'Scope'),
                    'value' => function($model){
                        return ProdottiServiziUtility::getAmbitoLabel($model->parent);
                    }
                ],
                'associazione servizi' => [
                    'label' => Yii::t('prodottiservizi', 'Prodotti e servizi associati'),
                    'value' => function($model){
                        return $model->getNProducts();
                    }
                ],
                'created_at' => [
                    'label' => Yii::t('prodottiservizi', 'Created At'),
                    'value' => function($model){
                        return Yii::$app->getFormatter()->asDate($model->created_at);
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $prodottiSingle = $model->getProdottiSingle();
                            $prodottiMultiple = $model->getProdottiMultiple();
                            $action = '/prodotti-e-servizi/prodotti-destinatari/delete';
                            $options = ['title' => Yii::t('amoscore', 'Cancella')];
                            if(count($prodottiSingle) > 0){
                                $messages = [
                                    '#errorModalDeleteDestinatariSinglePS_1',
                                    '#errorModalDeleteDestinatariSinglePS_2'
                                ];
                                $options = ProdottiServiziUtility::getModalDeleteSinglePS($model, $prodottiSingle, $action, $messages, $options);
                            }
                            elseif(count($prodottiMultiple) > 0){
                                $message = '#errorModalDeleteDestinatariMultiplePS';
                                $options = ProdottiServiziUtility::getModalDeleteMultiplePS($model, $prodottiMultiple, $action, $message, $options);
                            }
                            else {
                                $options = \yii\helpers\ArrayHelper::merge($options, ['data-confirm' => Module::t('prodottiservizi', '#destinatarioDeleteConfirm', [
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
