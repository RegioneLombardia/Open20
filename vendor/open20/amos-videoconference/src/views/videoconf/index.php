<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\utilities\ViewUtility;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\videoconference\AmosVideoconference;

use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\VideoconfSearch $model
 */

// Attributo dinamico da mostrare in base alla action in uso
$actionColumn = '{view}{update}{participants}{close}{delete}';
if(\Yii::$app->controller->action->id == 'past-videoconferences') {
    $actionColumn = '{view}{update}{delete}';
}
if(\Yii::$app->controller->action->id == 'index') {
    $dynamicColumn = [
        'attribute' => 'Partecipa',
        'format' => 'html',
        'contentOptions' => ['class' => 'kv-disable-click'],
        'value' => function ($model) {
            /** @var \open20\amos\videoconference\models\Videoconf $model */
            if ($model->status == $model::STATUS_END) {
                return '';
            } else {
                $subscribed = \open20\amos\videoconference\utils\VideoconfUtil::isCurrentUserSubscribed($model->id);
                $action = '/videoconference/videoconf/register-logged-user?idVideoconf=' . $model->id;
                $disabledClass = '';
                $message = AmosVideoconference::t('amosvideoconference', '#JITSI_PARTECIPATE');
                if ($model->status == $model::STATUS_RUNNING) {
                    if (!$subscribed) {
                        $titolo = AmosVideoconference::t('amosvideoconference', 'Registrati ed entra nella videoconferenza');
                    } else {
                        $titolo = AmosVideoconference::t('amosvideoconference', 'Entra nella videoconferenza');
                    }
                } elseif ($model->status == $model::STATUS_FUTURE) {
                    if (!$subscribed) {
                        $titolo = AmosVideoconference::t('amosvideoconference', 'Registrati alla videoconferenza');
                        $message = AmosVideoconference::t('amosvideoconference', '#JITSI_REGISTER');
                    } else {
                        $titolo = AmosVideoconference::t('amosvideoconference', 'Iscrizione già effettuata');
                        $disabledClass = ' disabled';
                    }
                }
                $options = [
                    'title' => $titolo,
                    'class' => 'btn btn-primary' . $disabledClass,
                ];
                return Html::a($message, $action, $options);
            }
        }
    ];
} else {
    $dynamicColumn = [
        'attribute' => 'end_date_hour',
        'format' => 'html',
        'contentOptions' => ['class' => 'kv-disable-click'],
        'value' => function ($model) {
            if ($model->end_date_hour) {
                return str_replace("-", "/", Yii::$app->formatter->asDatetime($model->end_date_hour, ViewUtility::formatDateTime()));
                //return Yii::$app->formatter->asDatetime($model->end_date_hour, ViewUtility::formatDateTime());
            }
            return '';
        }
    ];
}

$this->title = AmosVideoconference::t('amosvideoconference', 'Videoconferenza');
$this->params['breadcrumbs'][] = $this->title;

$js = <<<JS
    $(document).ready(function(){
        $("span.mdi.mdi-dots-vertical").addClass('kv-disable-click');
    });
JS;

$this->registerJs($js);

?>
<div class="videoconf-index">

    <?= $this->render('_search', ['model' => $model]);  ?>

    <?= $this->render('_order', ['model' => $model, 'currentView' => $currentView]); ?>
    <?php
    //pr(Yii::$app->formatter);
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'expandAllTitle' => 'Sotto progetti',
                    'allowBatchToggle' => false,
                    'enableRowClick' => true,
                    'header' => '',
                    'headerOptions' => [
                        'style' => 'white-space: nowrap;'
                    ],
                    'collapseIcon' => AmosIcons::show('chevron-down'),
                    'expandIcon' => AmosIcons::show('chevron-right'),
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function ($model, $key, $index, $column) {
                        return \kartik\grid\GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => \yii\helpers\Url::to(['/videoconference/videoconf-ajax/more-info'])
                ],
                'title',
                [
                    'label' => AmosVideoconference::t('amosvideoconference', 'Partecipanti'),
                    'contentOptions' => ['class' => 'kv-disable-click'],
                    'value' => function ($model){
                        return count($model->videoconfUsersParticipants);
                    },
                ],
                [
                    'label' => AmosVideoconference::t('amosvideoconference', 'Invitati'),
                    'contentOptions' => ['class' => 'kv-disable-click'],
                    'value' => function ($model){
                        return count($model->videoconfUsersMms);
                    },
                ],
				[
                    'label' => AmosVideoconference::t('amosvideoconference', 'Notifica'),
                    'contentOptions' => ['class' => 'kv-disable-click'],
					'format' => 'html',
                    'value' => function ($model){
                        return $model->notification_before_conference."'";
                    },
                ],
                [
                    'attribute' => 'begin_date_hour',
                    'format' => 'html',
                    'contentOptions' => ['class' => 'kv-disable-click'],
                    'value' => function ($model) {
                        if ($model->begin_date_hour) {
                            return str_replace("-", "/", Yii::$app->formatter->asDatetime($model->begin_date_hour, ViewUtility::formatDateTime()));
                        }
                        return '';
                    }
                ],
                $dynamicColumn,
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'contentOptions' => ['class' => 'kv-disable-click'],
                    'buttons' => [
                        'participants' => function ($url, $model) {
                            if ((\Yii::$app->user->can('VIDEOCONF_UPDATE',['model'=> $model]))) {
                                return Html::a(AmosIcons::show('accounts-add'), ['/videoconference/videoconf/participants', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-tools-secondary kv-disable-click',
                                        'title' => AmosVideoconference::t('amosvideoconference',"Partecipanti"),
                                        'data-pjax' => '0'
                                    ]);
                            }
                        },
                        'close' => function($url, $model) {
                            if ((\Yii::$app->user->can('VIDEOCONF_UPDATE',['model'=> $model]))) {
                                return Html::a(AmosIcons::show(), ['close-videoconference', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-tools-secondary kv-disable-click',
                                        'title' => AmosVideoconference::t('amosvideoconference',"Chiudi Videoconferenza"),
                                        'data-pjax' => '0'
                                    ]);
                            }
                        }
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
