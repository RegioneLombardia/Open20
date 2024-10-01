<?php

use yii\grid\GridView;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\icons\AmosIcons;
use open20\amos\utility\Module;

$this->title = \open20\amos\utility\Module::t('luyaconfigs', "Esporta pagina");
\Yii::$app->view->params['titleSection'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;


echo $this->render('_search', ['model' => $modelSearch]);

echo AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'nav_id',
        [
            'value' => function ($model) {
                $count = \luya\cms\models\NavItem::find()->andWhere(['nav_id' => $model->nav_id])->count();
                if ($count > 1) {
                    $lang = $model->lang;
                    if ($lang) {

                        return \yii\helpers\Html::a(AmosIcons::show('translate') . $lang->short_code,
                            ['/utility/export-luya/export-page', "NavItemSearch[nav_id]" => $model->nav_id], [
                                'class' => 'btn btn-secondary',
                                'style' => "padding: 2px 2px;",
                                'title' => Module::t('luyaconfigs', "Vedi le versioni in multilingua")
                            ]);
                    }
                }
                return '';
            },
            'format' => 'raw',
            'label' => Module::t('luyaconfigs', "Multilingua")
        ],
//        [
//            'value' => function ($model) {
//                $lang = $model->lang;
//                if ($lang) {
//                    return $lang->short_code;
//                }
//                return '';
//            },
//            'label' => Module::t('luyaconfigs', "Lingua")
//        ],

        'title',
        'alias',
        [
            'attribute' => 'timestamp_create',
            'value' => function ($model) {
                return date('d/m/Y', $model->timestamp_create);
            },
            'label' => Module::t('luyaconfigs', "Creata il")
        ],
        [
            'attribute' => 'nav.navContainer.name',
            'label' => Module::t('luyaconfigs', "Menu")
        ],
        [

//            'visible' => $this->eventModel->enable_companions,
            'class' => 'kartik\grid\ExpandRowColumn',
            'expandAllTitle' => 'Versioni',
            'allowBatchToggle' => false,
            'enableCache' => false,
            'expandIcon' => "<span class=\"am am-plus\"></span>",
            'collapseIcon' => Module::t('luyaconfigs', 'Chiudi'),
            'header' => Module::t("amosevents", "Versioni"),//Module::t('amosproject_management', 'Expand / Collapse'),
            'headerOptions' => [
                'style' => 'white-space: nowrap;'
            ],
            'contentOptions' => [
                'class' => 'text-center'
            ],
//            'detailRowCssClass' => '',

            'value' => function ($model, $key, $index, $column) {
                return \kartik\grid\GridView::ROW_COLLAPSED;
            },
            'detailUrl' => \yii\helpers\Url::to(['/utility/export-luya/page-versions'])
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{export-all}{export}{preview}{update}',
            'buttons' => [
                'export-all' => function ($url, $model) {
                    $count = \luya\cms\models\NavItem::find()->andWhere(['nav_id' => $model->nav_id])->count();
                    if ($count > 1) {
                        $icon ="<span class='mdi mdi-export'></span>". AmosIcons::show('translate');
                        $url = ['/utility/export-luya/export-page', 'nav_id' => $model->nav_id];

                        return \yii\helpers\Html::a($icon, $url, [
                            'class' => 'btn btn-tools-secondary',
                            'title' => \open20\amos\utility\Module::t('luyaconfigs', "Esporta pagina con versione pubblicata di tutte le lingue"),
                            'data-confirm' => Module::t('luyaconfigs', "Sei sicuro di esportare la pagina <strong>{title}</strong>?<br>Potrai importala soltanto com nua nuova pagina.", ['title' => $model->title])
                        ]);
                    }
                    return '';
                },
                'export' => function ($url, $model) {
                    $url = ['/utility/export-luya/export-page', 'nav_item_id' => $model->id, 'nav_id' => $model->nav_id];
                    return \yii\helpers\Html::a("<span class='mdi mdi-export'></span>",$url , [
                        'class' => 'btn btn-tools-secondary',
                        'title' => \open20\amos\utility\Module::t('luyaconfigs', "Esporta pagina con versione pubblicata"),
                        'data-confirm' => Module::t('luyaconfigs', "Sei sicuro di esportare la pagina <strong>{title}</strong>?", ['title' => $model->title])
                    ]);
                },
                'preview' => function ($url, $model) {
                    return \yii\helpers\Html::a('<span class="mdi mdi-eye"></span>', ['/cms-page-preview', 'itemId' => $model->id, 'version' => $model->nav_item_type_id], [
                        'class' => 'btn btn-tools-secondary',
                        'target' => '_blank',
                        'title' => \open20\amos\utility\Module::t('luyaconfigs', "Anteprima pagina"),
                    ]);
                },
                'update' => function ($url, $model) {
                    return \yii\helpers\Html::a('<span class="mdi mdi-pencil"></span>', ['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model->nav_id], [
                        'class' => 'btn btn-tools-secondary',
                        'target' => '_blank',
                        'title' => \open20\amos\utility\Module::t('luyaconfigs', "Aggiorna pagina"),
                    ]);
                }
            ]
        ],
    ]
]);
