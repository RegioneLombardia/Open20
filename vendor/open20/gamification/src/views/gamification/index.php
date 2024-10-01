<?php

use yii\helpers\Html;
use open20\amos\core\views\DataProviderView;
use open20\amos\core\icons\AmosIcons;
use open20\gamification\Module;

/* @var $this yii\web\View
 * @var $searchModel open20\gamification\models\search\GamificationSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $currentView string
 * @var $model open20\gamification\models\Gamification
 * @var array $indirizzo
 */

$queryParamCurrentView = Yii::$app->request->getQueryParam('currentView');

?>
<div class="gamification-index">
    <?= $this->render('_search', ['model' => $model, 'queryParamCurrentView' => $queryParamCurrentView]); ?>
    <?=
    DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                [
                    'attribute' => 'titolo',
                    'label' => 'Titolo',
                ],
                [
                    'attribute' => 'descrizione',
                    'label' => 'Descrizione',
                ],
                [
                    'attribute' => 'starting_date',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asTime($model->starting_date, 'dd/MM/yyyy HH:mm');
                    },
                    'label' => 'Data inizio',
                ],
                [
                    'attribute' => 'finish_date',
                    'value' => function ($model) {
                        return Yii::$app->formatter->asTime($model->finish_date, 'dd/MM/yyyy HH:mm');
                    },
                    'label' => 'Data fine',
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->getStatus($model->id);
                    },
                    'label' => 'Stato',
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Pubblicato il',
                    'format' => 'date',
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{clone} {view} {update} {delete}',
                    'buttons' => [
                        'clone' => function ($url, $model) {
                            return Html::a(
                                Module::t('gamification', 'Copia'),
                                [
                                    '/'.Module::getModuleName().'/gamification/clone',
                                    'id' => $model->id,
                                ],
                                [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => Module::t('gamification', 'Copia'),
                                ]
                            );
                        },
                        'view' => function ($url, $model) {
                            $options = ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Visualizza'), 'data-pjax' => '0'];
                            return Html::a(
                                 AmosIcons::show('am am-file'), ['gamification/view', 'id' => $model->id], $options
                            );
                        },
                        'update' => function ($url, $model) {
                            $options = ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Modifica'), 'data-pjax' => '0'];
                            return Html::a(
                                 AmosIcons::show('am am-file'), ['gamification/update', 'id' => $model->id], $options
                            );
                        },
                        'delete' => function ($url, $model) {
                            $options = ['class' => 'btn btn-tools-secondary', 'title' => Yii::t('amoscore', 'Cancella'), 'data-pjax' => '0', 'data-confirm' => 'Confermare l’eliminazione di questa Gamification?'];
                            return Html::a(
                                 AmosIcons::show('am am-file'), ['gamification/delete', 'id' => $model->id], $options
                            );
                        },
                    ],
                ],
            ],
            'enableExport' => true,
        ],
        'exportConfig' => [
            'exportEnabled' => true,
        ],
    ]);
    ?>
</div>
