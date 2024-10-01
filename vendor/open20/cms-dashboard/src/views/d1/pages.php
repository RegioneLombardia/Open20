<?php

/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $container
 * @var $modelContainer
 * @var $menuQuery
 * @var $ancestors Array
 */

use open20\amos\core\views\AmosGridView;
use open20\cms\dashboard\utilities\Utility;
use open20\cms\dashboard\Module;
use open20\amos\core\helpers\Html;
use luya\admin\models\User;

$this->title = Module::txt('Pagine');

$this->params['breadcrumbs'][] = $this->title;
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$js = <<<JS
    $('.kv-expand-icon-cell').on('click', function(e){
       var tr =  $(this).parents('tr');
       if(!$(tr).hasClass('active')){
           $(tr).addClass('active');
       }else{
           $(tr).removeClass('active');
       }
    });
JS;
$this->registerJs($js);

$this->registerJs('
        $("input:radio[name=containerRadio]").change(function() {
            window.location.href = "/' . Module::getModuleName() . '/d1/pagine?container="+this.value;
        });
        $(\'.pagine-search.element-to-toggle[data-toggle-element="form-search"]\').addClass(\'toggleIn\');
       

        ', \yii\web\View::POS_READY);
$canPublish = \Yii::$app->user->can('CMS_PUBLISH_PAGE');

$dataProvider->sort = false;
?>
<script type="text/javascript">
    var currentBtn = null;

    function newVersionDraft(id) {
        currentBtn = id;
        event.preventDefault();
<?php if ($canPublish) { ?>
            $('#vvers').val('');
            $('#vvers').trigger('change.select2');
            $('.js-data-versions').select2({
                ajax: {
                    url: '/dashboards/d1/ajax-versions?id=' + currentBtn,
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    }
                }
            });
<?php } ?>
        $("#modal-text").modal("show");
    }

    function submitNewVersion() {
        if (currentBtn) {
            var url = $('#createNewVersionDraft' + currentBtn).attr('href');
            var vname = encodeURIComponent($('#vname').val());
<?php if ($canPublish) { ?>
                var vvers = encodeURIComponent($('#vvers').val());
                if (vname) {
                    url = url + '&vname=' + vname + '&vvers=' + vvers;
                    window.location.href = url;
                }
<?php } else { ?>
                if (vname) {
                    url = url + '&vname=' + vname;
                    window.location.href = url;
                }
<?php } ?>
        }
    }
</script>

<?=
$this->render('_search', [
    'model' => $modelContainer,
    'originAction' => Yii::$app->controller->action->id
]);
?>

<div class="index-change-container">
    <?php echo $this->render('parts/_modal_sort', ['modelContainer' => $modelContainer, 'menuQuery' => $menuQuery, 'container' => $container]); ?>

    <?php
    \yii\bootstrap\Modal::begin([
        'header' => '<h4>' . Module::txt('Dati per creare la nuova versione') . '</h4>',
        'options' => ['id' => 'modal-text'],
            //'toggleButton' => ['label' => 'click me'],
    ]);
    echo '<form><div class="form-group">';
    echo '<label>' . Module::txt('Nome della nuova versione') . '</label>';
    echo '<input type="text" class="form-control" id="vname" name="vname"><br>';
    if ($canPublish) {
        echo '<label>' . Module::txt('Quale versione vuoi copiare?') . '</label>';
        echo '<p><select id="vvers" name="vvers" class="js-data-versions" required></select></p>';
    }
    echo '<button type="submit" id="newvers" data-modal="true" onclick="submitNewVersion()" class="btn btn-navigation-primary">' . Module::txt('Conferma') . '</button>';
    echo '</div></form>';
    \yii\bootstrap\Modal::end();
    ?>
    <div class="filter-menu m-b-30">

        <div>
            <label for="select-container"><?= Module::txt('Mostra le voci del menù') ?></label>
        </div>
        <div class="radio-filter">
            <?php
            $containerSearchId = !empty($modelContainer['container']) ? $modelContainer['container'] : 'default';
            foreach (Utility::getAllCmsContainer() as $localcontainer) {
                ?>


                <label class="radio-inline" for="container-radio-<?= $localcontainer["alias"] ?>">

                    <input class="" type="radio" name="containerRadio"
                           id="container-radio-<?= $localcontainer["alias"] ?>"
                           value="<?= $localcontainer["alias"] ?>" <?= $containerSearchId == $localcontainer["alias"] ? 'checked' : ''; ?>>
                           <?= $localcontainer["name"] ?>
                </label>

                <?php
            }
            ?>
        </div>
    </div>
    <?php
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'showPageSummary' => false,
        'options' => [
            'id' => 'main-page_' . time(),
        ],
        'columns' => [
            [
                'width' => '4%',
                'columnKey' => 'main_node',
                'class' => 'kartik\grid\ExpandRowColumn',
                'value' => function ($model, $key, $index) use ($container, $ancestors) {
                    if(!empty($ancestors) && $model['nav_id'] == $ancestors[0]) {
                        return AmosGridView::ROW_EXPANDED;
                    }
                    $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);
                    if ($children->count() > 0) {
                        return AmosGridView::ROW_COLLAPSED;
                    }
                    return '';
                },
                'extraData' => [
                    'container' => $container,
                    'canPublish' => $canPublish,
                    'lvl' => 0,
                    'postSearch' => \Yii::$app->request->get(),
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
                'allowBatchToggle' => false,
            ],
            [
                'width' => '6%',
                'attribute' => 'offline',
                'label' => Module::txt('Stato'),
                'value' => function ($model) {
                    return Utility::getStatusPage($model['nav_id'], $model['id0'], $model['offline']);
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
                'width' => '30%',
                'attribute' => 'title',
                'label' => Module::txt('Titolo'),
                'value' => function ($model) {
                    return Utility::getTitle($model['title'], $model['is_home']);
                },
                'format' => 'raw',
            ],
            [
                'width' => '15%',
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
                    'sort' => function ($url, $model) {
                        return Html::a('<span class="mdi mdi-arrow-split-horizontal"></span>',
                                ['/' . Module::getModuleName() . '/d1/new-sort', 'id' => $model['nav_id'], 'lvl' => 0, 'container' => $model['alias']],
                                ['class' => 'btn btn-tool-secondary',
//                                'onclick' => 'newSortRows(event, ' . $model['nav_id'] . ', "' . $model['title'] . '")',
                                    'id' => 'createNewSort' . $model['nav_id'],
                                    'title' => Module::txt('Sposta nel menù'),
                                    'data-class' => 'sort-page',
                                    'data-title' => $model['title'],
                                    'data-key' => $model['nav_id']
                        ]);
                    },
                    'preview' => function ($url, $model) {
                        $url = \yii\helpers\Url::current();
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
                                // 'style' => 'border-bottom: 1px solid #ccc'
                                // 'data-toggle' => 'tooltip'
                                ]
                        );
                    },
                    'publish' => function ($url, $model) use ($canPublish) {
                        $ver = 0;
                        $dataConfirm = null;
                        if ($model['nav_item_type'] == 1) {
                            $ver = $model['nav_item_type_id'];
                        }
                        if (Utility::checkWfRequest($model['id0'], $ver)) {
                            return '';
                        }
                        $url = \yii\helpers\Url::current();
                        $offline = $model['offline'];
                        if ($offline) {
                            $icon = '<span class="mdi mdi-earth"></span>';
                            $pubUrl = ['/' . Module::getModuleName() . '/d1/publication-request', 'nav_id' => $model['nav_id'], 'item_id' => $model['id0'], 'version_id' => $ver, 'url' => $url];
                            $text = Module::txt('Richiedi la pubblicazione della pagina');
                            $textAdmin = Module::txt('Pubblica la pagina');
                            $style = 'color:#297a38;';
                            $dataConfirm = Module::txt('Sei sicuro di voler pubblicare la pagina <strong>{pageName}</strong>?', ['pageName' => $model['title']]);
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
                        if ($canPublish) {
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
                        $url = \yii\helpers\Url::current();
                        return Html::a(
                                '<span class="mdi mdi-cog"></span>',
                                ['/' . Module::getModuleName() . '/d1/update-page', 'id' => $model['nav_id'], 'container' => (\Yii::$app->request->get('container')) ?: 'default', 'url' => $url],
                                [
                                    'title' => Module::txt('Modifica proprietà'),
                                    'class' => 'btn btn-tool-secondary',
                                // 'data-toggle' => 'tooltip'
                                ]
                        );
                    },
                    'update' => function ($url, $model) use ($canPublish) {
                        if ($model['nav_item_type'] == 3) {
                            return '';
                        }
                        $ver = 0;
                        if ($model['nav_item_type'] == 1) {
                            $ver = $model['nav_item_type_id'];
                        }
                        $url = \yii\helpers\Url::current();
                        if ($ver && !$model['offline'] && !$canPublish) {
                            $draft = Utility::getMyDraft($model['id0']);
                            if (!empty($draft)) {
                                return Html::a(
                                                '<span class="mdi mdi-pencil"></span>',
                                                ['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model['nav_id']],
                                                [
                                                    'title' => Module::txt('Modifica la tua ultima versione in bozza'),
                                                    'class' => 'btn btn-tool-secondary',
                                                // 'data-toggle' => 'tooltip'
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
                                                // 'style' => 'color:#a61919',
                                                // 'data-toggle' => 'tooltip'
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
                                // 'data-toggle' => 'tooltip',
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
                    'create' => function ($url, $model) {
                        $url = \yii\helpers\Url::current();
                        return Html::a(
                                '<span class="mdi mdi-plus-box-multiple-outline"></span>',
                                \Yii::$app->urlManager->createUrl(
                                        [
                                            '/' . Module::getModuleName() . '/d1/create-page',
                                            'parent_id' => $model['nav_id'],
                                            'container' => $model['alias']
                                        ]
                                ),
                                [
                                    'title' => Module::txt('Crea pagina figlia'),
                                    'class' => 'btn btn-tool-primary',
                                // 'style' => 'border-bottom: 1px solid #ccc;'
                                ]
                        );
                    },
                    'visibility' => function ($url, $model) {
                        $offline = $model['offline'];
                        if ($offline) {
                            return '';
                        }
                        $url = \yii\helpers\Url::current();

                        if ($model['hidden']) {
                            return Html::a('<span class="mdi mdi-eye"></span>', [
                                        '/' . Module::getModuleName() . '/d1/set-visibility-menu',
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
                            '/' . Module::getModuleName() . '/d1/set-visibility-menu',
                            'id' => $model['nav_id'],
                            'value' => 1,
                            'url' => \Yii::$app->request->url
                                ]
                                , [
                            'title' => Module::txt('Nascondi nel menu'),
                            'class' => 'btn btn-tool-secondary',
                        ]);
                    },
                    'delete' => function ($url, $model) use ($container) {
                        $disabled = false;
                        if (!$model['offline'] && !$canPublish) {
                            $disabled = true;
                        }
                        $children = Utility::getAdminMenuLuya($container, $model['nav_id'], true);
                        /** @var \open20\amos\sondaggi\models\search\SondaggiDomandeSearch $model */
                        if ($children->count() > 0 || $model['offline'] == 0) {
                            $disabled = true;
                        }
                        $url = \yii\helpers\Url::current();
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
        ],
    ]);
    ?>
</div>

