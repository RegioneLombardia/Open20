<?php

use amos\sitemanagement\Module;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var amos\sitemanagement\models\SiteManagementTemplate $model
 */

$this->title = Module::t('amossitemanagement', 'Slider Video');
$this->params['breadcrumbs'][] = $this->title;

$max = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->id])->max("site_management_slider_elem.order");
$min = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->id])->min("site_management_slider_elem.order");
?>


<div class="col-xs-12">
        <?=
            Html::a(
                \Yii::t('amossitemanagement', 'Add video'),
                [
                    '/sitemanagement/site-management-slider-elem/create', 'id' => $model->id, 'urlRedirect' => urlencode('/sitemanagement/site-management-community-slider-elements/videos'),
                    'useCrop' => true, 'cropRatio' => 1.7, 'onlyVideos' => true
                ],
                ['class' => 'btn btn-navigation-primary' ]
            );
        ?>
        <?php
        $gridColumns = [
            [
                'label' => 'Ordina',
                'value' => function ($model) use ($max, $min, $esperienzaModel) {
                    $buttons = '';
                    if ($model->order != $min) {
                        $buttons .= Html::a(
                            \open20\amos\core\icons\AmosIcons::show('long-arrow-up'),
                            [
                                '/sitemanagement/site-management-slider-elem/order-slider', 'id' => $model->id,
                                'slider_id' => $model->slider_id, 'direction' => 'up', 'urlRedirect' => urlencode('/sitemanagement/site-management-community-slider-elements/videos'),
                                'useCrop' => true, 'cropRatio' => '2.2'
                            ],
                            ['class' => 'btn btn-tools-secondary']
                        );
                    }
                    if ($model->order != $max) {
                        $buttons .= Html::a(
                            \open20\amos\core\icons\AmosIcons::show('long-arrow-down'),
                            [
                                '/sitemanagement/site-management-slider-elem/order-slider', 'id' => $model->id,
                                'slider_id' => $model->slider_id, 'direction' => 'down', 'urlRedirect' => urlencode('/sitemanagement/site-management-community-slider-elements/videos')
                            ],
                            ['class' => 'btn btn-tools-secondary']
                        );
                    }
                    return $buttons;
                },
                'format' => 'raw'
            ],
            'order',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->getLabelType($model->type);
                },
            ],
            'title',
            'description',
            [
                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                'controller' => 'site-management-slider-elem',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            \open20\amos\core\icons\AmosIcons::show('edit'),
                            [
                                '/sitemanagement/site-management-slider-elem/update',
                                'id' => $model->id,
                                'urlRedirect' => urlencode('/sitemanagement/site-management-community-slider-elements/videos'),
                                'onlyVideos' => true
                            ],
                            [
                                'class' => 'btn btn-navigation-primary'
                            ]
                        );
                    },
                    'view' => function ($url, $model) {
                        return Html::a(
                            \open20\amos\core\icons\AmosIcons::show('file'),
                            ['/sitemanagement/site-management-slider-elem/view', 'id' => $model->id, 'urlRedirect' => urlencode('/sitemanagement/site-management-community-slider-elements/videos')],
                            [
                                'class' => 'btn btn-navigation-primary'
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            \open20\amos\core\icons\AmosIcons::show('delete'),
                            ['/sitemanagement/site-management-slider-elem/delete', 'id' => $model->id, 'urlRedirect' => urlencode('/sitemanagement/site-management-community-slider-elements/videos')],
                            [
                                'class' => 'btn btn-danger-inverse'
                            ]
                        );
                    }
                ]
            ]
        ];

        if ($readOnly) {
            array_pop($gridColumns);
        }

        echo \open20\amos\core\views\AmosGridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns
        ]);
        ?>


</div>