<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-elasticsearch/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\DataProviderView;
use yii\widgets\Pjax;
use open20\elasticsearch\Module;
use open20\elasticsearch\assets\ElasticSearchAsset;
use open20\amos\layout\assets\SpinnerWaitAsset;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\elasticsearch\models\search\ElasticsearchHistorySearch $model
 */

ElasticSearchAsset::register($this);
SpinnerWaitAsset::register($this);

$this->title = Yii::t('amoselasticsearch', 'Search history');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/elasticsearch']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['titleSection'] = $this->title;

$searchTex = $model->search_text;
$js = <<<JS

      $.ajax({
           url: '/elasticsearch/elasticsearch-history/most-searched-words',
           type: 'get',
           data: {search_text: '$searchTex'},
           success: function (data) {
              $('#most-searched-words').html(data);
           }
     });

JS;

$this->registerJs($js, \yii\web\View::POS_LOAD);

?>
<div class="elasticsearch-history-index">

    <div class="row">

        <div class="col-xs-12">
            <?= $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]); ?>
        </div>

        <div class="col-xs-12 col-md-6">
            <h4 class="text-uppercase mb-0"><?= Module::t('amoselasticsearch', "Le ricerche") ?></h4>
            <p class="lead">
                <?= Module::t(
                    'amoselasticsearch',
                    "Elenco di tutte le ricerche effettuate {time}",
                    [
                        'limit' => '15',
                        'time' => Module::t('amoselasticsearch', 'da inizio tracciamento')
                    ]
                )
                ?>
            </p>
            <hr>
            <?php $dataProvider->pagination->pageSize = 15; ?>
            <?= DataProviderView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $model,
                'currentView' => $currentView,
                'gridView' => [
                    'columns' => [
                        [
                            'attribute' => 'created_at',
                            'value' => function ($model) {
                                if ($model->created_at) {
                                    $date = new \DateTime($model->created_at);
                                    return $date->format('d/m/Y H:i:s');
                                }
                            }

                        ],
                        'search_text',
                        [
                            'class' => 'open20\amos\core\views\grid\ActionColumn',
                            'template' => '{view}'
                        ],
                    ],
                ],
            ]);
            ?>
        </div>

        <div class="col-xs-12 col-md-5 col-md-push-1 m-t-30">
            <div id="most-searched-words-container" class="callout callout-info">
                <div class="callout-title">
                    <span class="mdi mdi-trending-up"></span><?= ' ' . Module::t('amoselasticsearch', "I trend più ricercati") ?>
                </div>
                <p class="lead">
                    <?= Module::t(
                        'amoselasticsearch',
                        "Elenco dei {limit} trend più ricercati {time}",
                        [
                            'limit' => '15',
                            'time' => Module::t('amoselasticsearch', 'da inizio tracciamento')
                        ]
                    )
                    ?>
                </p>
                <hr>
                <div id="most-searched-words">
                    <div class="col-md-12">
                        <div class="loader-3dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>