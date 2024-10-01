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
 * @var frontend\models\ProdottiServiziSearch $model
 */

$this->title = Yii::t('prodottiservizi', 'Prodotti e Servizi');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/prodotti-servizi']];

$this->params['forceBreadcrumbs'][] = ['label' => 'Prodotti e Servizi', 'url' => ['/prodotti-e-servizi/prodotti-servizi']];
$this->params['forceBreadcrumbs'][] = ['label' => Module::t('prodottiservizi', 'Amministra')];


?>
<div class="prodotti-servizi-index">
    <?= $this->render('_search', [
        'model' => $model, 'originAction' => Yii::$app->controller->action->id,
        'soluzioni' => $soluzioni,
        'finalita' => $finalita,
        'destinatari' => $destinatari
        ]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'icon_solution' => [
                    'label' => Module::t('prodottiservizi', 'Icon'),
                    'format' => 'html',
                    'value' => function($model) {
                        return '<span class="mdi mdi-' . $model->soluzione->icon . '"></span>';
                    }
                ],
                'solution' => [
                    'label' => Module::t('prodottiservizi', 'Soluzione'),
                    'value' => function($model) {
                        return $model->soluzione->name;
                    }
                ],
                'title',
                'status' => [
                    'label' => Module::t('prodottiservizi', 'Status'),
                    'format' => 'html',
                    'value' => function($model) {
                        return '<div class="status ' . ProdottiServiziUtility::getStatiClassLabel($model->status) . '">' . ProdottiServiziUtility::getStatoLabel($model->status) . '</div>';
                    }
                ],
                'created_at' => [
                    'label' => Module::t('prodottiservizi', 'Created at'),
                    'value' => function($model) {
                        return Yii::$app->getFormatter()->asDate($model->created_at);
                    }
                ],
                'scope' => [
                    'label' => Module::t('prodottiservizi', 'Scope'),
                    'value' => function($model) {
                        return ProdottiServiziUtility::getAmbitoLabel($model->scope);
                    }
                ],
                'destinatari' => [
                    'label' => Module::t('prodottiservizi', 'Destinatari'),
                    'format' => 'html',
                    'value' => function($model) {
                        $destinatariMm = $model->destinatariMm;
                        $destinatari = [];
                        foreach($destinatariMm as $mm):
                            $destinatari[] = $mm->destinatario->name;
                        endforeach;
                        $destinatari = \yii\helpers\ArrayHelper::map($model->destinatariMm, 'id', function($model){return $model->destinatario->name;});
                        sort($destinatari);
                        return Html::ul($destinatari);
                    }
                ],
                'updated_at' => [
                    'label' => Module::t('prodottiservizi', 'Updated At'),
                    'value' => function($model) {
                        return Yii::$app->getFormatter()->asDate($model->updated_at);
                    }
                ],
                'sportelli' => [
                    'label' => Module::t('prodottiservizi', 'Sportelli precedenti'),
                    'value' => function($model) {
                        return count($model->sportelli);
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn'
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