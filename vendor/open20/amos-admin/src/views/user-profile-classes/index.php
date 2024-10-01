<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-admin/src/views 
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\amos\admin\AmosAdmin;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\admin\models\search\UserProfileClassesSearch $model
 */
$this->title                   = AmosAdmin::t('amoscore', 'Profili');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-classes-index">
    <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]);
    ?>

    <?=
    DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'description:striptags',
                //'code',
                'enabled:statosino',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{clone}{update}{delete}',
                    'buttons' => [
                        'clone' => function ($url, $model) { 
                            return \yii\helpers\Html::a(
                                AmosAdmin::t('amosadmin', 'Copia'),
                                [
                                    '/'.AmosAdmin::getModuleName().'/user-profile-classes/clone',
                                    'id' => $model->id,
                                ],
                                [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => AmosAdmin::t('amosadmin', 'Copia'),
                                ]
                            );
                        }
                    ],
                ],
            ],
        ],
        /* 'listView' => [
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
          ] */
    ]);
    ?>

</div>
