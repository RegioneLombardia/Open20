<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $container
 * @var $canPublish
 * @var $lvl
 * @var $ancestors array
 */
use open20\amos\core\views\AmosGridView;
use open20\cms\dashboard\utilities\Utility;
use open20\cms\dashboard\Module;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use luya\admin\models\User;

$dataProvider->sort = false;
echo AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'showHeader' => true,
    'showPageSummary' => false,
    'options' => [
        'id' => 'subpage_' . time(),
    ],
    'columns' => [
        [
            'width' => '4%;',
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index) use ($container, $ancestors, $lvl) {
                $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);
                if(!empty($ancestors) && $model['nav_id'] == $ancestors[$lvl + 1]) {
                    return AmosGridView::ROW_EXPANDED;
                }
                if ($children->count() > 0) {
                    return AmosGridView::ROW_COLLAPSED;
                }
                return '';
            },
            'extraData' => [
                'container' => $container,
                'canPublish' => $canPublish,
                'lvl' => ($lvl + 1),
                'ancestors' => $ancestors
            ],
            'detailUrl' => '/dashboards/d1/luya-admin-menu?view=_subpage',
            'disabled' => function ($model, $key, $index, $column) use ($container) {
                $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);

                if ($children->count() > 0) {
                    return false;
                }
                return true;
            },
            'expandOneOnly' => false,
            'detailRowCssClass' => '',
            'header' => '',
        ],
        [
            'attribute' => 'offline',
            'width' => '6%;',
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
            'width' => '6%',
            'attribute' => 'hidden',
            'label' => Module::txt('Visibile'),
            'value' => function ($model) {
                return Utility::getVisibilityIcon($model['hidden']);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'title',
            'width' => '30%;',
            'label' => Module::txt('Pagina'),
            'value' => function ($model) use ($lvl) {
                $spaces = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $lvl);
                return '<div class="d-flex">' . $spaces . '<span class="mdi mdi-subdirectory-arrow-right m-r-10"></span><strong>' . $model['title'] . '</strong></div>';
            },
            'format' => 'html',
        ],
        [
            'width' => '20%',
            'attribute' => 'nav_item_type',
            'label' => Module::txt('Tipologia'),
            'value' => function ($model) {
                return Utility::getTipologia($model['nav_item_type']);
            },
        ],
        [
            'width' => '15%',
            'attribute' => 'update_user_id',
            'label' => Module::txt('Ultima modifica'),
            'value' => function ($model) {
                if (empty($model['update_user_id'])) {
                    return User::findOne($model['create_user_id'])->firstname
                            . ' '
                            . User::findOne($model['create_user_id'])->lastname;
                }
                return User::findOne($model['update_user_id'])->firstname
                . ' '
                . User::findOne($model['update_user_id'])->lastname;
            }
        ],
        [
            'width' => '15%',
            'attribute' => 'timestamp_update',
            'label' => Module::txt('Data ultima modifica'),
            'value' => function ($model) {
                if (empty($model['timestamp_update']))
                    return \Yii::$app->formatter->asDatetime($model['timestamp_create'], 'php:d/m/Y H:i');
                return \Yii::$app->formatter->asDatetime($model['timestamp_update'], 'php:d/m/Y H:i');
            }
        ],
        [
            'width' => '10%',
            'attribute' => 'publish_from',
            'label' => Module::txt('Inizio pubblicazione'),
            'value' => function ($model) {
                if (empty($model['publish_from'])) {
                    return Module::txt('Immediata');
                }
                $data = \Yii::$app->formatter->asDatetime($model['publish_from'], 'php:d/m/Y H:i');
                return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style' => 'white-space:nowrap;']);
            },
            'format' => 'raw'
        ],
        [
            'width' => '10%',
            'attribute' => 'publish_till',
            'label' => Module::txt('Fine pubblicazione'),
            'value' => function ($model) {
                if (empty($model['publish_till'])) {
                    return Module::txt('Mai');
                }
                $data = \Yii::$app->formatter->asDatetime($model['publish_till'], 'php:d/m/Y H:i');
                return Html::tag('p', $data, ['class' => 'm-t-0 m-b-0 ', 'style' => 'white-space:nowrap;']);
            },
            'format' => 'raw'
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{preview}{pagina}{update}{create}{version}{sort}{publish}{visibility}{delete}',
            'buttons' => [
                'sort' => function ($url, $model) use ($container) {
                    return Html::a('<span class="mdi mdi-arrow-split-horizontal"></span>',
                            ['/' . Module::getModuleName() . '/d1/new-sort', 'id' => $model['nav_id'], 'lvl' => 0, 'container' => $container],
                            ['class' => 'btn btn-tool-secondary',
//                            'onclick' => 'newSortRows(event, ' . $model['nav_id'] . ', "' . $model['title'] . '")',
                                'id' => 'createNewSort' . $model['nav_id'],
                                'title' => Module::txt('Sposta nel menù'),
                                'data-class' => 'sort-page',
                                'data-title' => $model['title'],
                                'data-key' => $model['nav_id']
                    ]);
                },
                'visibility' => function ($url, $model) use ($container) {
                    $offline = $model['offline'];
                    if ($offline) {
                        return '';
                    }
                    $url = \yii\helpers\Url::current();
                    if ($model['hidden']) {
                        return Html::a('<span class="mdi mdi-eye"></span>', [
                                    '/' . Module::getModuleName() . '/d1/set-visibility-menu', 'container' => $container,
                                    'id' => $model['nav_id'],
                                    'value' => 0,
                                    'url' => \Yii::$app->request->url
                                        ]
                                        , [
                                    'title' => Module::txt('Rendi visibile nel menu'),
                                    'class' => 'btn btn-tool-secondary',
                        ]);
                    }
                    return Html::a('<span class="mdi mdi-eye"></span>', [
                        '/' . Module::getModuleName() . '/d1/set-visibility-menu', 'container' => $container,
                        'id' => $model['nav_id'],
                        'value' => 1,
                        'url' => \Yii::$app->request->url
                            ]
                            , [
                        'title' => Module::txt('Nascondi nel menu'),
                        'class' => 'btn btn-tool-secondary',
                    ]);
                },
                'preview' => function ($url, $model) {
                    $url = '/' . Module::getModuleName() . '/d1/pagine';
                    $link = Utility::getLinkTarget($model);
                    $onlineVersion = Utility::getOnlineVersion($model['id0']);
                    $target = ['/cms-page-preview', 'itemId' => $model['id0'], 'version' => (!empty($onlineVersion['id']) ? $onlineVersion['id'] : 1)];
                    if (!empty($link)) {
                        $target = $link;
                    }
                    return Html::a(
                            '<span class="mdi mdi-eye"></span>',
                            $target,
                            [
                                'title' => Module::txt('Visualizza l\'anteprima'),
                                'class' => 'btn btn-tool-secondary',
                                'target' => '_blank',
                                'style' => 'border-bottom: 1px solid #ccc'
                            ]
                    );
                },
                'publish' => function ($url, $model) use ($canPublish, $container) {
                    $ver = 0;
                    $dataConfirm = null;
                    if ($model['nav_item_type'] == 1) {
                        $ver = $model['nav_item_type_id'];
                    }
                    if (Utility::checkWfRequest($model['id0'], $ver)) {
                        return '';
                    }
                    $url = '/' . Module::getModuleName() . '/d1/pagine?container=' . $container . '&openPage=' . $model['nav_id'];
                    $offline = $model['offline'];
                    if ($offline) {
                        $icon = '<span class="mdi mdi-earth"></span>';
                        $pubUrl = ['/' . Module::getModuleName() . '/d1/publication-request', 'nav_id' => $model['nav_id'], 'item_id' => $model['id0'], 'version_id' => $ver, 'url' => $url];
                        $text = Module::txt('Richiedi la pubblicazione della pagina.');
                        $textAdmin = Module::txt('Pubblica la pagina');
                        $dataConfirm = Module::txt('Sei sicuro di voler pubblicare la pagina <strong>{pageName}</strong>?<br>La pagina sarà raggiungibile da url.', ['pageName' => $model['title']]);
                        $style = 'color:#297a38;';
                        if ($ver) {
                            $pubUrlAdmin = ['/' . Module::getModuleName() . '/d1/publish-version', 'id' => $ver, 'url' => $url];
                        } else {
                            $pubUrlAdmin = ['/' . Module::getModuleName() . '/d1/publish-page', 'id' => $model['nav_id'], 'url' => $url];
                        }
                    } else {
                        if ($model['is_home']) {
                            return '';
                        }
                        $icon = '<span class="mdi mdi-earth-off"></span>';
                        $text = Module::txt('Riporta la pagina allo stato Bozza');
                        $textAdmin = Module::txt('Riporta in Bozza');
                        $dataConfirm = Module::txt('Sei sicuro di voler riportare la pagina <strong>{pageName}</strong> in stato Bozza?<br>La pagina non sarà più raggiungibile da url.', ['pageName' => $model['title']]);
                        $pubUrl = ['/' . Module::getModuleName() . '/d1/unpublishing-request', 'nav_id' => $model['nav_id'], 'item_id' => $model['id0'], 'version_id' => $model['nav_item_type_id'], 'url' => $url];
                        $pubUrlAdmin = ['/' . Module::getModuleName() . '/d1/unpublish-page', 'nav_id' => $model['nav_id'], 'url' => $url];
                        $style = 'color:#a61919; border-top:1px solid #ccc';
                    }
                    if ($canPublish == 'true') {
                        $pubUrl = $pubUrlAdmin;
                        $text = $textAdmin;
                    }
                    return Html::a(
                            $icon,
                            $pubUrl,
                            [
                                'title' => $text,
                                'class' => 'btn btn-' . ((!$offline) ? 'warning' : 'tool-secondary'),
                                'style' => $style,
                                'data-confirm' => $dataConfirm,
                            ]
                    );
                },
                'pagina' => function ($url, $model) {
                    $url = '/dashboards/d1/pagine?container=' . $model['alias'] . '&openPage=' . $model['nav_id'];
                    return Html::a(
                            '<span class="mdi mdi-cog"></span>',
                            ['/' . Module::getModuleName() . '/d1/update-page', 'id' => $model['nav_id'], 'url' => $url],
                            [
                                'title' => Module::txt('Modifica proprietà'),
                                'class' => 'btn btn-tool-secondary',
                            ]
                    );
                },
                'update' => function ($url, $model) use ($canPublish, $container) {
                    if ($model['nav_item_type'] == 3) {
                        return '';
                    }
                    $ver = 0;
                    if ($model['nav_item_type'] == 1) {
                        $ver = $model['nav_item_type_id'];
                    }
                    $url = Yii::$app->urlManager->createUrl([
                        '/' . Module::getModuleName() . '/d1/pagine',
                        'container' => $container
                    ]);
                    if ($ver && !$model['offline'] && !$canPublish) {
                        $draft = Utility::getMyDraft($model['id0']);
                        if (empty($draft)) {
                            return Html::a(
                                            '<span class="mdi mdi-pencil"></span>',
                                            ['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model['nav_id']],
                                            [
                                                'title' => Module::txt('Modifica la tua ultima versione in bozza'),
                                                'class' => 'btn btn-tool-secondary',
                                            ]
                            );
                        } else {
                            return Html::a(
                                            '<span class="mdi mdi-pencil"></span>',
                                            ['/' . Module::getModuleName() . '/d1/create-new-version', 'item_id' => $model['id0'], 'page_id' => $ver],
                                            [
                                                'title' => Module::txt('Crea una nuova versione'),
                                                'class' => 'btn btn-tool-secondary',
                                                'onclick' => 'newVersionDraft(' . $model['id0'] . ')',
                                                'id' => 'createNewVersionDraft' . $model['id0'],
                                            ]
                            );
                        }
                    }
                    return Html::a(
                            '<span class="mdi mdi-pencil"></span>',
                            ['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model['nav_id']],
                            [
                                'title' => Module::txt('Modifica contenuti'),
                                'class' => 'btn btn-tool-secondary',
                            ]
                    );
                },
                'create' => function ($url, $model) use ($lvl) {
                    $url = '/' . Module::getModuleName() . '/d1/pagine?container=' . $model['alias'];
                    if ($lvl == 5) {
                        return '';
                    }
                    return Html::a(
                            '<span class="mdi mdi-plus-box-multiple-outline"></span>',
                            \Yii::$app->urlManager->createUrl(
                                    [
                                        '/' . Module::getModuleName() . '/d1/create-page',
                                        'parent_id' => $model['nav_id'],
                                        'container' => $model['alias'],
                                    ]
                            ),
                            [
                                'title' => Module::txt('Crea pagina figlia'),
                                'class' => 'btn btn-tool-primary',
                            ]
                    );
                },
                'version' => function ($url, $model) use ($canPublish) {
                    $url = \yii\helpers\Url::current();
                    if ($model['nav_item_type'] == 3 || !$canPublish) {
                        return '';
                    }
                    $ver = 0;
                    if ($model['nav_item_type'] == 1) {
                        $ver = $model['nav_item_type_id'];
                    }
                    return Html::a(
                            '<span class="mdi mdi-pencil"></span>',
                            ['/' . Module::getModuleName() . '/d1/create-new-version', 'item_id' => $model['id0'], 'page_id' => $ver],
                            [
                                'title' => Module::txt('Crea una nuova versione'),
                                'class' => 'btn btn-tool-secondary',
                                'onclick' => 'newVersionDraft(' . $model['id0'] . ')',
                                'id' => 'createNewVersionDraft' . $model['id0'],
                            // 'style' => 'color:#a61919',
                            // 'data-toggle' => 'tooltip'
                            ]
                    );
                },
                'delete' => function ($url, $model) use ($container, $canPublish) {
                    $disabled = false;
                    if (!$model['offline'] && !$canPublish) {
                        $disabled = true;
                    }
                    $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);
                    /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                    if ($children->count() > 0 || $model['offline'] == 0) {
                        $disabled = true;
                    }
                    $url = Yii::$app->urlManager->createUrl([
                        '/' . Module::getModuleName() . '/d1/pagine',
                        'container' => $container
                    ]);
                    return Html::a('<span class="mdi mdi-delete"></span>', ($disabled ? '#' : Yii::$app->urlManager->createUrl([
                                '/' . Module::getModuleName() . '/d1/delete-page',
                                'id' => $model['nav_id'],
                                'item_id' => $model['id0'],
                                'container' => $container,
                                'url' => $url,
                            ])), [
                        'title' => Module::txt('Elimina'),
                        'class' => 'btn btn-danger-inverse',
                        'style' => ($disabled ? 'display:none;' : 'color:#a61919; border-top:1px solid #ccc'),
                        'data-confirm' => ($disabled ? null : Module::txt('Sei sicuro di voler eliminare la pagina <strong>{pageName}</strong>?', ['pageName' => $model['title']])),
                            // 'data-toggle' => 'tooltip',
                    ]);
                },
            ],
        ],
    ]
]);
