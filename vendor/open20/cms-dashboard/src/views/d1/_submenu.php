<?php

/**
 * @var int $lvl
 * @var string $container
 * @var bool $canPublish
 * @var \yii\data\ActiveDataProvider $dataProvider
 */
use open20\amos\core\views\AmosGridView;
use open20\cms\dashboard\utilities\Utility;
use open20\cms\dashboard\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

echo AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'showHeader' => false,
    'showPageSummary' => false,
    // 'rowOptions' => function ($model) {
    //     if ($model['offline']) {
    //         return ['class' => 'danger'];
    //     }
    //     return '';
    // },
    'columns' => [
        //   ['class' => 'yii\grid\SerialColumn'],
//        [
//            'width' => '4%;',
//            'value' => function ($model) {
//                return '';
//            },
//        ],
        [
            'width' => '4%;',
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index) {
                $children = Utility::getAdminMenuLuya('default', $model['nav_id'], true);
                if ($children->count() > 0) {
                    return AmosGridView::ROW_COLLAPSED;
//                                   return AmosGridView::ROW_EXPANDED;
                }
                return '';
            },
            'extraData' => [
                'container' => $container,
                'canPublish' => $canPublish,
                'lvl' => ($lvl + 1)
            ],
            'detailUrl' => '/dashboards/d1/luya-admin-menu?view=_submenu',
            'disabled' => function ($model, $key, $index, $column) use ($container) {
                $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);

                if ($children->count() > 0) {
                    return false;
                }
                return true;
            },
            'expandOneOnly' => false,
            'detailRowCssClass' => '',
        //'options' => ['id' => 'exandrow' . $model['nav_id']]
        ],
        [
            'width' => '6%;',
            'attribute' => 'offline',
            'label' => Module::txt('Stato'),
            'value' => function ($model) {
                $statusOnline = Module::txt('Pubblicato');
                $statusOffline = Module::txt('Bozza');
                if ($model['offline']) {
                    return ('<span class="mdi mdi-cloud-off-outline mdi-24px text-muted" title="' . $statusOffline . '" data-toggle="tooltip"></span>');
                }
                return ('<span class="mdi mdi-cloud mdi-24px text-primary" title="' . $statusOnline . '" data-toggle="tooltip"></span>');
            },
            'format' => 'raw',
        ],
        [
            'width' => '50%;',
            'attribute' => 'title',
            'label' => Module::txt('Pagina'),
            'value' => function ($model) use ($lvl) {
//$spaces = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $lvl); //'<span class="mdi mdi-subdirectory-arrow-right"></span>';

                return '<div class="d-flex"><span class="mdi mdi-subdirectory-arrow-right m-r-10"></span><strong>' . $model['title'] . '</strong></div>';
            },
            'format' => 'html',
        ],
        [
            'width' => '15%;',
            'attribute' => 'hidden',
            'label' => Module::txt('Visibile nel menu'),
            'value' => function ($model) {
                return ($model['hidden'] ? Module::txt('No') : Module::txt('Si'));
            },
        ],
        [
            'width' => '6%;',
            'attribute' => 'ordina',
            'label' => 'Ordina',
            'value' => function ($model) use ($lvl) {
                return Html::a('<span class="mdi mdi-arrow-split-horizontal"></span>',
                        ['/' . Module::getModuleName() . '/d1/new-sort', 'id' => $model['nav_id'], 'lvl' => $lvl],
                        ['class' => 'btn btn-default',
                            'onclick' => 'newSortRows(' . $model['nav_id'] . ', "' . $model['title'] . '")',
                            'id' => 'createNewSort' . $model['nav_id'],
                ]);
            },
            'format' => 'raw'
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{preview}{visibility}',
            'buttons' => [
                'preview' => function ($url, $model) {
                    $url = '/' . Module::getModuleName() . '/d1/menu';
                    $link = Utility::getLinkTarget($model);
                    $onlineVersion = Utility::getOnlineVersion($model['id0']);
                    $target = ['/cms-page-preview', 'itemId' => $model['id0'], 'version' => (!empty($onlineVersion['id']) ? $onlineVersion['id'] : 1)];
                    if (!empty($link)) {
                        $target = $link;
                    }
                    return Html::a('<span class="mdi mdi-eye"></span>', $target
                            , [
                        'title' => Module::txt('Visualizza l\'anteprima'),
                        'class' => 'btn btn-tool-secondary',
                        'target' => '_blank',
                    ]);
                },
                'visibility' => function ($url, $model) {
                    $url = '/' . Module::getModuleName() . '/d1/menu';
                    if ($model['hidden']) {
                        return Html::a('<span class="mdi mdi-eye"></span>', ['/' . Module::getModuleName() . '/d1/set-visibility-menu', 'id' => $model['nav_id'], 'value' => 0]
                                        , [
                                    'title' => Module::txt('Rendi visibile nel menu'),
                                    'class' => 'btn btn-tool-secondary',
                        ]);
                    }
                    return Html::a('<span class="mdi mdi-eye"></span>', ['/' . Module::getModuleName() . '/d1/set-visibility-menu', 'id' => $model['nav_id'], 'value' => 1]
                            , [
                        'title' => Module::txt('Nascondi nel menu'),
                        'class' => 'btn btn-tool-secondary',
                    ]);
                },
            ],
        ],
    ]
]);
