<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiDomandeSearch $searchModel
 */

$this->title = AmosSondaggi::t('amossondaggi', 'Domande dei sondaggi');
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/index']];
if ($url) {
    $this->title = AmosSondaggi::t('amossondaggi', 'Domande del sondaggio');
    if (filter_input(INPUT_GET, 'idPagina')) {
        $this->title = AmosSondaggi::t('amossondaggi', 'Domande del sondaggio della pagina: ' . SondaggiDomandePagine::findOne(['id' => filter_input(INPUT_GET, 'idPagina')])->titolo);
    }
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Pagine del sondaggio'), 'url' => $url];

}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sondaggi-domande-pagine-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
          'modelClass' => 'Sondaggi Domande Pagine',
          ])        , ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>

    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                'domanda:ntext',
                'domanda_condizionata:statosino',
                [
                    'attribute' => 'sondaggi_domande_pagine_id',
                    'value' => function ($model) {
                        return $model->sondaggiDomandePagine->titolo;
                    },
                ],
                [
                    'attribute' => 'sondaggi_id',
                    'value' => function ($model) {
                        return $model->sondaggi->titolo;
                    }
                ],
                //'ordinamento',
//            ['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
//            'created_by',
//            'updated_by',
//            'deleted_by',
//            'version',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{update} {risposte} {aggrisposta} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_UPDATE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('edit'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/sondaggi-domande/update',
                                    'id' => $model->id,
                                    'url' => $url,
                                ]), [
                                    'title' => AmosSondaggi::t('amossondaggi', 'Modifica'),
                                    'class' => 'btn btn-tool-secondary'
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'risposte' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIRISPOSTEPREDEFINITE_READ', ['model' => $model])) {
                                if (in_array($model->getSondaggiDomandeTipologie()->one()['id'], [1, 2, 3, 4, 7, 8, 14])) {
                                    if ($model->getSondaggiRispostePredefinites()->count()) {
                                        if ($model->min_int_multipla > 0) {
                                            $numRisp = $model->min_int_multipla;
                                            if ($numRisp >=$model->getSondaggiRispostePredefinites()->count()) {
                                                return Html::a(AmosIcons::show('collection-plus'), Yii::$app->urlManager->createUrl([
                                                    '/' . $this->context->module->id . '/sondaggi-risposte-predefinite/index',
                                                    'idDomanda' => $model->id,
                                                    'url' => $url,
                                                ]), [
                                                    'title' => AmosSondaggi::t('amossondaggi', 'Gestisci risposte - Il numero di risposte presenti deve essere maggiore del numero d risposte minime'),
                                                    'class' => 'btn btn-tool-secondary btn-danger'
                                                ]);
                                            }
                                        }
                                        return Html::a(AmosIcons::show('collection-plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/sondaggi-risposte-predefinite/index',
                                            'idDomanda' => $model->id,
                                            'url' => $url,
                                        ]), [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Gestisci risposte'),
                                            'class' => 'btn btn-tool-secondary'
                                        ]);
                                    } else {
                                        return Html::a(AmosIcons::show('collection-plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/sondaggi-risposte-predefinite/index',
                                            'idDomanda' => $model->id,
                                            'url' => $url,
                                        ]), [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Gestisci risposte - E\' necessario aggiungere delle risposte per la tipologia di domanda scelta'),
                                            'class' => 'btn btn-tool-secondary btn-danger'
                                        ]);
                                    }
                                } else {
                                    return '';
                                }
                            } else {
                                return '';
                            }
                        },
                        'aggrisposta' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIRISPOSTEPREDEFINITE_CREATE', ['model' => $model])) {
                                if (in_array($model->getSondaggiDomandeTipologie()->one()['id'], [1, 2, 3, 4])) {
                                    if ($model->getSondaggiRispostePredefinites()->count()) {
                                        return Html::a(AmosIcons::show('plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/sondaggi-risposte-predefinite/create',
                                            'idDomanda' => $model->id,
                                            'url' => $url,
                                        ]), [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Aggiunti risposta'),
                                            'class' => 'btn btn-tool-secondary'
                                        ]);
                                    } else {
                                        return Html::a(AmosIcons::show('plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/sondaggi-risposte-predefinite/create',
                                            'idDomanda' => $model->id,
                                            'url' => $url,
                                        ]), [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Aggiungi risposta - E\' necessario aggiungere delle risposte per la tipologia di domanda scelta'),
                                            'class' => 'btn btn-tool-secondary btn-danger'
                                        ]);
                                    }
                                } else {
                                    return '';
                                }
                            } else {
                                return '';
                            }
                        },
                        'delete' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_DELETE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('delete'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/sondaggi-domande/delete',
                                    'id' => $model->id,
                                    'idSondaggio' => $model->sondaggi_id,
                                    'url' => $url,
                                ]), [
                                    'title' => AmosSondaggi::t('amossondaggi', 'Cancella'),
                                    'class' => 'btn btn-danger-inverse'
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
    ?>
    <p>
        <?php
//        if (isset($url)) :
//            echo Html::a(AmosSondaggi::t('amossondaggi', 'Aggiungi domanda'), ['create', 'idSondaggio' => filter_input(INPUT_GET, 'idSondaggio'), 'idPagina' => filter_input(INPUT_GET, 'idPagina'), 'url' => yii\helpers\Url::current()], ['class' => 'btn btn-success']);
//        endif;
        ?>
    </p>
</div>
