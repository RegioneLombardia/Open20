<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\views\DataProviderView;
use open20\amos\dashboard\AmosDashboard;
use yii\helpers\Html;

/* * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard * */
/* * @var \open20\amos\dashboard\models\AmosWidgets $widgetIconSelectable * */
/* * @var \open20\amos\dashboard\models\AmosWidgets $widgetGraphicSelectable * */
/* * @var array $widgetSelected * */

/* * @var \yii\web\View $this * */
// AmosIcons::map($this);
$this->params['widgetSelected'] = $widgetSelected;
$this->params['checkedByDefault'] = true;


if (!empty(\Yii::$app->params['bsVersion']) && \Yii::$app->params['bsVersion'] == '4.x') : ?>

    <div class="dashboard-default-index">

        <div class="my-4">
            <!--<h2>< ?= AmosDashboard::tHtml('amosdashboard', 'Plugins'); ?></h2>-->
            <?= Html::tag('h5', AmosDashboard::t('amosdashboard', 'Plugins'), ['class' => 'subtitle-form font-weight-bold']) ?>
        </div>

        <div class="plugin-list dashboard-content pb-5">
            <?= \open20\amos\core\views\ListView::widget(
                [
                    'dataProvider' => $providerIcon,
                    'itemOptions' => ['class' => 'border-bottom border-white'],
                    'itemView' => '@vendor/open20/amos-community/src/views/configure-dashboard/_listPlugin',
                ]
            ) ?>

            <div class="mt-4">
                <!--<h2>< ?= AmosDashboard::tHtml('amosdashboard', 'Widget'); ?></h2>-->
                <?= Html::tag('h5', AmosDashboard::t('amosdashboard', 'Widget'), ['class' => 'subtitle-form font-weight-bold']) ?>
            </div>

            <?=
                AmosGridView::widget([
                    'dataProvider' => $providerGraphic,
                    'summary' => false,
                    'columns' => [
                        [
                            'class' => 'open20\amos\core\views\grid\CheckboxColumn',
                            'name' => 'amosWidgetsIds[]',
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                return [
                                    'id' => \yii\helpers\StringHelper::basename($model['classname']),
                                    'value' => $model['id'],
                                    'checked' => empty($this->params['widgetSelected']) ? true : in_array($model['id'], $this->params['widgetSelected'])
                                ];
                            }
                        ],
                        [
                            'label' => 'Nome',
                            'format' => 'html',
                            'attribute' => 'classname',
                            'value' => function ($model) {
                                $object = \Yii::createObject($model['classname']);
                                return $object->getLabel();
                            }
                        ],
                        [
                            'label' => 'Descrizione',
                            'value' => function ($model) {
                                $object = \Yii::createObject($model['classname']);
                                return $object->getDescription();
                            }
                        ],
                    ]
                ]);
            ?>
        </div>
    </div>

<?php else : ?>

    <div class="dashboard-default-index">

        <div class="mt-4">
            <!--<h2>< ?= AmosDashboard::tHtml('amosdashboard', 'Widget'); ?></h2>-->
            <?= Html::tag('h5', AmosDashboard::t('amosdashboard', 'Widget'), ['class' => 'subtitle-form font-weight-bold']) ?>
        </div>

        <div class="plugin-list dashboard-content">
            <?= \open20\amos\core\views\ListView::widget(
                [
                    'dataProvider' => $providerIcon,
                    'itemOptions' => ['class' => 'col-xs-12 col-sm-6 col-md-2 col-lg-2 flex-column-item'],
                    'itemView' => '@vendor/open20/amos-community/src/views/configure-dashboard/_icon',
                ]
            ) ?>

            <div class="col-xs-12">
                <!--<h2>< ?= AmosDashboard::tHtml('amosdashboard', 'Widget'); ?></h2>-->
                <?= Html::tag('h2', AmosDashboard::t('amosdashboard', 'Widget'), ['class' => 'subtitle-form']) ?>
            </div>


            <?=
                AmosGridView::widget([
                    'dataProvider' => $providerGraphic,
                    'summary' => false,
                    'columns' => [
                        //                [
                        //                    'attribute' => 'module',
                        //                    'label' => 'Plugin',
                        //                ],
                        [
                            'class' => 'open20\amos\core\views\grid\CheckboxColumn',
                            'name' => 'amosWidgetsIds[]',
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                return [
                                    'id' => \yii\helpers\StringHelper::basename($model['classname']),
                                    'value' => $model['id'],
                                    'checked' => empty($this->params['widgetSelected']) ? true : in_array($model['id'], $this->params['widgetSelected'])
                                ];
                            }
                        ],
                        [
                            'label' => 'Icona',
                            'contentOptions' => ['class' => 'icona'],
                            'format' => 'html',
                            'value' => function ($model) {
                                $backgrounColor = 'color-border-mediumBase';
                                return '<p class="' . $backgrounColor . '">' . AmosIcons::show('view-web') . '</p>';
                            }
                        ],
                        [
                            'label' => 'Nome',
                            'format' => 'html',
                            'attribute' => 'classname',
                            'value' => function ($model) {
                                $object = \Yii::createObject($model['classname']);
                                return $object->getLabel();
                            }
                        ],
                        [
                            'label' => 'Descrizione',
                            'value' => function ($model) {
                                $object = \Yii::createObject($model['classname']);
                                return $object->getDescription();
                            }
                        ],
                    ]
                ]);
            ?>
        </div>
    </div>

<?php endif; ?>