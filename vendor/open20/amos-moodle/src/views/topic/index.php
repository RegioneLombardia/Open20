<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\icons\AmosIcons;
use open20\amos\moodle\AmosMoodle;
use open20\amos\moodle\models\Topic;
use open20\amos\moodle\assets\MoodleAsset;

MoodleAsset::register($this);
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ArrayDataProvider $parametro
 */
$actionColumn = '{view}';


$this->title = AmosMoodle::t('amosmoodle', 'Argomenti');
$this->params['breadcrumbs'][] = $this->title;
$dataProvider = $this->params['dataProvider'];
?>

<div class="moodle-topic-index">

    <?php // echo $this->render('_search', ['model' => $model]);    ?>

 
        <p>
            <?php /* echo         Html::a(Yii::t('cruds', 'Nuovo {modelClass}', [
              'modelClass' => 'Moodle Topic',
              ])        , ['create'], ['class' => 'btn btn-amministration-primary']) */ ?>
        </p>
        <?php
        //pr($parametro);
        echo DataProviderView::widget([
            'dataProvider' => $dataProvider, // $parametro,
            //'filterModel' => $model,
            'currentView' => $currentView,
            'gridView' => [
                'columns' => [
                    // ['class' => 'yii\grid\SerialColumn'],
                    //'id',
                    'nome',
                    'avanzamento_attivita',
                    //'stato',
                    'stato' => [
                        'headerOptions' => [
                            'class' => 'text-center',
                        ],
                        'attribute' => 'stato',
                        'format' => 'html',
                        'value' => function($model) {
                            /** @var Topic $model */
                            $options = ['class' => 'state-wait'];
                            $options['title'] = 'Attività non completata';
                            if (!is_null($model->stato)) {
                                if ($model->stato == Topic::TOPIC_STATUS_COMPLETED) {
                                    $options['class'] = 'am-2 state-ok';
                                    $options['title'] = 'Attività completata';
                                }
                            }
                            return AmosIcons::show('check', $options);
                        }
                    ],
                    [
                        'class' => 'open20\amos\core\views\grid\ActionColumn',
                        'template' => $actionColumn,
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $createUrlParams = [
                                    '/moodle/lesson/index',
                                    'topicId' => $model['id'],
                                    'courseId' => $model['courseId'],
                                ];
                                $btn = Html::a('Entra', Yii::$app->urlManager->createUrl($createUrlParams), [
                                            'title' => Yii::t('amoscore', 'Entra nell\'attività'),
                                            'class' => 'btn btn-primary',
                                                ]
                                );
                                return $btn;
                            },
                        ]
                    ],
                ],
            ],
                /* 'listView' => [
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
                  ] */
        ]);
    
    ?>

</div>
