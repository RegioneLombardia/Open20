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
AmosIcons::map($this);

$this->title = AmosDashboard::t('amosdashboard', 'Gestisci widget');

if ($currentDashboard->module != 'dashboard') {
    $this->params['breadcrumbs'][] = ['label' => AmosDashboard::t('amosdashboard',
            'Amministrazione '.$currentDashboard->module), 'url' => Yii::$app->urlManager->createUrl([$currentDashboard->module])];
}


$this->params['breadcrumbs'][]  = $this->title;
$this->params['widgetSelected'] = $widgetSelected;

\open20\amos\dashboard\assets\DashboardFullsizeAsset::register($this);
?>
<div class="dashboard-default-index dashboard-manager">

    <?php $form                           = ActiveForm::begin(); ?>

    <?= Html::hiddenInput('module', $currentDashboard->module) ?>
    <?= Html::hiddenInput('slide', $currentDashboard->slide) ?>
    <input type="hidden" id="saveDashboardUrl" value="<?= Yii::$app->urlManager->createUrl(['dashboard/manager/save-dashboard-order']); ?>"/>

    <div class="col-xs-12">
        <h2><?= AmosDashboard::tHtml('amosdashboard', 'Plugins'); ?></h2>
    </div>

    <div class="plugin-list dashboard-content">
        <?=
        DataProviderView::widget([
            'dataProvider' => $providerIcon,
            'currentView' => $currentView,
            'gridView' => [
                'summary' => false,
                'columns' => [
                    [
                        'class' => 'open20\amos\core\views\grid\CheckboxColumn',
                        'name' => 'amosWidgetsClassnames[]',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return [
                                'id' => \yii\helpers\StringHelper::basename($model['classname']),
                                'value' => $model['classname'],
                                'checked' => in_array($model['classname'], $this->params['widgetSelected'])
                            ];
                        }
                    ],
                    [
                        'label' => 'Icona',
                        'contentOptions' => ['class' => 'icona'],
                        'format' => 'html',
                        'value' => function ($model) {
                            $object          = \Yii::createObject($model['classname']);
                            $object->setUrl('');
                            $backgroundColor = Yii::createObject($model['classname'])->getClassSpan();
                            if (!$backgroundColor) {
                                $backgroundColor = [1 => 'color-base'];
                            }

                            if (!$backgroundColor[1]) {
                                $backgroundColor = [1 => 'bk-backgroundIcon'];
                            }

                            if (!$backgroundColor[2]) {
                                $backgroundColor = [2 => 'color-base'];
                            }
                            return '<p class="'.$backgroundColor[1].' '.$backgroundColor[2].'">'.AmosIcons::show(Yii::createObject($model['classname'])->getIcon(),
                                    '', 'dash').'</p>';
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
//TODO - colonna per ragruppamento in base al plugin
//                    [
//                        //'class'=>'kartik\grid\DataColumn',
//                        'attribute' => 'module',
//                        'label' => 'Plugin',
//                        'format' => 'html',
//                        //'group' => true,
//                        'value' => function ($model){
//                            return \yii\helpers\Inflector::camel2words($model->module);
//                        }
//                    ],
                ],
            ],
            'iconView' => [
                'itemView' => '_icon',
                'itemOptions' => [
                    'class' => 'col-xs-12 col-sm-3 col-md-4 col-lg-2 flex-column-item'
                ],
            ],
        ]);
        ?>

        <div class="col-xs-12">
            <h2><?= AmosDashboard::tHtml('amosdashboard', 'Widget'); ?></h2>
        </div>

        <?=
        AmosGridView::widget([
            'dataProvider' => $providerGraphic,
            'summary' => false,
            'columns' => [
                [
                    'attribute' => 'module',
                    'label' => 'Plugin',
                ],
                [
                    'class' => 'open20\amos\core\views\grid\CheckboxColumn',
                    'name' => 'amosWidgetsClassnames[]',
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        return [
                            'id' => \yii\helpers\StringHelper::basename($model['classname']),
                            'value' => $model['classname'],
                            'checked' => in_array($model['classname'], $this->params['widgetSelected'])
                        ];
                    }
                ],
                [
                    'label' => 'Icona',
                    'contentOptions' => ['class' => 'icona'],
                    'format' => 'html',
                    'value' => function ($model) {
                        $backgrounColor = 'color-border-mediumBase';
                        return '<p class="'.$backgrounColor.'">'.AmosIcons::show('view-web').'</p>';
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

        <div class="col-xs-12 m-b-15">
            <div class="form-actions pull-right">
                <?=
                Html::submitButton(
                    AmosDashboard::t('amosdashboard', 'Salva'), [
                    'class' => 'btn btn-success'
                ]);
                ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>




