<?php

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\models\SondaggiInvitations;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiDomandeSearch $searchModel
 */
$this->title                    = AmosSondaggi::t('amossondaggi', 'Comunicazioni');
$this->params['breadcrumbs'][]  = ['label' => $this->title, 'url' => ['/'.$this->context->module->id.'/sondaggi/manage']];
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
$this->params['titleButtons'][] = Html::a(AmosIcons::show('plus-circle').'&nbsp;'.AmosSondaggi::t('amossondaggi',
            '#new_f'),
        Yii::$app->urlManager->createUrl([
            '/'.$this->context->module->id.'/dashboard/create-communication',
            'idSondaggio' => filter_input(INPUT_GET, 'sondaggi_id'),
            'url' => $url,
        ]), [
        'title' => AmosSondaggi::t('amossondaggi', '#new_f'),
        'class' => 'btn btn-primary'
    ]);
?>
<div class="sondaggi-invitations-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <p>
        <?php /* echo         Html::a(AmosSondaggi::t('amossondaggi', 'Nuovo {modelClass}', [
          'modelClass' => 'Sondaggi Domande Pagine',
          ])        , ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>

    <?php
    Pjax::begin(['id' => 'dashboard-invitations-view', 'timeout' => 10000]);
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn'],
                // 'id',
                'name:ntext',
//                'subject:ntext',
                'target' => [
                    'attribute' => 'target',
                    'value' => function ($model) {
                        return $model->getTargetLabel()[$model->target];
                    }
                ],
                'count',
                'email_test',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{test}{invia}{update}{delete}',
                    'buttons' => [
                        'test' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
                                return Html::a(AmosIcons::show('email'),
                                        ['/sondaggi/dashboard/send-communications', 'id' => $model->id, 'url' => $url, 'preview' => true],
                                        [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Invia test'),
                                        'class' => 'btn btn-tool-secondary',
                                        'data' => [
                                            'id' => $model->id
                                        ]
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'invia' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
                                return Html::a(AmosIcons::show('mail-send'),
                                        ['/sondaggi/dashboard/send-communications', 'id' => $model->id, 'url' => $url],
                                        [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Invia la comunicazione'),
                                        'class' => 'btn btn-success activate-list',
                                        'data' => [
                                            'id' => $model->id,
                                            'confirm' => AmosSondaggi::t('amossondaggi',
                                                '#send_communication_to_organizations_confirm'),
                                        ]
                                ]);
                            } else {
                                return '';
                            }
                        },
                        'update' => function ($url, $model) {
                            $url = \yii\helpers\Url::current();
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
                                return Html::a(AmosIcons::show('edit'),
                                        Yii::$app->urlManager->createUrl([
                                            '/'.$this->context->module->id.'/dashboard/update-communication',
                                            'id' => $model->id,
                                            'idSondaggio' => $model->sondaggi_id,
                                            'url' => $url,
                                        ]),
                                        [
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
                            if (\Yii::$app->getUser()->can('AMMINISTRAZIONE_SONDAGGI')) {
                                return Html::a(AmosIcons::show('delete'),
                                        Yii::$app->urlManager->createUrl([
                                            '/'.$this->context->module->id.'/dashboard/delete-communication',
                                            'id' => $model->id,
                                            'idSondaggio' => $model->sondaggi_id,
                                            'url' => $url,
                                        ]),
                                        [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Cancella'),
                                        'class' => 'btn btn-danger-inverse',
                                        'data' => [
                                            'confirm' => AmosSondaggi::t('amossondaggi', '#delete_communication_dialog')
                                        ]
                                ]);
                            } else {
                                return '';
                            }
                        },
                    ]
                ],
            ],
        ]
    ]);
    Pjax::end();
    ?>
    <p>
        <?php
//        if (isset($url)) :
//            echo Html::a(AmosSondaggi::t('amossondaggi', 'Aggiungi domanda'), ['create', 'idSondaggio' => filter_input(INPUT_GET, 'idSondaggio'), 'idPagina' => filter_input(INPUT_GET, 'idPagina'), 'url' => yii\helpers\Url::current()], ['class' => 'btn btn-success']);
//        endif;
        ?>
    </p>
</div>
