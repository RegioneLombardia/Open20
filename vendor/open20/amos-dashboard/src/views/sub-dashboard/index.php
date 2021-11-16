<?php

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\views\DataProviderView;
use open20\amos\dashboard\AmosDashboard;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;

/* * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard * */
/* * @var \open20\amos\dashboard\models\AmosWidgets $widgetIconSelectable * */
/* * @var \open20\amos\dashboard\models\AmosWidgets $widgetGraphicSelectable * */
/* * @var array $widgetSelected * */

/* * @var \yii\web\View $this * */
AmosIcons::map($this);

$this->registerJs("
   $('document').ready(function(){
        $('#conf_new_subdash').on('click', function() {
            $.pjax.reload({container:'#grid-sub_dash', timeout: 40000});  //Reload GridView
        });
    });
    $('#module-subdash_id').change(function(){
              setTimeout(function(){
              $('#classname-subdash_id').val();
              $('#classname-subdash_id').trigger('change');
              }, 2000);
        });
        ", yii\web\View::POS_READY
);

$this->title = AmosDashboard::t('amosdashboard', 'Widget delle sotto-dashboard');

if ($currentDashboard->module != 'dashboard') {
    $this->params['breadcrumbs'][] = ['label' => AmosDashboard::t('amosdashboard',
            'Amministrazione '.$currentDashboard->module), 'url' => Yii::$app->urlManager->createUrl([$currentDashboard->module])];
}

$this->params['breadcrumbs'][]  = $this->title;
?>
<div class="dashboard-default-index">

    <?php
    Pjax::begin(['id' => 'grid-sub_dash']);
    echo AmosGridView::widget([
        'dataProvider' => new yii\data\ArrayDataProvider(['allModels' => $modules, 'pagination' => false]),
        'columns' => [
            [
                'attribute' => 'id',
                'label' => \Yii::t('amosdashboard', 'Nome del modulo'),
                'value' => function ($model) {
                    return Inflector::camel2words(Inflector::id2camel($model['id']));
                }
            ],
            [
                'attribute' => 'widget_icons',
                'label' => \Yii::t('amosdashboard', 'Widget ad icona'),
                'format' => 'html',
                'value' => function($model) {
                    $allWidgets    = \open20\amos\dashboard\models\search\AmosWidgetsSearch::getAllSubdashWidgets(\open20\amos\dashboard\models\AmosWidgets::TYPE_ICON,
                            false, $model['id']);
                    $widgetsToShow = [];

                    foreach ($allWidgets as $widget) {
                        $widgetsToShow[] = StringHelper::basename($widget['classname']);
                    }
                    return implode("<br>", $widgetsToShow);
                }
            ],
            [
                'attribute' => 'widget_graphics',
                'label' => \Yii::t('amosdashboard', 'Widget grafici'),
                'format' => 'html',
                'value' => function($model) {
                    $allWidgets    = \open20\amos\dashboard\models\search\AmosWidgetsSearch::getAllSubdashWidgets(\open20\amos\dashboard\models\AmosWidgets::TYPE_GRAPHIC,
                            false, $model['id']);
                    $widgetsToShow = [];

                    foreach ($allWidgets as $widget) {
                        $widgetsToShow[] = StringHelper::basename($widget['classname']);
                    }
                    return implode("<br>", $widgetsToShow);
                }
            ]
        ]
    ]);
    Pjax::end();
    ?>

    <hr />
    <div class="clearfix"></div>
    <h2><?= AmosDashboard::tHtml('amosdashboard', 'Modifica i widget delle sotto-dashboard'); ?></h2>
    <div class="plugin-list">
        <div class="row">
            <div class="col-lg-6">
                <?php
                //Pjax::begin(['id' => 'conf_new_subdash']);
                $form = ActiveForm::begin([
                        'enableAjaxValidation' => true,
                        'options' => ['data-pjax' => true],
                ]);
                ?>
                <?=
                $form->field($model, 'module')->widget(\kartik\widgets\Select2::className(),
                    [
                    'data' => \yii\helpers\ArrayHelper::map($modules, 'id', 'name'),
                    'options' => ['id' => 'module-subdash_id', 'placeholder' => AmosDashboard::t('amosdashboard',
                            'Select ...')],
                ]);
                ?>
            </div>
            <div class="col-lg-6">
                <?=
                $form->field($model, 'classname_subdashboard')->widget(\kartik\widgets\DepDrop::className(),
                    [
                    'type' => kartik\depdrop\DepDrop::TYPE_SELECT2,
                    'options' => ['id' => 'classname-subdash_id',
                        'placeholder' => AmosDashboard::t('amosdashboard', 'Select ...'),
                        'multiple' => true],
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'pluginOptions' => [
                        'depends' => ['module-subdash_id'],
                        'url' => yii\helpers\Url::to(['/dashboard/sub-dashboard/widgets-by-module']),
                        'initialize' => true,
                    ]
                ])
                ?>
            </div>
        </div>
        <div class="form-actions pull-right">
            <?=
            Html::submitButton(
                AmosDashboard::t('amosdashboard', 'Salva'),
                [
                'class' => 'btn btn-success',
                'id' => 'conf_new_subdash'
            ]);
            ?>
        </div>
    </div>
    <?php $form->end();
    ?>

</div>




