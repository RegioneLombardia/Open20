<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\community\models\Community;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\community\models\search\BookmarksSearch $model
 */

$this->title = Yii::t('amoscore', 'tutti i bookmarks', [
    'modelClass' => 'Bookmarks',
]);
$setCommunityBreadcrumbs = true;
$cwhmodule = Yii::$app->getModule('cwh');
if($cwhmodule) {
    $scope = $cwhmodule->getCwhScope();
    if (!is_null($scope)) {
        $setCommunityBreadcrumbs = false;
    }
}
if($setCommunityBreadcrumbs) {
    $this->params['forceBreadcrumbs'][] = ['label' => 'Community', 'url' => ['/community/community/index']];
    $this->params['forceBreadcrumbs'][] = ['label' => Community::findOne(Yii::$app->request->get()['id'])->name, 'url' => ['/community/join/open-join', 'id' => Yii::$app->request->get()['id']]];
}
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bookmarks-index">
    <?= $this->render('_search', ['model' => $model]); ?>

    <?= $this->render('_order', ['model' => $model, 'currentView' => $currentView]); ?>

    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,

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
        ],/*
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
