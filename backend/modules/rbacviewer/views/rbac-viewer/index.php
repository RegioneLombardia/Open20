<?php

use open20\amos\core\helpers\Html;
use execut\widget\TreeView;

/**
 * @var yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\rbac\Item $itemChoose ,
 * @var \yii\data\ActiveDataProvider $dataProviderRoles
 */
$this->title = 'Visualizzazione Ruoli, Permessi e Regole';
$this->params['breadcrumbs'][] = $this->title;

// pr(count($dataProvider->getModels())); die;


Yii::$container->set('leandrogehlen\treegrid\TreeGridAsset',[
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
    if ($itemChoose):
        echo \leandrogehlen\treegrid\TreeGrid::widget([
            'dataProvider' => $dataProvider,
            'keyColumnName' => 'id',
            'parentColumnName' => 'parent',
            //'parentRootValue' => '0', //first parentId value
            'pluginOptions' => [
//            'initialState' => 'collapsed',
            ],
            'columns' => [
//           'id',
//           'child',
                'item',
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{modal}',
                    'buttons' => [
                        'modal' => function ($url, $model) {
                            /** @var \backend\modules\rbacviewer\models\AuthItemsTreeViewTemp $model */
                            $htmlId = stripslashes(str_replace('/', '', $model->item)) . '-' . $model->id;
                            \yii\bootstrap\Modal::begin([
                                'id' => $htmlId,
//                            'clientOptions' => [
//                                'remote' => '/amministra-utenti/role/view?id=RAPPRESENTANTE_LEGALE_ENTE',
//                            ],
                                'header' => '<h2>La modale ' . $model->item . '</h2>',
                            ]);

//                        $a = new \mdm\admin\controllers\RoleController();
//                        echo $a->render('view',['id' => 'RAPPRESENTANTE_LEGALE_ENTE']);
                            // echo $this->render('modal', [
                            //     'item' => $model->item
                            // ]);

                            \yii\bootstrap\Modal::end();
                            return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'),
                                'javascript:$(\'#' . $htmlId . '\').modal(\'show\')',
//                            'javascript:console.log(\'miao\')',
                                [
                                    'title' => 'Modifica',
                                    'class' => 'btn btn-tool-secondary'
                                ]);
                        },
                    ]
                ]
            ]
        ]);
    else:
//        $currentView = [
//            'name' => 'grid',
//            'label' => '<span class="am am-view-list-alt"> </span> <p>Tabella</p>',
//            'url' => '?currentView=grid'
//        ];
//        echo \open20\amos\core\views\DataProviderView::widget([
//            'dataProvider' => $dataProviderRoles,
//            'currentView' => $currentView,
//            'gridView' => [
//                'columns' => [
//
//
//                    [
//                        'class' => 'open20\amos\core\views\grid\ActionColumn',
//                        'template' => '{visualizza}',
//                        'buttons' => [
//                            'visualizza' => function ($url, $model) {
//
//                            },
//                        ]
//                    ]
//                ],
//            ],
//        ]);

        ?>
        TODO: tutti i ruoli
        <?php
    endif;
    ?>
</div>
