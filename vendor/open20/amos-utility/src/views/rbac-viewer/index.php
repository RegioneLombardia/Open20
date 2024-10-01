<?php

use open20\amos\core\helpers\Html;
use execut\widget\TreeView;

/**
 * @var yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\rbac\Item $itemChoose ,
 * @var \yii\data\ActiveDataProvider $dataProviderRoles
 */
$this->title = 'Visualizzazione Ruoli';
$this->params['breadcrumbs'][] = $this->title;

// pr(count($dataProvider->getModels())); die;


\Yii::$container->set('leandrogehlen\treegrid\TreeGridAsset', [
    'js' => [
        'js/jquery.cookie.js',
        'js/jquery.treegrid.min.js',
    ]
]);
?>

<div class="rbac-index">

    <?php
    //    foreach ($dataProvider->getModels() as $model) {
    //        pr($model->attributes);
    //    }


    $currentView = [
        'name' => 'grid',
        'label' => '<span class="am am-view-list-alt"> </span> <p>Tabella</p>',
        'url' => '?currentView=grid'
    ];
    echo \open20\amos\core\views\DataProviderView::widget([
        'dataProvider' => $dataProviderRoles,
        'currentView' => $currentView,
        'gridView' => [
                'filterModel' => $modelSearch,
            'columns' => [
                'name',
                'description',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('storage'), ['detail', 'item' => $model->name], [
                                'class' => 'btn btn-tools-secondary',
                                'title' => \open20\amos\utility\Module::t('amosutility', 'Visualizza albero permessi')
                            ]);
                        },
                    ]
                ]
            ],
        ],
    ]);

    ?>

</div>
