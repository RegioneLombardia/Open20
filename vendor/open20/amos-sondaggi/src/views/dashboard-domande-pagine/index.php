<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $searchModel
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Gestione pagine');
 if ($url) {
     $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => '/' . $this->context->module->id . '/sondaggi/manage'];
 } else {
     $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => 'sondaggi/manage', 'route' => 'sondaggi/sondaggi/manage'];
     $this->params['breadcrumbs'][] = ['label' => $this->title];
 }

$this->params['titleButtons'][] = Html::a(AmosIcons::show('plus-circle').'&nbsp;'.AmosSondaggi::t('amossondaggi', '#new_page'),
Yii::$app->urlManager->createUrl([
    '/'.$this->context->module->id.'/dashboard-domande-pagine/create',
    'idSondaggio' => filter_input(INPUT_GET, 'idSondaggio'),
    'url' => $url,
]),
[
'title' => AmosSondaggi::t('amossondaggi', '#new_page'),
'class' => 'btn btn-primary'
]);

$modalMessage = AmosSondaggi::t('amossondaggi', '#cannot_order_conditioned_question');

$js = <<<JS

  function showCannotOrderModal() {
    $('#bk-page').prepend('<div class="container-messages container"><div id="w0" class="alert-danger alert fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{$modalMessage}</div></div>');
  }

  $('#dashboard-domande-pagine-view').on("click", ".order-down", function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
        url: '/sondaggi/dashboard-domande-pagine/order?id='+id+'&order=dopo',
        type: 'get',
        success: function (data) {
          if (data[0])
            $.pjax.reload({container: '#dashboard-domande-pagine-view', timeout: 5000});
          else showCannotOrderModal();
        }
    });
  });
  $('#dashboard-domande-pagine-view').on("click", ".order-up", function(e){
      e.preventDefault();
      var id = $(this).data('id');
      $.ajax({
          url: '/sondaggi/dashboard-domande-pagine/order?id='+id+'&order=prima',
          type: 'get',
          success: function (data) {
            if (data[0])
              $.pjax.reload({container: '#dashboard-domande-pagine-view', timeout: 5000});
            else showCannotOrderModal();
          }
      });
    });
JS;
$this->registerJs($js);

\open20\amos\layout\assets\SpinnerWaitAsset::register($this);

$this->registerJs(<<<JS

$(document).on('pjax:beforeSend', function() {
   $('.loading').removeAttr('hidden');
})

$(document).on('pjax:complete', function() {
    $('.loading').attr('hidden', 'hidden');
});

JS
);

?>

<div class="sondaggi-domande-pagine-dashboard">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
          'modelClass' => 'Sondaggi Domande Pagine',
          ])        , ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>
    <div class="loading" hidden></div>

    <?php
    Pjax::begin(['id'=>'dashboard-domande-pagine-view']);
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                'titolo',
//            ['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            'created_by',
//            'updated_by',
//            'deleted_by',
//            'version',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{update} {domande} {aggdomande} {updown} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDEPAGINE_UPDATE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('edit'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande-pagine/update',
                                    'id' => $model->id,
                                    'url' => $url,
                                ]), [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Modifica'),
                                        'class' => 'btn btn-tool-secondary'
                                    ]
                                );
                            } else {
                                return '';
                            }
                        },
                        'domande' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_READ', ['model' => $model])) {
                                return Html::a(AmosIcons::show('collection-text'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande/index',
                                    'idSondaggio' => $model->getSondaggi()->one()['id'],
                                    'idPagina' => $model->id,
//                                    'url' => $url,
                                ]), [
                                    'title' => AmosSondaggi::t('amossondaggi', 'Gestisci domande'),
                                    'class' => 'btn btn-tool-secondary'
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'aggdomande' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_CREATE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('plus'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande/create',
                                    'idSondaggio' => $model->getSondaggi()->one()['id'],
                                    'idPagina' => $model->id,
                                    'url' => $url,
                                ]), [
                                    'title' => AmosSondaggi::t('amossondaggi', 'Aggiungi domanda'),
                                    'class' => 'btn btn-tool-secondary'
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'updown' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_CREATE', ['model' => $model])) {
                                $data = [
                                    'id' => $model->id
                                ];
                                $return = '';
                                \Yii::debug(SondaggiDomandePagine::find()->andWhere(['sondaggi_id' => $model->sondaggi_id])->min('ordinamento'), 'sondaggi');
                                \Yii::debug(SondaggiDomandePagine::find()->andWhere(['sondaggi_id' => $model->sondaggi_id])->max('ordinamento'), 'sondaggi');
                                if ($model->ordinamento > SondaggiDomandePagine::find()->andWhere(['sondaggi_id' => $model->sondaggi_id])->min('ordinamento')) {
                                    $return .= Html::a(AmosIcons::show('caret-up'), '#', [
                                    'title' => AmosSondaggi::t('amossondaggi', '#order_up'),
                                    'class' => 'btn btn-tool-secondary order-up',
                                    'data' => $data
                                    ]);
                                }
                                if ($model->ordinamento < SondaggiDomandePagine::find()->andWhere(['sondaggi_id' => $model->sondaggi_id])->max('ordinamento')) {
                                    $return .= Html::a(AmosIcons::show('caret-down'), '#', [
                                        'title' => AmosSondaggi::t('amossondaggi', '#order_down'),
                                        'class' => 'btn btn-tool-secondary order-down',
                                        'data' => $data
                                    ]);
                                }
                                return $return;
                            } else {
                                return '';
                            }
                        },
                        'delete' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandePagineSearch $model */
                            $url = \yii\helpers\Url::current();
                            $confDeleteMessage = 'Stai per cancellare l\'intera pagina compreso le domande / risposte contenute al suo interno';
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDEPAGINE_DELETE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('delete'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande-pagine/delete',
                                    'id' => $model->id,
                                    'idSondaggio' => $model->sondaggi_id,
                                    'url' => $url,
                                ]), [
                                    'title' => AmosSondaggi::t('amossondaggi', 'Cancella'),
                                    'class' => 'btn btn-danger-inverse',
                                    'data-confirm' => $confDeleteMessage
                                ]);
                            } else {
                                return '';
                            }
                        },
                    ]
                ],
            ],
        ],
        /* 'listView' => [
          'itemView' => '_item'
          ],
          'iconView' => [
          'itemView' => '_icon'
          ],
          'mapView' => [
          'itemView' => '_map',
          'markerConfig' => [
          'lat' => 'domicilio_lat',
          'lng' => 'domicilio_lon',
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
          ] */
    ]);
    Pjax::end();
    ?>
    <?php
//    if (isset($url)) :
//        echo Html::a(AmosSondaggi::t('amossondaggi', 'Aggiungi pagina'), ['create', 'idSondaggio' => filter_input(INPUT_GET, 'idSondaggio'), 'idPagina' => filter_input(INPUT_GET, 'idPagina'), 'url' => yii\helpers\Url::current()], ['class' => 'btn btn-success']);
//    endif;
    ?>

</div>
