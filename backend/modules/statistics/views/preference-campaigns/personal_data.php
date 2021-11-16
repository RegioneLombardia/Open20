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
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * @var yii\web\View $this
 * @var backend\modules\campains\models\PreferenceCampain $model
 * @var yii\widgets\ActiveForm $form
 */


$this->title = Yii::t('statistics', 'Segmentazione per preferenze e criteri anagrafici');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/statistics/preference-campains/items']];
$this->params['breadcrumbs'][] = $this->title;

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
    'action' => ['personal-data'],
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
if ($model->target_id == -1) :
    ?>
    
        <div class="row">
            <div class="col-md-6">
                <?php
                if (!empty($dataGender['data-provider'])) {
                    echo \kartik\grid\GridView::widget([
                        'dataProvider' => $dataGender['data-provider'],
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
                                'label' => '',
                                'value' => function ($model) {
                                    return isset($model[0]) ? $model[0] : '';
                                }
                            ],
                            'maschi' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'maschi',
                                'label' => 'Maschi',
                                'value' => function ($model) {
                                    return isset($model[1]) ? $model[1] : '';
                                }
                            ],
                            'femmine' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'femmine',
                                'label' => 'Femmine',
                                'value' => function ($model) {
                                    return isset($model[2]) ? $model[2] : '';
                                }
                            ],
                            'nd' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'nd',
                                'label' => 'ND',
                                'value' => function ($model) {
                                    return isset($model[3]) ? $model[3] : '';
                                }
                            ],
                        ]
                    ]);
                }
                ?>
    
            </div>
            <div class="col-md-6">
                <?php
                if (!empty($dataGender['graf-data'])) {
                    echo GraficiGoogle::widget([
                        'visualization' => 'ColumnChart',
                        'data' => $dataGender['graf-data'],
                        'options' => [
                            'is3D' => true,
                            'sliceVisibilityThreshold' => 0,
                            'showTip' => true,
                            'legend' => ['position' => 'top'],
                            'orientation' => 'horizontal',
                            'height' => 400,
                            'hAxis' => [
                                'title' => 'Grafico suddivisione di genere',
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
    
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!empty($dataAgeGroups['data-provider'])) {
                    echo \kartik\grid\GridView::widget([
                        'dataProvider' => $dataAgeGroups['data-provider'],
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
                                'label' => '',
                                'value' => function ($model) {
                                    return isset($model[0]) ? $model[0] : '';
                                }
                            ],
                            'min18' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'min18',
                                'label' => '<18',
                                'value' => function ($model) {
                                    return isset($model[1]) ? $model[1] : '';
                                }
                            ],
                            '1825' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => '1825',
                                'label' => '18-25',
                                'value' => function ($model) {
                                    return isset($model[2]) ? $model[2] : '';
                                }
                            ],
                            '2635' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => '2635',
                                'label' => '26-35',
                                'value' => function ($model) {
                                    return isset($model[3]) ? $model[3] : '';
                                }
                            ],
                            '3645' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => '3645',
                                'label' => '36-45',
                                'value' => function ($model) {
                                    return isset($model[4]) ? $model[4] : '';
                                }
                            ],
                            '4655' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => '4655',
                                'label' => '46-55',
                                'value' => function ($model) {
                                    return isset($model[5]) ? $model[5] : '';
                                }
                            ],
                            '5665' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => '5665',
                                'label' => '56-65',
                                'value' => function ($model) {
                                    return isset($model[6]) ? $model[6] : '';
                                }
                            ],
                            'plus65' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'plus65',
                                'label' => '>65',
                                'value' => function ($model) {
                                    return isset($model[7]) ? $model[7] : '';
                                }
                            ],
                            'nd' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'nd',
                                'label' => 'ND',
                                'value' => function ($model) {
                                    return isset($model[8]) ? $model[8] : '';
                                }
                            ],
                        ]
                    ]);
                }
                ?>
    
            </div>
            <div class="col-md-12">
                <?php
                if (!empty($dataAgeGroups['graf-data'])) {
                    echo GraficiGoogle::widget([
                        'visualization' => 'ColumnChart',
                        'data' => $dataAgeGroups['graf-data'],
                        'options' => [
                            'is3D' => true,
                            'sliceVisibilityThreshold' => 0,
                            'showTip' => true,
                            'legend' => ['position' => 'top'],
                            'orientation' => 'horizontal',
                            'height' => 400,
                            'hAxis' => [
                                'title' => 'Grafico suddivisione per fasce di età',
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
    
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!empty($dataProvinces['data-provider'])) {
                    echo \kartik\grid\GridView::widget([
                        'dataProvider' => $dataProvinces['data-provider'],
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
                            'provincia' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'provincia',
                                'label' => 'Provincia',
                                'value' => function ($model) {
                                    return isset($model[0]) ? $model[0] : '';
                                }
                            ],
                            'tot' => [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'tot',
                                'label' => 'Numero persone',
                                'value' => function ($model) {
                                    return isset($model[1]) ? $model[1] : '';
                                }
                            ],
                        ]
                    ]);
                }
                ?>
    
            </div>
            <div class="col-md-12">
                <?php
                $numData = count($dataProvinces['graf-data']);
                if ($numData > 1) {
                    echo GraficiGoogle::widget([
                        'visualization' => 'ColumnChart',
                        'data' => $dataProvinces['graf-data'],
                        'options' => [
                            'is3D' => true,
                            'orientation' => 'vertical',
                            'sliceVisibilityThreshold' => 0,
                            'showTip' => true,
                            'legend' => ['position' => 'top'],
                            // 'orientation' => 'horizontal',
                            'height' => ($numData * 40),
                            'hAxis' => [
                                'title' => 'Grafico suddivisione per provincia',
                                'gridlines' => [
                                    'color' => null, //set grid line transparent   
    
                                    'multiple' => 1,
    
                                ]
                            ],
                            'vAxis' => ['title' => null, 'slantedText' => true],
                        ]
                    ]);
                }
                ?>
            </div>
        </div>
    
    <?php
elseif ($model->target_id == 0) :
?>

    <div class="row">
        <div class="col-md-6">
            <?php
            if (!empty($dataGender['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataGender['data-provider'],
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
                        ],
                        'maschi' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'maschi',
                            'label' => 'Maschi',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        'femmine' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'femmine',
                            'label' => 'Femmine',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        'nd' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'nd',
                            'label' => 'ND',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ],
                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-6">
            <?php
            if (!empty($dataGender['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $dataGender['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        'orientation' => 'horizontal',
                        'height' => 400,
                        'hAxis' => [
                            'title' => 'Grafico suddivisione di genere',
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

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($dataAgeGroups['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataAgeGroups['data-provider'],
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
                        ],
                        'min18' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'min18',
                            'label' => '<18',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        '1825' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '1825',
                            'label' => '18-25',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        '2635' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '2635',
                            'label' => '26-35',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ],
                        '3645' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '3645',
                            'label' => '36-45',
                            'value' => function ($model) {
                                return isset($model[4]) ? $model[4] : '';
                            }
                        ],
                        '4655' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '4655',
                            'label' => '46-55',
                            'value' => function ($model) {
                                return isset($model[5]) ? $model[5] : '';
                            }
                        ],
                        '5665' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '5665',
                            'label' => '56-65',
                            'value' => function ($model) {
                                return isset($model[6]) ? $model[6] : '';
                            }
                        ],
                        'plus65' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'plus65',
                            'label' => '>65',
                            'value' => function ($model) {
                                return isset($model[7]) ? $model[7] : '';
                            }
                        ],
                        'nd' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'nd',
                            'label' => 'ND',
                            'value' => function ($model) {
                                return isset($model[8]) ? $model[8] : '';
                            }
                        ],
                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-12">
            <?php
            if (!empty($dataAgeGroups['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $dataAgeGroups['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        'orientation' => 'horizontal',
                        'height' => 400,
                        'hAxis' => [
                            'title' => 'Grafico suddivisione per fasce di età',
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

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($dataProvinces['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataProvinces['data-provider'],
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
                        'provincia' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'provincia',
                            'label' => 'Provincia',
                            'value' => function ($model) {
                                return isset($model[0]) ? $model[0] : '';
                            }
                        ],
                        'cit' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'cit',
                            'label' => 'Cittadino',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        'imp' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'imp',
                            'label' => 'Impresa',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        'eo' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'eo',
                            'label' => 'Enti e Operatori',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ]/*,
                        'nd' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'nd',
                            'label' => 'ND',
                            'value' => function ($model) {
                                return isset($model[4]) ? $model[4] : '';
                            }
                        ], */
                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-12">
            <?php
             $numData = count($dataProvinces['graf-data']);
             if ($numData > 1) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $dataProvinces['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'orientation' => 'vertical',
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        // 'orientation' => 'horizontal',
                        'height' => ($numData * 50),
                        'hAxis' => [
                            'title' => 'Grafico suddivisione per provincia',
                            'gridlines' => [
                                'color' => null, //set grid line transparent   

                                'multiple' => 1,

                            ]
                        ],
                        'vAxis' => ['title' => null, 'slantedText' => true],
                    ]
                ]);
            }
            ?>
        </div>
    </div>

<?php
elseif($model->target_id > 0) : // ____________________________________________________________ 
?>

    <div class="row">
        <div class="col-md-6">
            <?php
            if (!empty($dataGender['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataGender['data-provider'],
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
                        ],
                        'maschi' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'maschi',
                            'label' => 'Maschi',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        'femmine' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'femmine',
                            'label' => 'Femmine',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        'nd' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'nd',
                            'label' => 'ND',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ],
                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-6">
            <?php
            if (!empty($dataGender['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $dataGender['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        'orientation' => 'vertical',
                        'height' => 600,
                        'chartArea' => [
                            'left' => '40%',
                            'top' => 0,
                            'width' => '50%',
                            'height' => '90%'
                        ],
                        'hAxis' => [
                            'title' => 'Grafico suddivisione di genere',
                            'gridlines' => [
                                'color' => null, //set grid line transparent    

                                'multiple' => 1,

                            ]
                        ],
                        'vAxis' => ['title' => null, 'slantedText' => true],
                    ]
                ]);
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($dataAgeGroups['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataAgeGroups['data-provider'],
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
                    'columns' => [
                        'target' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'target',
                            'label' => 'Target',
                            'value' => function ($model) {
                                return isset($model[0]) ? $model[0] : '';
                            }
                        ],
                        'min18' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'min18',
                            'label' => '<18',
                            'value' => function ($model) {
                                return isset($model[1]) ? $model[1] : '';
                            }
                        ],
                        '1825' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '1825',
                            'label' => '18-25',
                            'value' => function ($model) {
                                return isset($model[2]) ? $model[2] : '';
                            }
                        ],
                        '2635' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '2635',
                            'label' => '26-35',
                            'value' => function ($model) {
                                return isset($model[3]) ? $model[3] : '';
                            }
                        ],
                        '3645' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '3645',
                            'label' => '36-45',
                            'value' => function ($model) {
                                return isset($model[4]) ? $model[4] : '';
                            }
                        ],
                        '4655' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '4655',
                            'label' => '46-55',
                            'value' => function ($model) {
                                return isset($model[5]) ? $model[5] : '';
                            }
                        ],
                        '5665' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => '5665',
                            'label' => '56-65',
                            'value' => function ($model) {
                                return isset($model[6]) ? $model[6] : '';
                            }
                        ],
                        'plus65' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'plus65',
                            'label' => '>65',
                            'value' => function ($model) {
                                return isset($model[7]) ? $model[7] : '';
                            }
                        ],
                        'nd' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'nd',
                            'label' => 'ND',
                            'value' => function ($model) {
                                return isset($model[8]) ? $model[8] : '';
                            }
                        ],
                    ]
                ]);
            }
            ?>

        </div>
        <div class="col-md-12">
            <?php
            if (!empty($dataAgeGroups['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $dataAgeGroups['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        'orientation' => 'horizontal',
                        'height' => 500,
                        'hAxis' => [
                            'title' => 'Grafico suddivisione per fasce di età',
                            'gridlines' => [
                                'color' => null, //set grid line transparent                                    
                            ]
                        ],
                        'vAxis' => [
                            'title' => null,
                            'slantedText' => true,
                            'gridlines' => [
                                'color' => null, //set grid line transparent     
                                'multiple' => 1,
                            ]
                        ],
                    ]
                ]);
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php

            $allTopicModels = \preference\userprofile\utility\TopicTagUtility::getAllTopicByTargetId($model->target_id);
            $allTopic = ArrayHelper::map($allTopicModels, 'id', 'nome');
