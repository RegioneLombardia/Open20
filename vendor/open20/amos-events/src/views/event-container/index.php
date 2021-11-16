<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\AmosEvents;
use open20\amos\layout\utility\AmosIconsBi;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventContainerSearch $model
 */

$this->title = Yii::t('amosevents', 'Eventi padre');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/events']];
$this->params['breadcrumbs'][] = $this->title;
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);


?>
<div class="event-container-index">
    <?php $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [


                'title',
                [
                    'label' => 'N. eventi',
                    'value' => function ($model) {
                        return $model->getEvents()->count();
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function ($url, $model) use ($spriteAsset) {
                            return Html::a(AmosIconsBi::show('ic_edit'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Modifica'], true);

                        },
                        'delete' => function ($url, $model) {
                            return Html::a(AmosIconsBi::show('ic_close', 'danger'), $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Elimina',
                                'data-confirm' => 'Sei sicuro di eliminare l\'evento padre?'], true);
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
