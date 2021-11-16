<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/statistics/views
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\amos\sondaggi\components\GraficiGoogle;
use yii\helpers\VarDumper;

/**
 * @var yii\web\View $this
 * @var backend\modules\campains\models\PreferenceCampain $model
 * @var yii\widgets\ActiveForm $form
 */


$this->title = Yii::t('statistics', 'Aggregazione tematiche');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/statistics/preference-campains/items']];
$this->params['breadcrumbs'][] = $this->title;

// TODO da sistemare... il form inietta questo messaggio di errore! un fantasma...
$this->registerCss("
#errore-alert-common {display:none;}
");
$script = <<< JS
	$("#choice-id").change(function(){
		$("#myform-id").submit();
	});

JS;

$this->registerJs($script);
?>
<?php
// VarDumper::dump( $form->messages, $depth = 10, $highlight = true);
$form = ActiveForm::begin([
    'action' => ['items'],
    'method' => 'get',
    'options' => [
        'class' => 'default-form',
        'id' => 'myform-id'
    ]
]);
?>
<div class="row">

    <div class="col-md-6">
        <?php
        echo $form->field($model, 'target_id')->widget(\kartik\select2\Select2::className(), [
            'data' => $choices,
            'options' => [
                'multiple' => false,
                'id' => 'choice-id',
                'placeholder' => 'Scegli un target oppure tutti'
            ]
        ]);
        ?>
    </div>
    <!-- <div class="col-md-6">
        <div class="right">
            <?= Html::submitButton(Yii::t('statistics', 'Apri statistica'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div> -->

</div>
<?php ActiveForm::end(); ?>

<?php
if ($model->target_id == 0):
?>

<div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($data['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $data['data-provider'],
                    // 'showPageSummary' => true,
                    'striped' => true,
                    'hover' => true,
                    'panel' => [
                        'type' => 'info',
                        'heading' => false, // 'Suddivisione di genere'
                    ],
                    'toggleDataOptions' => [
                        'all' => [
                            'label' => 'Tutto'
                        ],
                    ],
                    'exportConfig' => [
                        \kartik\grid\GridView::HTML => [],
                        \kartik\grid\GridView::CSV => [],
                        \kartik\grid\GridView::TEXT => [],
                        \kartik\grid\GridView::EXCEL => [],
                        \kartik\grid\GridView::PDF => [],
                    ],
                    'columns' => [
                        'target' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'target',
                            'label' => 'Target',
                            'value' => function ($model) {
                                return isset($model[0]) ? $model[0] : '';
                            }
                        ],   // 'Tasso medio di recapito', 'Tasso medio di aperture', 'Tasso medio di click', 'Tasso medio di click su aperture'
                        't1' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't1',
                            'label' => 'Tasso medio di recapito (%)',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        't2' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't2',
                            'label' => 'Tasso medio di aperture (%)',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        't3' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't3',
                            'label' => 'Tasso medio di click (%)',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ],
                        't4' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't4',
                            'label' => 'Tasso medio di click su aperture (%)',
                            'value' => function ($model) {
                                return isset($model[4]) ? $model[4] : '';
                            }
                        ],
                        't5' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't5',
                            'label' => 'Numero di campagne',
                            'value' => function ($model) {
                                return isset($model[5]) ? $model[5] : '';
                            }
                        ],

                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-12">
            <?php
            if (!empty($data['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $data['graf-data'],
                    'options' => [
                        'is3D' => true,
                        // 'isStacked' => 'true',
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'right'],
                        'orientation' => 'horizontal',
                        'height' => 400,
                        'hAxis' => [
                            'title' => 'Tassi su tematiche',
                            'gridlines' => [
                                'color' => null, //set grid line transparent                                    
                            ]
                        ],
                        'colors' => ['#cc2200','#e24800', '#ff7701','#ff9c00', 'black'],
                        'vAxis' => [
                            'title' => null,
                            'format' => '#,##%', 
                            'slantedText' => true,
                            'gridlines' => [
                                'color' => null, //set grid line transparent     
                            //'multiple' => 1,
                        ]],
                    ]
                ]);
            }
            ?>
        </div>
    </div>
<?php
else: // _______________________________________________________
?>

<div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($data['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $data['data-provider'],
                    // 'showPageSummary' => true,
                    'striped' => true,
                    'hover' => true,
                    'panel' => [
                        'type' => 'info',
                        'heading' => false, // 'Suddivisione di genere'
                    ],
                    'toggleDataOptions' => [
                        'all' => [
                            'label' => 'Tutto'
                        ],
                    ],
                    'exportConfig' => [
                        \kartik\grid\GridView::HTML => [],
                        \kartik\grid\GridView::CSV => [],
                        \kartik\grid\GridView::TEXT => [],
                        \kartik\grid\GridView::EXCEL => [],
                        \kartik\grid\GridView::PDF => [],
                    ],
                    'columns' => [
                        'items' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'items',
                            'label' => 'Tematica',
                            'value' => function ($model) {
                                return isset($model[0]) ? $model[0] : '';
                            }
                        ],   // 'Tasso medio di recapito', 'Tasso medio di aperture', 'Tasso medio di click', 'Tasso medio di click su aperture'
                        't1' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't1',
                            'label' => 'Tasso medio di recapito (%)',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        't2' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't2',
                            'label' => 'Tasso medio di aperture (%)',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        't3' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't3',
                            'label' => 'Tasso medio di click (%)',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ],
                        't4' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't4',
                            'label' => 'Tasso medio di click su aperture (%)',
                            'value' => function ($model) {
                                return isset($model[4]) ? $model[4] : '';
                            }
                        ],
                        't5' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 't5',
                            'label' => 'Numero di campagne',
                            'value' => function ($model) {
                                return isset($model[5]) ? $model[5] : '';
                            }
                        ],
                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-12">
            <?php
            // VarDumper::dump($data['graf-data'], $depth = 10, $highlight = true); 
            if (count($data['graf-data']) > 1) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $data['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'title' => 'Tassi su tematiche',
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'right'],
                        'bar' => ['groupWidth' => "75%"],
                        'orientation' => 'vertical',
                        'height' => 700,
                        'chartArea' => [
                            'left' => '25%',
                            'top' => 0,
                            'width' => '50%',
                            'height' => '90%'
                        ],
                        // 'isStacked' => 'percent',
                        'hAxis' => [
                            'title' => 'Tassi su tematiche',
                            'format' => '#,##%', 
                            'gridlines' => [
                                'color' => null, //set grid line transparent                                    
                            ]
                        ],
                        'vAxis' => ['title' => null, 'slantedText' => true, 'gridlines' => [
                            'color' => null, //set grid line transparent     
                            'multiple' => 1,
                        ]],
                    ]
                ]);
            }
            ?>
        </div>
    </div>
    
<?php
endif;
?>
