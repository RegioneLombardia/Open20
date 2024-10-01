<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\sondaggi
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\sondaggi\assets\ModuleRisultatiAsset;
use open20\amos\sondaggi\components\GraficiGoogle;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use open20\amos\sondaggi\assets\ModuleSondaggiAsset;

ModuleSondaggiAsset::register($this);

ModuleRisultatiAsset::register($this);

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
$this->title = AmosSondaggi::t('amossondaggi', 'Risultati') . ': ' . $model->titolo;
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];

$funcResize = "";
for ($i = 0; $i < count($risposte); $i++) {
    $funcResize .= 'drawChartw' . $i . '();';
}

$this->registerJs('
    jQuery(document).ready(function() {
    $(window).resize(function(){
           ' . $funcResize . '
    });
  });
    ', yii\web\View::POS_END);
?>
<div class="sondaggi-risultati">
    <div class="row">
        <div class="grafico-index">

            <div class="col-lg-12">
                <?php
                $ind = 0;

                foreach ($domande->all() as $Domanda) {
                    ?>
                    <?php if ($ind == 0): ?>
                        <?php if (empty($disableTitle)) { ?>
                            <!--                            <h3>--><?php //$Domanda->sondaggiDomandePagine->titolo ?><!--</h3>-->
                            <!--                            <h5>--><?php //$Domanda->sondaggiDomandePagine->descrizione ?><!--</h5>-->
                        <?php } ?>
                        <!--                        <h3>--><?php //echo $model->titolo ?><!--</h3>-->
                        <!--                        --><?php //if (!empty($model->descrizione)) { ?>
                        <!--                            <h5>--><?php //echo $model->descrizione ?><!--</h5>-->
                        <!--                        --><?php //} ?>
                        <?php
                        $ind++;
                    endif;
                    ?>
                    <?php
                    if (!empty($risposte[$Domanda->id]) && count($risposte[$Domanda->id]) > 0) { ?>
                        <?php if (empty($disableTitle)) { ?>
                            <h5><?= $Domanda->domanda ?></h5>
                        <?php } ?>
                        <?php if ($model->graphics_live == \open20\amos\sondaggi\models\Sondaggi::SONDAGGI_LIVE_CHART_COLUMN) {
                            $risposteBar = [];
                            $legenda [] = '';
                            $risposteBar [] = AmosSondaggi::t('amosondaggi', 'Risposte');
                            foreach ($risposte[$Domanda->id] as $i => $item) {
                                if ($i > 0) {
                                    $legenda [] = $item[0];
                                    $risposteBar [] = $item[1];

                                }
                            }
                            $risposteTot = [$legenda, $risposteBar];
                            echo GraficiGoogle::widget([
                                'visualization' => 'ColumnChart',
                                'data' => $risposteTot,
                                //'scriptAfterChartInstantiate' => new \yii\web\JsExpression($script),

                                'options' => [
                                    //'title' => '',
                                    //'subtitle' => '',
                                    //'titleTextStyle' => ['color' => 'red', 'fontSize' => 45],
                                    //'is3D' => true,
                                    'sliceVisibilityThreshold' => 0,
                                    'showTip' => true,
                                    'legend' => ['position' => 'top'],
                                    //'isStacked' => true,
                                    'orientation' => 'vertical',
//                                'colors' => [$Risposta[1][2]],
                                    /* 'slices' => [
                                      0 => ['color' => '#33adff'],
                                      1 => ['color' => '#ff33bb'],
                                      ], */

                                      'width' => '100%',
                                      'height' => '100%',
                                    'hAxis' => ['title' => AmosSondaggi::t('amossondaggi', 'Numero partecipanti'),
                                        'format' => '#',
                                        'gridlines' => [
                                            'color' => null, //set grid line transparent
                                        ]],
                                    'vAxis' => ['title' => '', 'slantedText' => false],

                                ]
                            ]);
                        } else { ?>

                            <?php
                            echo GraficiGoogle::widget([
                                'visualization' => 'PieChart',
                                'data' => $risposte[$Domanda->id],
                                'options' => [
//                                    'title' => $Domanda->domanda,
                                    //'subtitle' => 'Tutti i partecipanti, iscritti al primo giorno',
                                    //'titleTextStyle' => ['color' => 'red', 'fontSize' => 45],
                                    //'is3D' => true,
                                    'showTip' => true,
                                    'sliceVisibilityThreshold' => 0,
                                    //'isStacked' => false,
                                    /* 'slices' => [
                                      0 => ['color' => '#8ec44e'],
                                      1 => ['color' => '#3aa060'],
                                      2 => ['color' => '#ea5c6f'],
                                      3 => ['color' => '#0dc988'],
                                      4 => ['color' => '#53cfc4'],
                                      ], */
                                      'chartArea' => [
                                        'width' => '100%',
                                        'height' => '90%'
                                    ],
                                      'width' => '100%',
                                      'height' => '100%',

//                                        'legend' => [
                                    // 'position' => 'none',
//                                        ],
                                    'vAxis' => [
                                        'title' => AmosSondaggi::t('amossondaggi', 'Numero occorrenze'),
                                        'gridlines' => [
                                            'color' => null  //set grid line transparent
                                        ]],
                                    'hAxis' => ['title' => 'Risposte'],
                                ]
                            ]);
                        }
                    } else {
                        ?>
                        <strong><?= $Domanda->domanda ?></strong>
                        <br><?= AmosSondaggi::t('amossondaggi', 'Non sono presenti risposte.') ?><br><br>
                    <?php } ?>
                    <?php
                }
                ?>
            </div>

        </div>


    </div>

</div>
