<?php

use backend\modules\landings\models\PreferenceLandingDocumenti;
use open20\amos\events\AmosEvents;
use backend\modules\landings\Module;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\db\ActiveQuery;
use yii\helpers\Html;

$landingModel = $model;
echo Html::a(\Yii::t('amosevents', 'Aggiungi Allegato'),
    ['/documenti/documenti/create', 'urlRedirect' => '/landings/preference-landing/update?id=' . $model->id],
    ['class' => 'btn btn-sm btn-secondary mb-3 save-all-data',
//            'data-confirm' => \Yii::t('amosevents','Stai per lasciare la pagina, assicurarsi di aver salvato dati. Proseguire?')
    ]);
?>
<?php
$query =  PreferenceLandingDocumenti::find()
    ->joinWith('documenti')
    ->andWhere(['preference_landing_id' => $landingModel->id])
;
$max = $query->max("sort_value");
$min = $query->min("sort_value");

echo \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProviderDocumenti,
    'columns' => [
        [
            'label' => 'Ordina',
            'value' => function ($model) use ($max, $min, $landingModel) {
                $landingDoc = PreferenceLandingDocumenti::findOne(['documenti_id' => $model->id]);
                $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

                $iconArrowDown = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_arrow_downward></use>";
                $svgIconArrowDown = Html::tag('svg', $iconArrowDown, ['class' => 'icon']);
                $spanSvgIconArrowDown = Html::tag('span', $svgIconArrowDown, ['class' => 'p-1']) . Html::tag('span', Module::t('preferencelandings', 'Sposta sotto'), ['class' => 'sr-only']);

                $iconArrowUp = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_arrow_upward></use>";
                $svgIconArrowUp = Html::tag('svg', $iconArrowUp, ['class' => 'icon']);
                $spanSvgIconArrowUp = Html::tag('span', $svgIconArrowUp, ['class' => 'p-1']) . Html::tag('span', Module::t('preferencelandings', 'Sposta sopra'), ['class' => 'sr-only']);

                $buttons = '';
                if ((!empty($landingDoc)) && $landingDoc->sort_value != $min) {
                    $buttons .= Html::a(
                        $spanSvgIconArrowUp,
                        [
                            '/landings/preference-landing/order-documents',
                            'id' => $model->id,
                            'landingId' => $landingModel->id,
                            'direction' => 'up',
                        ],
                        [
                            'class' => 'btn btn-xs btn-icon',
                            'data-toggle' => 'tooltip',
                            'title' => \backend\modules\landings\Module::t('preferencelandings', 'Sposta sopra')
                        ]
                    );
                }
                if ((!empty($landingDoc)) && $landingDoc->sort_value != $max) {
                    $buttons .= Html::a(
                        $spanSvgIconArrowDown,
                        [
                            '/landings/preference-landing/order-documents',
                            'id' => $model->id,
                            'landingId' => $landingModel->id,
                            'direction' => 'down',
                        ],
                        [
                            'class' => 'btn btn-xs btn-icon',
                            'data-toggle' => 'tooltip',
                            'title' => \backend\modules\landings\Module::t('preferencelandings', 'Sposta sotto')
                        ]
                    );
                }
                return $buttons;
            },
            'format' => 'raw'
        ],
        [
            'label' => \open20\amos\documenti\AmosDocumenti::t('amosdocumenti', 'Filename'),
            'value' => function ($model) {
                /** @var \open20\amos\documenti\models\Documenti $model */
                return $model->documentMainFile->name . '.' . $model->documentMainFile->type;
            }
        ],
        [
            'attribute' => 'titolo',
            'enableSorting' => false
        ],
        'created_by' => [
            'attribute' => 'createdUserProfile',
            'label' => AmosEvents::t('amosnews', 'Pubblicato Da'),
            'value' => function ($model) {
                // return Html::a($model->createdUserProfile->nomeCognome, ['/admin/user-profile/view', 'id' => $model->createdUserProfile->id], [
                //     'title' => AmosEvents::t('amosnews', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->createdUserProfile->nomeCognome])
                // ]);
                return $model->createdUserProfile->nomeCognome;
            },
            'format' => 'html'
        ],
        'status' => [
            'attribute' => 'status',
            'enableSorting' => false,
            'value' => function ($model) {
                /** @var News $model */
                return $model->hasWorkflowStatus() ? AmosEvents::t('amosnews', $model->getWorkflowStatus()->getLabel()) : '--';
            }
        ],
        [
            'class' => \open20\amos\core\views\grid\ActionColumn::className(),
            'buttons' => [
                // 'update' => function($url, $model)use ($eventModel){
                //     return Html::a(\open20\amos\core\icons\AmosIcons::show('edit'), [
                //         '/news/news/update','id' => $model->id, 'urlRedirect' => '/events/event-dashboard/landing-manage-contents?id='.$eventModel->id
                //     ], ['class' => 'btn btn-primary']);
                // },
                // 'view' => function($url, $model) use ($eventModel){
                //     return Html::a(\open20\amos\core\icons\AmosIcons::show('file'), [
                //         '/news/news/view','id' => $model->id, 'urlRedirect' => '/events/event-dashboard/landing-manage-contents?id='.$eventModel->id
                //     ], ['class' => 'btn btn-primary']);
                // },
                // 'delete' => function($url, $model)use ($eventModel){
                //     return Html::a(\open20\amos\core\icons\AmosIcons::show('delete'), [
                //         '/news/news/delete','id' => $model->id, 'urlRedirect' => '/events/event-dashboard/landing-manage-contents?id='.$eventModel->id
                //     ], ['class' => 'btn btn-primary']);
                // }
                'update' => function ($url, $model) use ($landingModel) {
                    $url = ['/documenti/documenti/update', 'id' => $model->id, 'urlRedirect' => '/landings/preference-landing/update?id=' . $landingModel->id];
                    $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                    $iconModifica = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_edit></use>";
                    $svgIconModifica = Html::tag('svg', $iconModifica, ['class' => 'icon icon-white']);
                    $spanSvgIconModifica = Html::tag('span', $svgIconModifica, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Modifica'), ['class' => 'sr-only']);
                    return Html::a($spanSvgIconModifica, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Modifica'], true);
                },
                'view' => function ($url, $model) use ($landingModel) {
                    $url = ['/documenti/documenti/view', 'id' => $model->id, 'urlRedirect' => '/landings/preference-landing/update?id=' . $landingModel->id];
                    $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                    $iconView = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_visibility></use>";
                    $svgiconView = Html::tag('svg', $iconView, ['class' => 'icon icon-white']);
                    $spanSvgiconView = Html::tag('span', $svgiconView, ['class' => 'rounded-icon rounded-secondary p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Visualizza'), ['class' => 'sr-only']);
                    return Html::a($spanSvgiconView, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Visualizza'], true);
                },
                'delete' => function ($url, $model) use ($landingModel) {
                    $url = ['/documenti/documenti/delete', 'id' => $model->id, 'urlRedirect' => '/landings/preference-landing/update?id=' . $landingModel->id];
                    $spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);
                    $iconDelete = "<use xlink:href=" . $spriteAsset->baseUrl . "/material-sprite.svg#ic_close></use>";
                    $svgIconDelete = Html::tag('svg', $iconDelete, ['class' => 'icon icon-white']);
                    $spanSvgIconDelete = Html::tag('span', $svgIconDelete, ['class' => 'rounded-icon rounded-danger p-1']) . Html::tag('span', AmosEvents::t('amosevents', 'Elimina'), ['class' => 'sr-only']);
                    return Html::a($spanSvgIconDelete, $url, ['class' => 'btn btn-xs btn-icon', 'data-toggle' => 'tooltip', 'title' => 'Elimina'], true);
                }
            ]
        ]

    ]
]);
?>