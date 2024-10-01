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

echo DataProviderView::widget([
    'dataProvider' => $dataProvider,
    'currentView' => $currentView,
    'gridView' => [
        'striped' => false,
        'showPageSummary' => false,
        'showHeader'=> false,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'id',
            'domanda:ntext',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{update} {clone} {delete}',
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
]);
?>
