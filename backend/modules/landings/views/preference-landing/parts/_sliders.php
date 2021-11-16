<?php

use yii\helpers\Html;
use backend\modules\landings\Module;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;




if (!$model->isNewRecord) {
    $max = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->image_slider_id])->max("site_management_slider_elem.order");
    $min = \amos\sitemanagement\models\SiteManagementSliderElem::find()->andWhere(['slider_id' => $model->image_slider_id])->min("site_management_slider_elem.order");
    $landingModel = $model;
    ?>
    <?=
    Html::a(
        Module::t('preferencelandings', 'Aggiungi immagine'),
        [
            '/sitemanagement/site-management-slider-elem/create',
            'id' => $model->image_slider_id,
            'urlRedirect' => urlencode('/landings/preference-landing/update?id=' . $model->id),
            'useCrop' => true,
            'slider_type' => \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_IMG,
            'cropRatio' => 1.7
        ],
        ['class' => 'btn btn-sm btn-secondary mb-3 save-all-data',
//            'data-confirm' => Module::t('preferencelandings', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')
        ]
    );
    ?>
    <?php
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderSliderElemImage,
        'columns' => [
            [
                'label' => 'Ordina',
                'value' => function ($model) use ($max, $min, $landingModel) {
                    $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

                    $iconArrowDown = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_arrow_downward></use>";
                    $svgIconArrowDown = Html::tag('svg', $iconArrowDown, ['class' => 'icon']);
                    $spanSvgIconArrowDown = Html::tag('span', $svgIconArrowDown, ['class' => 'p-1']) . Html::tag('span', Module::t('preferencelandings', 'Sposta sotto'), ['class' => 'sr-only']);

                    $iconArrowUp = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_arrow_upward></use>";
                    $svgIconArrowUp = Html::tag('svg', $iconArrowUp, ['class' => 'icon']);
                    $spanSvgIconArrowUp = Html::tag('span', $svgIconArrowUp, ['class' => 'p-1']) . Html::tag('span', Module::t('preferencelandings', 'Sposta sopra'), ['class' => 'sr-only']);

                    $buttons = '';
                    if ($model->order != $min) {
                        $buttons .= Html::a(
                            $spanSvgIconArrowUp,
                            [
                                '/sitemanagement/site-management-slider-elem/order-slider',
                                'id' => $model->id,
                                'slider_id' => $model->slider_id,
                                'direction' => 'up',
                                'urlRedirect' => urlencode('/landings/preference-landing/update?id=' . $landingModel->id),
                                'useCrop' => true, 'cropRatio' => '2.2'
                            ],
                            [
                                'class' => 'btn btn-xs btn-icon',
                                'data-toggle' => 'tooltip',
                                'title' => Module::t('preferencelandings', 'Sposta sopra')
                            ]
                        );
                    }
                    if ($model->order != $max) {
                        $buttons .= Html::a(
                            $spanSvgIconArrowDown,
                            [
                                '/sitemanagement/site-management-slider-elem/order-slider', 'id' => $model->id,
                                'slider_id' => $model->slider_id, 'direction' => 'down', 'urlRedirect' => urlencode('/landings/preference-landing/update?id=' . $landingModel->id)
                            ],
                            [
                                'class' => 'btn btn-xs btn-icon',
                                'data-toggle' => 'tooltip',
                                'title' => Module::t('preferencelandings', 'Sposta sotto')
                            ]
                        );
                    }
                    return $buttons;
                },
                'format' => 'raw'
            ],
            'order',
            'title' => [
                'attribute' => 'title',
                'label' => Module::t('preferencelandings', 'Titolo'),
            ],
            //            'immagine' => [
            //                'label' => Module::t('preferencelandings', 'Immagine'),
            //                'format' => 'html',
            //                'value' => function ($model) {
            //                    /** @var News $model */
            //                    $url = '/img/img_default.jpg';
            //                    if (!is_null($model->fileImage)) {
            //                        $url = $model->fileImage->getWebUrl('table_small', false, true);
            //                    }
            //                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => AmosEvents::t('amosnews', 'Immagine della notizia')]);
            //
            //                    return Html::a($contentImage, []);
            //                }
            //            ],
            //            'description',
            [
                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                'controller' => 'site-management-slider-elem',
                'template' => '{update}{delete}',
                'buttons' => [

                    'update' => function ($url, $model) use ($landingModel) {
                        $url = ['/sitemanagement/site-management-slider-elem/update', 'id' => $model->id, 'urlRedirect' => urlencode('/landings/preference-landing/update?id=' . $landingModel->id)];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconModifica = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_edit></use>";
                        $svgIconModifica = Html::tag('svg', $iconModifica, ['class' => 'icon icon-white']);
                        $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', Module::t('preferencelandings', 'Modifica'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Modifica'], true);
                    },
                    'delete' => function ($url, $model) use ( $landingModel) {
                        $url = ['/sitemanagement/site-management-slider-elem/delete', 'id' => $model->id, 'urlRedirect' => urlencode('/landings/preference-landing/update?id=' . $landingModel->id)];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconDelete = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_close></use>";
                        $svgIconDelete = Html::tag('svg', $iconDelete, ['class' => 'icon icon-white']);
                        $spanSvgIconDelete = Html::tag('span', $svgIconDelete, ['class' => 'rounded-icon rounded-danger p-1']) . Html::tag('span', Module::t('preferencelandings', 'Elimina'), ['class' => 'sr-only']);
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




