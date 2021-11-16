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


$this->title = Yii::t('statistics', 'Segmentazione aperture');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/statistics/preference-campains/index']];
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
    'action' => ['index'],
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
        echo $form->field($model, 'channel_id')->widget(\kartik\select2\Select2::className(), [
            'data' => $choices,
            'options' => [
                'id' => 'choice-id',
                'multiple' => false,
                'placeholder' => 'Scegli una campagna mail inviata'
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
if ($model->channel_id > 0):
?>
<div class="row">
    <div class="col-md-12">
        <h4>Segmentazione per sesso</h4>
    </div>
</div>

<div class="row">
    <?php
    if (empty($genderData['data-provider']) && empty($genderData['graf-data'])) {
    ?>
    <div class="col-md-12">
        <div class="alert alert-secondary" role="alert">
            Nessun dato disponibile
        </div>
    </div>
    <?php
    }
    ?>
    <div class="col-md-6">
        <?php
        if (!empty($genderData['data-provider'])) {
            echo \kartik\grid\GridView::widget([
                'dataProvider' => $genderData['data-provider'],
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
                    'genere' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'genere',
                        'label' => 'Genere',
                        'value' => function ($model) {
                            return isset($model[0]) ? $model[0] : '';
                        }
                    ],
                    'numero_persone' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'numero_persone',
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
    <div class="col-md-6">
        <?php
        if (!empty($genderData['graf-data'])) {
            echo GraficiGoogle::widget([
                'visualization' => 'ColumnChart',
                'data' => $genderData['graf-data'],
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
                    'vAxis' => ['title' => null, 'slantedText' => true],
                ]
            ]);
        }
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Segmentazione per età</h4>
    </div>
</div>

<div class="row">
    <?php
    if (empty($ageGroupData['data-provider']) && empty($ageGroupData['graf-data'])) {
        ?>
        <div class="col-md-12">
            <div class="alert alert-secondary" role="alert">
                Nessun dato disponibile
            </div>
        </div>
        <?php
    }
    ?>
    <div class="col-md-12">
        <?php
        if (!empty($ageGroupData['data-provider'])) {
            echo \kartik\grid\GridView::widget([
                'dataProvider' => $ageGroupData['data-provider'],
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
                    'genere' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'genere',
                        'label' => 'Età',
                        'value' => function ($model) {
                            return isset($model[0]) ? $model[0] : '';
                        }
                    ],
                    'numero_persone' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'numero_persone',
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
        if (!empty($ageGroupData['graf-data'])) {
            echo GraficiGoogle::widget([
                'visualization' => 'ColumnChart',
                'data' => $ageGroupData['graf-data'],
                'options' => [
                    'is3D' => true,
                    'sliceVisibilityThreshold' => 0,
                    'showTip' => true,
                    'legend' => ['position' => 'top'],
                    'orientation' => 'horizontal',
                    'height' => 400,
                    'hAxis' => [
                        'title' => 'Grafico segmentazione per età',
                        'gridlines' => [
                            'color' => null, //set grid line transparent                                    
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
        <h4>Segmentazione per province</h4>
    </div>
</div>

<div class="row">
    <?php
    if (empty($provincesData['data-provider']) && empty($provincesData['graf-data'])) {
        ?>
        <div class="col-md-12">
            <div class="alert alert-secondary" role="alert">
                Nessun dato disponibile
            </div>
        </div>
        <?php
    }
    ?>
    <div class="col-md-12">
        <?php
        if (!empty($provincesData['data-provider'])) {
            echo \kartik\grid\GridView::widget([
                'dataProvider' => $provincesData['data-provider'],
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
                    'genere' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'genere',
                        'label' => 'Provincia',
                        'value' => function ($model) {
                            return isset($model[0]) ? $model[0] : '';
                        }
                    ],
                    'numero_persone' => [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'numero_persone',
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
        if (!empty($provincesData['graf-data'])) {
            echo GraficiGoogle::widget([
                'visualization' => 'ColumnChart',
                'data' => $provincesData['graf-data'],
                'options' => [
                    'is3D' => true,
                    'sliceVisibilityThreshold' => 0,
                    'showTip' => true,
                    'legend' => ['position' => 'top'],
                    'orientation' => 'vertical',
                    'height' => 400,
                    'hAxis' => [
                        'title' => 'Grafico segmentazione per province',
                        'gridlines' => [
                            'color' => null, //set grid line transparent                                    
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
endif;
?>