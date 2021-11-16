<?php

use yii\helpers\Html;
use backend\modules\landings\Module;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;

/**
 * @var \backend\modules\landings\models\PreferenceLanding $model
 * @var \yii\data\ActiveDataProvider $dataProviderLinks
 */



if (!$model->isNewRecord) {
    $query  = \backend\modules\landings\models\PreferenceLandingLink::find()->andWhere(['preference_landing_id' => $model->id]);

    $max = $query->max("sort_value");
    $min = $query->min("sort_value");

    $landingModel = $model;
    ?>
    <?=
    Html::a(
        Module::t('preferencelandings', 'Aggiungi link'),
        [
            '/landings/preference-landing-links/create',
            'id' => $model->id,
        ],
        [
            'class' => 'btn btn-sm btn-secondary mb-3 save-all-data',
//            'data-confirm' => Module::t('preferencelandings', 'Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')
        ]
    );
    ?>
    <?php
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProviderLinks,
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
                    if ($model->sort_value != $min) {
                        $buttons .= Html::a(
                            $spanSvgIconArrowUp,
                            [
                                '/landings/preference-landing-links/sort-links',
                                'id' => $model->id,
                                'direction' => 'up',
                            ],
                            [
                                'class' => 'btn btn-xs btn-icon',
                                'data-toggle' => 'tooltip',
                                'title' => Module::t('preferencelandings', 'Sposta sopra')
                            ]
                        );
                    }
                    if ($model->sort_value != $max) {
                        $buttons .= Html::a(
                            $spanSvgIconArrowDown,
                            [
                                '/landings/preference-landing-links/sort-links',
                                'id' => $model->id,
                                'direction' => 'down',
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
            'title' => [
                'attribute' => 'title',
                'label' => Module::t('preferencelandings', 'Titolo'),
                'enableSorting' => false
            ],
            'url' => [
                'attribute' => 'url',
                'label' => Module::t('preferencelandings', 'URL'),
                'enableSorting' => false
            ],
            [
                'class' => \open20\amos\core\views\grid\ActionColumn::className(),
                'controller' => 'site-management-slider-elem',
                'template' => '{link}{view}{update}{delete}',
                'buttons' => [
                    'link' => function ($url, $model) use ($landingModel) {
                        $url = $model->url;
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $icon = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_open_in_new></use>";
                        $svgIconModifica = Html::tag('svg', $icon, ['class' => 'icon icon-white']);
                        $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', Module::t('preferencelandings', 'Link'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Link', 'target' => '_blank'], true);
                    },
                    'view' => function ($url, $model) use ($landingModel) {
                        $url = ['/landings/preference-landing-links/view', 'id' => $model->id];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconModifica = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_visibility></use>";
                        $svgIconModifica = Html::tag('svg', $iconModifica, ['class' => 'icon icon-white']);
                        $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', Module::t('preferencelandings', 'Visualizza'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Visualizza'], true);
                    },
                    'update' => function ($url, $model) use ($landingModel) {
                        $url = ['/landings/preference-landing-links/update', 'id' => $model->id];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconModifica = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_edit></use>";
                        $svgIconModifica = Html::tag('svg', $iconModifica, ['class' => 'icon icon-white']);
                        $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', Module::t('preferencelandings', 'Modifica'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Modifica'], true);
                    },
                    'delete' => function ($url, $model) use ( $landingModel) {
                        $url = ['/landings/preference-landing-links/delete', 'id' => $model->id];
                        $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                        $iconDelete = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_close></use>";
                        $svgIconDelete = Html::tag('svg', $iconDelete, ['class' => 'icon icon-white']);
                        $spanSvgIconDelete = Html::tag('span', $svgIconDelete, ['class' => 'rounded-icon rounded-danger p-1']) . Html::tag('span', Module::t('preferencelandings', 'Elimina'), ['class' => 'sr-only']);
                        return Html::a($spanSvgIconDelete,
                            $url,
                            [
                                'class' => 'btn btn-xs btn-icon',
                                'data-toggle' => 'tooltip',
                                'title' => 'Elimina',
                                'data-confirm' => 'Sei sicuro di voler elimare?',
                            ],
                            true);
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




