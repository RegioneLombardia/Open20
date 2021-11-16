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


$this->title = Yii::t('statistics', 'Iscrizioni');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/statistics/preference-campains/inscriptions']];
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
    'action' => ['inscriptions'],
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
        echo $form->field($model, 'user_status_id')->widget(\kartik\select2\Select2::className(), [
            'data' => $choices,
            'options' => [
                'multiple' => false,
                'placeholder' => 'Scegli uno stato utente',
                'id' => 'choice-id',
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
if ($model->user_status_id == 1) :
?>

    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($inscriptionsData['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $inscriptionsData['data-provider'],
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
                        'mesi' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'mesi',
                            'label' => 'Mesi',
                            'value' => function ($model) {
                                return isset($model[0]) ? $model[0] : '';
                            }
                        ],
                        'np' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'np',
                            'label' => 'Numero iscrizioni',
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
            if (!empty($inscriptionsData['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $inscriptionsData['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        'orientation' => 'vertical',
                        'height' => 400,
                        'hAxis' => [
                            'title' => null, //'Grafico suddivisione di genere',
                            'gridlines' => [
                                'color' => null, //set grid line transparent   
                                'multiple' => 1,                                 
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
// ____________________________________________________________ 

if ($model->user_status_id == 2) :
?>

<div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($inscriptionsData['data-provider'])) {
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $inscriptionsData['data-provider'],
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
                        'mesi' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'mesi',
                            'label' => 'Mesi',
                            'value' => function ($model) {
                                return isset($model[0]) ? $model[0] : '';
                            }
                        ],
                        'np' => [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'np',
                            'label' => 'Numero disiscrizioni',
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
            if (!empty($inscriptionsData['graf-data'])) {
                echo GraficiGoogle::widget([
                    'visualization' => 'ColumnChart',
                    'data' => $inscriptionsData['graf-data'],
                    'options' => [
                        'is3D' => true,
                        'sliceVisibilityThreshold' => 0,
                        'showTip' => true,
                        'legend' => ['position' => 'top'],
                        'orientation' => 'vertical',
                        'height' => 400,
                        'hAxis' => [
                            'title' => null, //'Grafico suddivisione di genere',
                            'gridlines' => [
                                'color' => null, //set grid line transparent  
                                'multiple' => 1,                                  
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