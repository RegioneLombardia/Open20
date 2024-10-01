<?php

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\cwh\AmosCwh;
use open20\amos\core\utilities\ModalUtility;

/**
 *
 * @var $this \yii\web\View
 * @var $contentsDataProvider \yii\data\ArrayDataProvider
 * @var $networksDataProvider \yii\data\ArrayDataProvider
 * @var $lastProcessDateTime \DateTime
 *
 */

$this->title = AmosCwh::t('amoscwh', '#cwh_wizard_title{appName}', [
    'appName' => Yii::$app->name
]);

\open20\amos\layout\assets\SpinnerWaitAsset::register($this);

$deleteConfirmMsg = AmosCwh::t('amoscwh', '#delete_confirm_popup');

?>


<div class="">
    <?php
    \yii\bootstrap\Alert::begin([
        'closeButton' => false,
        'options' => [
            'class' => 'alert alert-info',
        ],
    ]);
    ?>
    <p><?= AmosCwh::t('amoscwh', '#cwh_wizard_introduction') ?> </p>
    <p><?= AmosCwh::t('amoscwh', '#cwh_wizard_introduction_2') ?>
    </p>
    <p><?= AmosCwh::t('amoscwh', '#cwh_wizard_introduction_3{lastProcessDateTime}{appName}',
            [
                'lastProcessDateTime' => $lastProcessDateTime,
                'appName' => Yii::$app->name,
            ]
        ) ?>
    </p>
    <?php
    \yii\bootstrap\Alert::end();
    ?>

    <div class="loading" id="loader" hidden></div>

    <div class="row">
        <div class="col-xs-12">
            <h2><?= AmosCwh::t('amoscwh', '#cwh_wizard_section_network_title') ?></h2>
            <h4><?= AmosCwh::t('amoscwh', '#cwh_wizard_section_network_description') ?></h4>
            <?= \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $networksDataProvider,
//                'rowOptions' => function ($model, $key, $index, $grid) {
//                    if ($model->isConfigured()) {
//                        return ['class' => 'alert alert-success'];
//                    }
//                    return [];
//                },
                'columns' => [
                    /*
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return [
                                'id' => \yii\helpers\StringHelper::basename($model['classname']),
                                'value' => $model['classname'],
                                'checked' => $model->isConfigured()
                            ];
                        },
                    ],

                    'classname',
                     'base_url_config',
                    'config_class',
                    */
                    'label',
                    'module_id',
                    'configured' => [
                        'attribute' => 'configured',
                        'format' => 'boolean',
                        'value' => function ($model) {
                            return $model->isConfigured();
                        }
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => AmosCwh::t('amoscwh', '#manage_config'),
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a(
                                    \open20\amos\core\icons\AmosIcons::show('edit', [
                                        'alt' => AmosCwh::t('amoscwh', '#edit_config')
                                    ]),
                                    $model->composeUrl()
                                );
                            },
                        ],

                    ],
                ]

            ]) ?>


        </div>

        <div class="col-xs-12">
            <h2><?= AmosCwh::t('amoscwh', '#cwh_nodi_regeneration_title') ?></h2>
            <h4><?= AmosCwh::t('amoscwh', '#cwh_nodi_regeneration') ?></h4>
            <?= Html::a(AmosCwh::t('amoscwh', '#cwh_nodi_regeneration_btn'), '/cwh/configuration/wizard?regenerateView=1', ['class' => 'btn btn-primary']) ?>
        </div>

        <div class="col-xs-12">
            <h2><?= AmosCwh::t('amoscwh', '#cwh_wizard_section_content_title') ?></h2>
            <h4><?= AmosCwh::t('amoscwh', '#cwh_wizard_section_content_description') ?></h4>

            <?= \open20\amos\core\views\AmosGridView::widget([
                'dataProvider' => $contentsDataProvider,
//                'rowOptions' => function ($model, $key, $index, $grid) {
//                    if ($model->isConfigured()) {
//                        return ['class' => 'alert alert-success'];
//                    }
//                    return [];
//                },
                'columns' => [
                    //'classname',
                    'label',
                    'tablename',
                    'module_id',
                    //'base_url_config',
                    //'config_class',
                    'configured' => [
                        'attribute' => 'configured',
                        'format' => 'boolean',
                        'value' => function ($model) {
                            return $model->isConfigured();
                        }
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'header' => AmosCwh::t('amoscwh', '#manage_config'),
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                $icon = 'edit';
                                $title = AmosCwh::t('amoscwh', '#edit_config');
                                if (!$model->isConfigured()) {
                                    $icon = 'plus';
                                    $title = AmosCwh::t('amoscwh', '#new_config');
                                }
                                return Html::a(
                                    AmosIcons::show($icon),
                                    $model->composeUrl(),
                                    ['class' => 'btn btn-tool-secondary', 'title' => $title]
                                );
                            },
                            'delete' => function ($url, $model, $key) use ($deleteConfirmMsg){
                                $btn = '';
                                if ($model->tablename && $model->isConfigured()) {
                                    ModalUtility::createConfirmModal([
                                        'id' => 'delete-config-' . $model->tablename,
                                        'modalDescriptionText' => $deleteConfirmMsg,
                                        'confirmBtnLink' => '/cwh/configuration/delete-content?tablename=' . $model->tablename,
                                        'confirmBtnOptions' => ['class' => 'btn btn-primary', 'onclick' => "$('.loading').show();"]
                                    ]);
                                    $btn = Html::a(AmosIcons::show('delete'),
                                        null,
                                        [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#delete-config-'.$model->tablename,
                                            'class' => 'btn btn-danger-inverse delete-config',
                                            'title' => AmosCwh::t('amoscwh', '#delete_config')
                                        ]
                                    );
                                }
                                return $btn;
                            },
                        ],

                    ],
                ]
            ]) ?>

        </div>
    </div>

    <div class="row">
        <?php $form = \open20\amos\core\forms\ActiveForm::begin([

        ]); ?>

        <div class="bk-btnFormContainer col-sm-12">
            <?= \open20\amos\core\helpers\Html::a(AmosCwh::t('amoscwh', 'Chiudi'),
                Yii::$app->urlManager->createUrl('dashboard'), [
                    'class' => 'btn btn-secondary pull-left',
                    'name' => 'close',
                ]) ?>
            <?= \open20\amos\core\helpers\Html::submitButton(AmosCwh::t('amoscwh', 'Salva'), [
                'class' => 'btn btn-primary pull-right',
                'name' => 'save_config',
            ]) ?>
            <?= \open20\amos\core\helpers\Html::submitButton(AmosCwh::t('amoscwh', 'Ricarica'), [
                'class' => 'btn btn-danger pull-right',
                'name' => 'delete_cache',
            ]) ?>
        </div>
        <?php \open20\amos\core\forms\ActiveForm::end() ?>
    </div>
</div>

