<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-elasticsearch/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\core\module\BaseAmosModule;
use open20\design\utility\DesignUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\elasticsearch\Module;
use open20\elasticsearch\assets\ElasticSearchAsset;

/**
 * @var yii\web\View $this
 * @var open20\elasticsearch\models\ElasticsearchHistory $model
 */

$this->title = Module::t('amoselasticsearch', "Registro risultati ricerca: {text}", [
    'text' => $model->search_text
]);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/elasticsearch']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Elasticsearch History'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['titleSection'] = $this->title;

$recordPageSize = 25;

$searchedAt = new \DateTime($model->created_at);

ElasticSearchAsset::register($this);
?>

<div class="elasticsearch-history-view">

    <div class="row">
        <div class="col-xs-12">
            <p>
                <?=
                Module::t(
                    'amoselasticsearch',
                    "In data {date} per la ricerca di {text} sono stati registrati {tot} risultati.",
                    [
                        'date' => Html::tag('strong', $searchedAt->format('d/m/Y H:i')),
                        'text' => Html::tag('strong', $model->search_text),
                        'tot' => Html::tag('strong', $model->tot_found),
                    ]
                )
                ?>
            </p>
            <p>
                <?=
                Module::t(
                    'amoselasticsearch',
                    "Di seguito sono riportati i primi {n} record nello stesso ordine visualizzato al momento della ricerca.",
                    [
                        'n' => Html::tag('strong', $recordPageSize),
                    ]
                )
                ?>
            </p>
        </div>
        <div class="col-xs-12">
            <div class="modulo-backend-search">
                <div class="list-search-container d-flex flex-wrap">
                    <?php
                    $module = \Yii::$app->getModule('elasticsearch');
                    if (!empty($module->history)) {
                        
                        $dataProvider->pagination->pageSize = $recordPageSize;

                        echo \yii\widgets\ListView::widget([
                            'dataProvider' => $dataProvider,
                            'itemView' => '_itemListSearchDesign',
                            'summary' => false,
                            'pager' => DesignUtility::listViewPagerConfig(),
                            'itemOptions' => [
                                'tag' => false
                            ],
                            'options' => [
                                'class' => 'list-view w-100'
                            ]
                        ]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


</div>


<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Module::t('amoselasticsearch', 'Back'), '/elasticsearch/elasticsearch-history/index', ['class' => 'btn btn-secondary']); ?>
</div>