<?php

use yii\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

if (!$landing->isNewRecord) {
    $max = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $landing->video_slider_id])->max("site_management_slider_elem.order");
    $min = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $landing->video_slider_id])->min("site_management_slider_elem.order");
    $eventModel = $model;
?>
    <?=
        Html::a(
            AmosEvents::t('amosevents',  'Aggiungi video'),
            [
                '/sitemanagement/site-management-slider-elem/create',
                'id' => $landing->video_slider_id,
                'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $model->id),
                'useCrop' => true,
                'slider_type' => \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_VIDEO,
                'cropRatio' => 1.7
            ],
            ['class' => 'btn btn-sm btn-secondary mb-3 save-all-data',
//                'data-confirm' => AmosEvents::t('amosevents', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')
]);
    ?>
    <?php
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderSliderElemVideo,
        'columns' => [
            [
                'label' => 'Ordina',
                'value' => function ($model) use ($max, $min, $eventModel) {
                    $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

                    $iconArrowDown = "<use xlink:href=" .  $spriteAsset->baseUrl . "/material-sprite.svg#ic_arrow_downward></use>";
                    $svgIconArrowDown = Html::tag('svg', $iconArrowDown, ['class' => 'icon']);
                    $spanSvgIconArrowDown = Html::tag('span', $svgIconArrowDown, ['class' => 'p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Sposta sotto'), ['class' => 'sr-only']);

                    $iconArrowUp = "<use xlink:href=" .  $spriteAsset->baseUrl . "/material-sprite.svg#ic_arrow_upward></use>";
                    $svgIconArrowUp = Html::tag('svg', $iconArrowUp, ['class' => 'icon']);
                    $spanSvgIconArrowUp = Html::tag('span', $svgIconArrowUp, ['class' => 'p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Sposta sopra'), ['class' => 'sr-only']);
                    $buttons = '';
                    if ($model->order != $min) {
                        $buttons .= Html::a(
                            $spanSvgIconArrowUp,
                            [
                                '/sitemanagement/site-management-slider-elem/order-slider',
                                'id' => $model->id,
                                'slider_id' => $model->slider_id,
                                'direction' => 'up',
                                'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $eventModel->id),
                                'useCrop' => true, 'cropRatio' => '2.2'
                            ],
                            [
                                'class' => 'btn btn-xs btn-icon',
                                'data-toggle' => 'tooltip',
                                'title' => AmosEvents::t('amosevents', 'Sposta sopra')
                            ]
                        );
                    }
                    if ($model->order != $max) {
                        $buttons .= Html::a(
                            $spanSvgIconArrowDown,
                            [
                                '/sitemanagement/site-management-slider-elem/order-slider', 'id' => $model->id,
                                'slider_id' => $model->slider_id, 'direction' => 'down', 'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $eventModel->id)
                            ],
                            [
                                'class' => 'btn btn-xs btn-icon',
                                'data-toggle' => 'tooltip',
                                'title' => AmosEvents::t('amosevents', 'Sposta sotto')
                            ]
                        );
                    }
                    return $buttons;
                },
                'format' => 'raw'
            ],
            'order',

            'title',
            'url_video',
            //            'description',
            [
                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                'controller' => 'site-management-slider-elem',
                'template' => '{update}{delete}',
                'buttons' => [
                    // 'update' => function ($url, $model) use ($landing) {
                    //     return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'),
                    //         [
                    //             '/sitemanagement/site-management-slider-elem/update',
                    //             'id' => $model->id,
                    //             'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $model->id)
                    //         ],
                    //         [
                    //             'class' => 'btn text-secondary'
                    //         ]);
                    // },
                    // 'delete' => function ($url, $model) use ($landing) {
                    //     return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'),
                    //         ['/sitemanagement/site-management-slider-elem/delete', 'id' => $model->id, 'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $model->id)],
                    //         [
                    //             'class' => 'btn text-danger'
                    //         ]);
                    // }
                    'update' => function ($url, $model) use ($landing, $eventModel) {
                        $url = ['/sitemanagement/site-management-slider-elem/update', 'id' => $model->id, 'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $eventModel->id)];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconModifica = "<use xlink:href=" .  $spriteAsset->baseUrl . "/material-sprite.svg#ic_edit></use>";
                        $svgIconModifica = Html::tag('svg', $iconModifica, ['class' => 'icon icon-white']);
                        $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Modifica'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Modifica'], true);
                    },
                    'delete' => function ($url, $model) use ($landing, $eventModel) {
                        $url = ['/sitemanagement/site-management-slider-elem/delete', 'id' => $model->id, 'urlRedirect' => urlencode('/events/event-dashboard/landing-manage-contents?id=' . $eventModel->id)];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconDelete = "<use xlink:href=" .  $spriteAsset->baseUrl . "/material-sprite.svg#ic_close></use>";
                        $svgIconDelete = Html::tag('svg', $iconDelete, ['class' => 'icon icon-white']);
                        $spanSvgIconDelete = Html::tag('span', $svgIconDelete, ['class' => 'rounded-icon rounded-danger p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Elimina'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconDelete, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Elimina'], true);
                    }
                ]
            ]
        ]
    ]);
    ?>
    <?php
} else {
    echo "<p>" . \open20\amos\events\AmosEvents::t(
        'amosevents',
        "E' necessario salvare per poter inserire degli lementi allo slider."
    ) . "</p>";
}
    ?>


