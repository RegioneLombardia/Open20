<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiDomandePagine;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use open20\amos\core\views\AmosGridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiDomandeSearch $searchModel
 */

$this->title = AmosSondaggi::t('amossondaggi', '#poll_questions');
if (!$this->context->module->enableDashboard) {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['/' . $this->context->module->id . '/sondaggi/manage']];
}
if ($url) {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Pagine'), 'url' => $url];
} else {
    $this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => 'sondaggi/manage', 'route' => 'sondaggi/sondaggi/manage'];
    $this->params['breadcrumbs'][] = ['label' => $this->title];
}
$this->params['titleButtons'][] = Html::a(AmosIcons::show('plus-circle').'&nbsp;'.AmosSondaggi::t('amossondaggi', '#new_page'),
Yii::$app->urlManager->createUrl([
    '/'.$this->context->module->id.'/dashboard-domande/create',
    'idSondaggio' => filter_input(INPUT_GET, 'idSondaggio'),
    'idPagina' => filter_input(INPUT_GET, 'idPagina'),
    'url' => \yii\helpers\Url::current(),
]),
[
'title' => AmosSondaggi::t('amossondaggi', '#new_page'),
'class' => 'btn btn-primary'
]);
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
                [
                    'attribute' => 'sondaggi_domande_tipologie_id',
                    'value' => function ($model) {
                        return $model->sondaggiDomandeTipologie->tipologia;
                    },
                ],
                [
                    'attribute' => 'sondaggi_domande_pagine_id',
                    'value' => function ($model) {
                        return $model->sondaggiDomandePagine->titolo;
                    },
                ],
                'pageOrder' => [
                    'label' => AmosSondaggi::t('amossondaggi', '#page_number'),
                    'value' => function ($model) {
                        $pagine = $model->sondaggi->getSondaggiDomandePagines()->orderBy('ordinamento')->all();
                        $pagine_id = array_map(function($element) {return $element->id;}, $pagine);
                        return array_search($model->sondaggiDomandePagine->id, $pagine_id) + 1;
                    },
                ],
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'width' => '0px',
                    'value' => function ($model, $key, $index) {
                        if ($model->is_parent && $model->getChildren()->count() > 0) {
                            return AmosGridView::ROW_EXPANDED;
                        }
                        return AmosGridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) use ($currentView) {
                        $provider = new ActiveDataProvider([
                            'query' => $model->getChildren()
                        ]);
                        return Yii::$app->controller->renderPartial('_sub_index', ['dataProvider' => $provider, 'currentView' => $currentView]);
                    },
                    'disabled' => function ($model, $key, $index, $column) {
                        if ($model->is_parent && $model->getChildren()->count() > 0) return false;
                        return true;
                    },
                    'expandOneOnly' => false,
                    'detailRowCssClass' => '',
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{update} {risposte} {aggdomanda} {aggrisposta} {clone} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_UPDATE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('edit'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande/update',
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
                                                    '/' . $this->context->module->id . '/dashboard-risposte-predefinite/index',
                                                    'idDomanda' => $model->id,
                                                    'url' => $url,
                                                ]), [
                                                    'title' => AmosSondaggi::t('amossondaggi', 'Gestisci risposte - Il numero di risposte presenti deve essere maggiore del numero d risposte minime'),
                                                    'class' => 'btn btn-tool-secondary btn-danger'
                                                ]);
                                            }
                                        }
                                        return Html::a(AmosIcons::show('collection-plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/dashboard-risposte-predefinite/index',
                                            'idDomanda' => $model->id,
                                            'url' => $url,
                                        ]), [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Gestisci risposte'),
                                            'class' => 'btn btn-tool-secondary'
                                        ]);
                                    } else {
                                        return Html::a(AmosIcons::show('collection-plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/dashboard-risposte-predefinite/index',
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
                        'aggdomanda' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                                if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_CREATE', ['model' => $model])) {
                                    if (!$model->is_parent) {

                                    }
                                }
                        },
                        'aggrisposta' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIRISPOSTEPREDEFINITE_CREATE', ['model' => $model])) {
                                if (in_array($model->getSondaggiDomandeTipologie()->one()['id'], [1, 2, 3, 4])) {
                                    if ($model->getSondaggiRispostePredefinites()->count()) {
                                        return Html::a(AmosIcons::show('plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/dashboard-risposte-predefinite/create',
                                            'idDomanda' => $model->id,
                                            'url' => $url,
                                        ]), [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Aggiunti risposta'),
                                            'class' => 'btn btn-tool-secondary'
                                        ]);
                                    } else {
                                        return Html::a(AmosIcons::show('plus'), Yii::$app->urlManager->createUrl([
                                            '/' . $this->context->module->id . '/dashboard-risposte-predefinite/create',
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

                            if (in_array($model->getSondaggiDomandeTipologie()->one()['id'], [1, 2, 3, 4, 7, 8, 14])) {
                                $confDeleteMessage = 'Stai per cancellare la domanda comprese le  risposte contenute al suo interno';
                            } else {
                                $confDeleteMessage = 'Sei sicuro di voler eliminare questo elemento?';
                            }
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_DELETE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('delete'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande/delete',
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
                        'clone' => function ($url, $model) {
                            /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI') || \Yii::$app->getUser()->can('SONDAGGIDOMANDE_CREATE', ['model' => $model])) {
                                return Html::a(AmosIcons::show('copy'), Yii::$app->urlManager->createUrl([
                                    '/' . $this->context->module->id . '/dashboard-domande/clone',
                                    'id' => $model->id,
                                    'idSondaggio' => $model->sondaggi_id,
                                    'url' => $url
                                ]), [
                                    'title' => AmosSondaggi::t('amossondaggi', '#clone'),
                                    'class' => 'btn btn-tool-secondary'
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