//            array_push($allTopic, 'nd');

            $columns[0] = [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 0,
                'label' => 'Provincia',
                'value' => function ($model) {
                    return isset($model[0]) ? $model[0] : '';
                }

            ];

            $i = 1;
            foreach ($allTopic as $key => $value) {
                array_push($columns, [
                    'class' => '\kartik\grid\DataColumn',
                    'attribute' => $key,
                    'label' => $value,
                    'value' => function ($model) use ($i) {
                        // VarDumper::dump($model[$i], $depth = 10, $highlight = true);
                        return isset($model[$i]) ? $model[$i] : '';
                    }
                ]);
                $i++;
            }

            if (!empty($dataProvinces['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataProvinces['data-provider'],
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
                    'columns' => $columns,
                ]);
            }
            ?>

        </div>
        <div class="col-md-12"> 
            <?php
             $numData = count($dataProvinces['graf-data']);
             if ($numData > 1) {
                //  VarDumper::dump(count($dataProvinces['graf-data']), $depth = 10, $highlight = true);
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $dataProvinces['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'orientation' => 'vertical',
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'right'],
                        // 'orientation' => 'horizontal',
                        'height' => (($numData * 100) < 400)? 400: ($numData * 100),
                        'chartArea' => [
                            'left' => '15%',
                            'top' => 0,
                            'width' => '50%',
                            'height' => '90%'
                        ],
                        'hAxis' => [
                            'title' => 'Grafico suddivisione per provincia',
                            'gridlines' => [
                                'color' => null, //set grid line transparent     
                                'multiple' => 1,
                            ]
                        ],
                        'vAxis' => [
                            'title' => null,
                            'slantedText' => true,
                            'gridlines' => [
                                'multiple' => 1,
                            ]
                        ],
                    ]
                ]);
            }
            ?>
        </div>
    </div>

<?php
endif;
?>