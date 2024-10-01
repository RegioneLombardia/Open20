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

ModuleRisultatiAsset::register($this);

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\sondaggi\models\search\SondaggiSearch $searchModel
 */
$this->title                   = AmosSondaggi::t('amossondaggi', 'Risultati').': '.$model->titolo;
$this->params['breadcrumbs'][] = ['label' => AmosSondaggi::t('amossondaggi', 'Sondaggi'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];

$funcResize = "";
for ($i = 0; $i < count($risposte); $i++) {
    $funcResize .= 'drawChartw'.$i.'();';
}

$this->registerJs('
    jQuery(document).ready(function() {
    $(window).resize(function(){
           '.$funcResize.'
    });
  });
    ', yii\web\View::POS_END);
?>
<div class="sondaggi-risultati">
    <div class="row">
        <div class="grafico-index">
            <div class="col-lg-12 menu-navigazione">
                <br>
                <?=
                Html::a(AmosSondaggi::t('amossondaggi', '&nbsp;&nbsp;<strong><<</strong>&nbsp;&nbsp;'),
                    (($idPagina != -1) ? ['risultati', 'id' => $model->id, 'idPagina' => -1, 'filter' => $filter] : null),
                    ['class' => 'btn btn-success', 'disabled' => (($idPagina != -1) ? false : true)])."&nbsp;&nbsp;&nbsp;".
                Html::a(AmosSondaggi::t('amossondaggi', '&nbsp;&nbsp;<strong><</strong>&nbsp;&nbsp;'),
                    (($idPagina != -1) ? ['risultati', 'id' => $model->id, 'idPagina' => $paginaPrecedente, 'filter' => $filter]
                            : null), ['class' => 'btn btn-success', 'disabled' => (($idPagina != -1) ? false : true)])."&nbsp;&nbsp;&nbsp;".
                Html::a(AmosSondaggi::t('amossondaggi', '&nbsp;&nbsp;<strong>></strong>&nbsp;&nbsp;'),
                    (($idPagina != 0) ? ['risultati', 'id' => $model->id, 'idPagina' => $prossimaPagina, 'filter' => $filter]
                            : null),
                    ['class' => 'btn btn-success', 'disabled' => (($idPagina != 0) ? (!is_null($prossimaPagina) ? false : true)
                            : true)])."&nbsp;&nbsp;&nbsp;".
                Html::a(AmosSondaggi::t('amossondaggi', '&nbsp;&nbsp;<strong>>></strong>&nbsp;&nbsp;'),
                    (($idPagina != 0) ? ['risultati', 'id' => $model->id, 'idPagina' => 0, 'filter' => $filter] : null),
                    ['class' => 'btn btn-success', 'disabled' => (($idPagina != 0) ? false : true)]);
                ?>
            </div>
            <?php if ($idPagina == -1): ?>
                <div class="col-lg-12">
                    <?php if ($tipo == 0): ?>
                        <h3><?= $model->titolo ?></h3>
                        <h4><?= $model->descrizione ?></h4>
                        <?php
                        echo GraficiGoogle::widget([
                            'visualization' => 'ColumnChart',
                            'data' => $risposte,
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
                                'colors' => ['#8ec44e', '#3aa060', '#ea5c6f', '#0dc988', '#53cfc4', '#f8b439'],
                                /* 'slices' => [
                                  0 => ['color' => '#33adff'],
                                  1 => ['color' => '#ff33bb'],
                                  ], */
                                'height' => 700,
                                'hAxis' => ['title' => AmosSondaggi::t('amossondaggi', 'Numero dei partecipanti'),
                                    'gridlines' => [
                                        'color' => null, //set grid line transparent
                                        'multiple' => 1,
                                    ]],
                                'vAxis' => ['title' => null, 'slantedText' => false],
                            ]
                        ]);
                        ?>
                    <?php elseif ($tipo == 1 || $tipo == 2): ?>
                        <h3><?= $model->titolo ?></h3>
                        <h4><?= $model->descrizione ?></h4>
                        <?php
                        foreach ($risposte as $Risposta) {
                            echo GraficiGoogle::widget([
                                'visualization' => 'ColumnChart',
                                'data' => $Risposta,
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
                                    'colors' => [$Risposta[1][2]],
                                    /* 'slices' => [
                                      0 => ['color' => '#33adff'],
                                      1 => ['color' => '#ff33bb'],
                                      ], */
                                    'height' => 700,
                                    'hAxis' => ['title' => AmosSondaggi::t('amossondaggi', 'Numero partecipanti'),
                                        'gridlines' => [
                                            'color' => null, //set grid line transparent
                                        ]],
                                    'vAxis' => ['title' => null, 'slantedText' => false],
                                ]
                            ]);
                        } elseif ($tipo == 3 || $tipo == 4):
                        ?>
                        <h3><?= $model->titolo ?></h3>
                        <h4><?= $model->descrizione ?></h4>
                        <?php
                        foreach ($risposte as $Risposta) {
                            if (isset($Risposta[0][0])) {
                                if ($Risposta[0][0] == 'Provincia') {
                                    if ($this->context->module->enableGeoChart) {
                                        ?>
                                        <h4><?= AmosSondaggi::t('amossondaggi', 'Accessi e compilazioni per provincia') ?></h4>
                                        <?php
                                        echo GraficiGoogle::widget([
                                            'visualization' => 'GeoChart',
                                            'data' => $Risposta,
                                            //'scriptAfterChartInstantiate' => new \yii\web\JsExpression($script),
                                            'options' => [
                                                'region' => 'IT',
                                                'displayMode' => 'markers',
                                                'resolution' => 'provinces',
                                                'datalessRegionColor' => '#f5f5f5',
                                                'colorAxis' => [
                                                    'colors' => ['#8ec44e', '#3aa060',],
                                                ],
                                                'explorer' => [
                                                    'maxZoomIn' => 0.5,
                                                    'maxZoomOut' => 8
                                                ],
                                                'colors' => [0xFF8747, 0xFFB581, 0xc06000],
                                                /* 'sizeAxis' => [
                                                  'minValue' => 0,
                                                  'maxValue' =>  100
                                                  ], */
                                                //'title' => '',
                                                //'subtitle' => '',
                                                //'titleTextStyle' => ['color' => 'red', 'fontSize' => 45],
                                                //'is3D' => true,
                                                'sliceVisibilityThreshold' => 0,
                                                'showTip' => true,
                                                'legend' => ['position' => 'top'],
                                                //'isStacked' => true,
                                                //'orientation' => 'vertical',
                                                //'colors' => [$Risposta[1][2]],
                                                /* 'slices' => [
                                                  0 => ['color' => '#33adff'],
                                                  1 => ['color' => '#ff33bb'],
                                                  ], */
                                                'height' => 700,
                                            /* 'hAxis' => ['title' => 'Numero partecipanti',
                                              'gridlines' => [
                                              'color' => null, //set grid line transparent
                                              ]],
                                              'vAxis' => ['title' => null, 'slantedText' => false], */
                                            ]
                                        ]);
                                    }
                                } else if ($Risposta[0][0] == 'Cognome') {
                                    $arrRisposta = $Risposta;
                                    array_shift($arrRisposta);
                                    echo \kartik\grid\GridView::widget([
                                        'dataProvider' => new ArrayDataProvider([
                                            'allModels' => $arrRisposta,
                                            ]),
                                        'showPageSummary' => true,
                                        'pjax' => true,
                                        'striped' => true,
                                        'hover' => true,
                                        'panel' => ['type' => 'info', 'heading' => AmosSondaggi::t('amossondaggi',
                                                'Report sui partecipanti al sondaggio')],
                                        'toggleDataOptions' => [
                                            'all' => [
                                                'label' => 'Tutto'
                                            ],
                                        ],
                                        'columns' => [
                                            'cognome' => [
                                                'class' => '\kartik\grid\DataColumn',
                                                'attribute' => 'cognome',
                                                'label' => AmosSondaggi::t('amossondaggi', 'Partecipante'),
                                                'value' => function ($model) {
                                                    return $model['cognome'].' '.$model['nome'];
                                                },
                                            ],
                                            'begin_date' => [
                                                'class' => '\kartik\grid\DataColumn',
                                                'attribute' => 'begin_date',
                                                'label' => AmosSondaggi::t('amossondaggi', 'Data inizio'),
                                                'value' => function ($model) {
                                                    return $model['begin_date'];
                                                },
                                            ],
                                            'end_date' => [
                                                'class' => '\kartik\grid\DataColumn',
                                                'attribute' => 'end_date',
                                                'label' => AmosSondaggi::t('amossondaggi', 'Data fine'),
                                                'value' => function ($model) {
                                                    return $model['end_date'];
                                                },
                                            ],
                                            'dati' => [
                                                'class' => '\kartik\grid\DataColumn',
                                                'attribute' => 'dati',
                                                'label' => AmosSondaggi::t('amossondaggi', 'Dati'),
                                                'format' => 'html',
                                                'value' => function ($model) {
                                                    return '<strong>'.AmosSondaggi::t('amossondaggi', 'E-mail').': </strong>'.$model['email'].'<br>'.
                                                        '<strong>'.AmosSondaggi::t('amossondaggi', 'Username').': </strong>'.$model['username'].'<br>'.
                                                        '<strong>'.AmosSondaggi::t('amossondaggi', 'Telefono').': </strong>'.$model['telefono'].'<br>'.
                                                        '<strong>'.AmosSondaggi::t('amossondaggi', 'Ruolo').': </strong>'.$model['role'].'<br>';
                                                },
                                            ],
                                            'stato' => [
                                                'class' => '\kartik\grid\DataColumn',
                                                'attribute' => 'stato',
                                                'label' => AmosSondaggi::t('amossondaggi', 'Stato'),
                                                'value' => function ($model) {
                                                    return $model['stato'];
                                                },
                                                'hAlign' => 'center',
                                                'group' => true,
                                            ],
                                        ]
                                    ]);
                                } else {
                                    echo GraficiGoogle::widget([
                                        'visualization' => 'ColumnChart',
                                        'data' => $Risposta,
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
                                            //'colors' => [$Risposta[1][2]],
                                            /* 'slices' => [
                                              0 => ['color' => '#33adff'],
                                              1 => ['color' => '#ff33bb'],
                                              ], */
                                            'height' => 700,
                                            'hAxis' => ['title' => AmosSondaggi::t('amossondaggi', 'Numero partecipanti'),
                                                'gridlines' => [
                                                    'color' => null, //set grid line transparent
                                                ]],
                                            'vAxis' => ['title' => null, 'slantedText' => false],
                                        ]
                                    ]);
                                }
                            }
                        }
                    endif;
                    ?>
                </div>
            <?php elseif ($idPagina == 0): ?>
                <div class="col-lg-12">
                    <h3><?= AmosSondaggi::t('amossondaggi', 'RISPOSTE LIBERE') ?></h3>
                    <hr>
                    <?php if ($risposte && $risposte->getCount()): ?>
                        <?=
                        \kartik\grid\GridView::widget([
                            'dataProvider' => $risposte,
                            //'layout' => "{items}\n{summary}\n{pager}",
                            //'showPageSummary' => true,
                            'striped' => true,
                            'hover' => true,
                            'panel' => ['type' => 'info', 'heading' => AmosSondaggi::t('amossondaggi', 'Risposte libere')],
                            'toggleDataOptions' => [
                                'all' => [
                                    'label' => 'Tutto'
                                ],
                            ],
                            'columns' => [
                                'pagina' => [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'pagina',
                                    'label' => AmosSondaggi::t('amossondaggi', 'Pagine'),
                                    'value' => function ($model) {
                                        return $model['pagina'];
                                    },
                                    'group' => true,
                                ],
                                'descrizione' => [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'descrizione',
                                    'format' => 'html',
                                    'label' => AmosSondaggi::t('amossondaggi', 'Descrizioni'),
                                    'value' => function ($model) {
                                        if (empty($model['descrizione'])) {
                                            return AmosSondaggi::t('amossondaggi', 'nessun valore');
                                        } else {
                                            return $model['descrizione'];
                                        }
                                    },
                                    //'hAlign' => 'center',
                                    'group' => true,
                                ],
                                'domanda' => [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'domanda',
                                    'label' => AmosSondaggi::t('amossondaggi', 'Domande'),
                                    'value' => function ($model) {
                                        return $model['domanda'];
                                    },
                                    //'hAlign' => 'center',
                                    'group' => true,
                                ],
                                'risposta' => [
                                    'class' => '\kartik\grid\DataColumn',
                                    'attribute' => 'risposta',
                                    'label' => AmosSondaggi::t('amossondaggi', 'Risposte'),
                                    'value' => function ($model) {
                                        return $model['risposta'];
                                    },
                                ],
                            ]
                        ])
                        ?>
                    <?php else: ?>
                        <h4><?= AmosSondaggi::t('amossondaggi', '#no_free_answer_in_poll') ?></h4>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="col-lg-12">
                    <?php
                    $ind = 0;
                    if ($model->abilita_criteri_valutazione == 1) {
                        foreach ($domande->all() as $Domanda) {
                            ?>
                            <?php if ($ind == 0): ?>
                                <h3><?= $Domanda->sondaggiDomandePagine->titolo ?></h3>
                                <h4><?= $Domanda->sondaggiDomandePagine->descrizione ?></h4>
                                <?php
                                $ind++;
                            endif;
                            ?>
                            <?php
                            if (!empty($risposte['standard'][$Domanda->id]) && count($risposte['standard'][$Domanda->id])
                                > 0):
                                echo GraficiGoogle::widget([
                                    'visualization' => 'PieChart',
                                    'data' => $risposte['standard'][$Domanda->id],
                                    'options' => [
                                        'title' => $Domanda->domanda,
                                        //'subtitle' => 'Tutti i partecipanti, iscritti al primo giorno',
                                        //'titleTextStyle' => ['color' => 'red', 'fontSize' => 45],
                                        'is3D' => true,
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
                                        'height' => 500,
                                        'legend' => [
                                        // 'position' => 'none',
                                        ],
                                        'vAxis' => [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Numero occorrenze'),
                                            'gridlines' => [
                                                'color' => null  //set grid line transparent
                                            ]],
                                        'hAxis' => ['title' => 'Risposte'],
                                    ]
                                ]);
                            else :
                                ?>
                                <strong><?= $Domanda->domanda ?></strong>
                                <br><?= AmosSondaggi::t('amossondaggi', 'Non sono presenti risposte.') ?><br><br>
                            <?php endif; ?>
                            <?php
                        }
//                    foreach ($criteri->all() as $Criteri) {
                        ?>
                        <?php if ($ind == 0): ?>
                            <h3><?= $criteri->one()->sondaggiDomandePagine->titolo ?></h3>
                            <h4><?= $criteri->one()->sondaggiDomandePagine->descrizione ?></h4>
                            <?php
                            $ind++;
                        endif;
                        ?>
                        <div class="col-lg-12">
                            <?php
                            if (!empty($risposte['criteri']) && count($risposte['criteri'] > 0)):
                                echo '<div class="col-xs-12">'.GraficiGoogle::widget([
                                    'visualization' => 'ColumnChart',
                                    'data' => $risposte['grafico_criteri'],
                                    'options' => [
                                        'title' => strip_tags($criteri->one()->introduzione),
                                        //'subtitle' => 'Tutti i partecipanti, iscritti al primo giorno',
                                        //'titleTextStyle' => ['color' => 'red', 'fontSize' => 45],
                                        'is3D' => true,
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
                                        'height' => 500,
                                        'legend' => [
                                        // 'position' => 'none',
                                        ],
                                        'vAxis' => [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Punteggi'),
                                            'gridlines' => [
                                                'color' => null  //set grid line transparent
                                            ]],
                                        'hAxis' => ['title' => strip_tags($criteri->one()->introduzione)],
                                    ]
                                ]).'</div>';
                                echo \kartik\grid\GridView::widget([
                                    'dataProvider' => new ArrayDataProvider([
                                        'allModels' => $risposte['criteri'],
                                        ]),
                                    'showPageSummary' => true,
                                    'pjax' => true,
                                    'striped' => true,
                                    'hover' => true,
                                    'panel' => ['type' => 'info', 'heading' => AmosSondaggi::t('amossondaggi',
                                            'Report delle valutazioni')],
                                    'toggleDataOptions' => [
                                        'all' => [
                                            'label' => 'Tutto'
                                        ],
                                    ],
                                    'columns' => [
                                        '0' => [
                                            'class' => '\kartik\grid\DataColumn',
                                            'attribute' => '0',
                                            'label' => strip_tags($criteri->one()->introduzione),
                                            'pageSummary' => 'Totale',
                                        ],
                                        '1' => [
                                            'class' => '\kartik\grid\DataColumn',
                                            'attribute' => '1',
                                            'label' => AmosSondaggi::t('amossondaggi', 'Punteggi possibili'),
                                            'hAlign' => 'center',
                                        ],
                                        '2' => [
                                            'class' => '\kartik\grid\DataColumn',
                                            'attribute' => '2',
                                            'label' => AmosSondaggi::t('amossondaggi', 'Valutatori'),
                                            'hAlign' => 'right',
                                        ],
                                        '3' => [
                                            'class' => '\kartik\grid\DataColumn',
                                            'attribute' => '3',
                                            'label' => AmosSondaggi::t('amossondaggi', 'Media'),
                                            'hAlign' => 'right',
                                            'pageSummary' => true,
                                        ],
                                        '4' => [
                                            'class' => '\kartik\grid\DataColumn',
                                            'attribute' => '4',
                                            'label' => AmosSondaggi::t('amossondaggi', 'Totale'),
                                            'hAlign' => 'right',
                                            'pageSummary' => true,
                                        ],
                                    ],
                                ]);
                            else :
                                ?>
                                <br><?= AmosSondaggi::t('amossondaggi', 'Non sono presenti valutazioni.') ?><br><br>
                            <?php endif; ?>
                        </div>
                        <?php
                        //}
                    } else {
                        foreach ($domande->all() as $Domanda) {
                            $isParent = $Domanda->is_parent;
                            ?>
                            <?php if ($ind == 0): ?>
                                <h3><?= $Domanda->sondaggiDomandePagine->titolo ?></h3>
                                <h4><?= $Domanda->sondaggiDomandePagine->descrizione ?></h4>
                                <?php
                                $ind++;
                            endif;
                            ?>
                            <?php
                            if (!empty($risposte[$Domanda->id]) && count($risposte[$Domanda->id]) > 0):
                                echo GraficiGoogle::widget([
                                    'visualization' => ($isParent ? 'ColumnChart' : 'PieChart'),
                                    'data' => $risposte[$Domanda->id],
                                    'options' => [
                                        'title' => $Domanda->domanda,
                                        //'subtitle' => 'Tutti i partecipanti, iscritti al primo giorno',
                                        //'titleTextStyle' => ['color' => 'red', 'fontSize' => 45],
                                        'is3D' => true,
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
                                        'height' => 500,
//                                        'legend' => [
                                        // 'position' => 'none',
//                                        ],
                                        'vAxis' => [
                                            'title' => AmosSondaggi::t('amossondaggi', 'Numero occorrenze'),
                                            'gridlines' => [
                                                'color' => null  //set grid line transparent
                                            ]],
                                        'hAxis' => ['title' => ($isParent ? AmosSondaggi::t('amossondaggi', 'Domande') : AmosSondaggi::t('amossondaggi',
                                                    'Risposte'))],
                                    ]
                                ]);
                            else :
                                ?>
                                <strong><?= $Domanda->domanda ?></strong>
                                <br><?= AmosSondaggi::t('amossondaggi', 'Non sono presenti risposte.') ?><br><br>
                            <?php endif; ?>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php $form = ActiveForm::begin(); ?>
            <div class="col-xs-12 col-sm-4"><?=
                $form->field($filter, 'data_inizio')->widget(DateControl::className(),
                    [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'layout' => '{remove}{input}'
                    ]
                ])->label(AmosSondaggi::t('amossondaggi', 'Data inizio'))
                ?>
            </div>
            <div class="col-xs-12 col-sm-4"><?=
                $form->field($filter, 'data_fine')->widget(DateControl::className(),
                    [
                    'type' => DateControl::FORMAT_DATE,
                    'options' => [
                        'layout' => '{remove}{input}'
                    ]
                ])->label(AmosSondaggi::t('amossondaggi', 'Data fine'))
                ?>
            </div>

            <?php if ($model->getSondaggiPubblicaziones()->one()['tipologie_entita'] > 0): ?>
                <div class="col-xs-12 col-sm-4"><?=
                    $form->field($filter, 'area_formativa')->widget(Select2::classname(),
                        [
                        'data' => ArrayHelper::map(\open20\amos\tag\models\Tag::find()->andWhere(['root' => 1])->andWhere([
                                'lvl' => 1])->asArray()->all(), 'id', 'nome'),
                        //'showToggleAll' => false,
                        'options' => [
                            'placeholder' => AmosSondaggi::t('amossondaggi', 'Digita il nome dell\'Area formativa ...'),
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <?php $nomeAtt = new \yii\db\Expression("concat('[', pei_entita_formative.codice_entita, '] ', pei_entita_formative.titolo) as titolo"); ?>
                    <?=
                    $form->field($filter, 'attivita')->widget(Select2::classname(),
                        [
                        'data' => ArrayHelper::map(frontend\modules\attivitaformative\models\PeiAttivitaFormative::find()
                                ->innerJoin('pei_point_sedi as S', 'S.id = pei_entita_formative.pei_point_sedi_id')
                                ->leftJoin('pei_point as PP', 'S.pei_point_id = PP.id')
                                ->select(['pei_entita_formative.id as id', $nomeAtt])
                                ->orderBy('titolo')
                                ->asArray()->all(), 'id', 'titolo'),
                        'showToggleAll' => false,
                        'options' => [
                            'placeholder' => AmosSondaggi::t('amossondaggi',
                                'Digita il nome o il codice dell\'Attività formativa ...'),
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-xs-12 col-sm-8">
                    <?= Html::submitButton('Cerca', ['class' => 'cerca btn btn-success']); ?>
                </div>
            <?php else: ?>
                <div class="col-xs-12 col-sm-4">
                    <?= Html::submitButton('Cerca', ['class' => 'cerca btn btn-success']); ?>
                </div>
            <?php endif; ?>
        
        <?php ActiveForm::end(); ?>

    </div>

</div>
<div class="col-lg-12 menu-sondaggio-chiudi">
    <?php
    $link = (!empty($url)) ? $url : 'index';
    echo Html::a(AmosSondaggi::t('amossondaggi', 'Chiudi'), [$url], ['class' => 'btn btn-success', 'onclick' => "window.open('', '_self', ''); window.close();"]);
    ?>
</div>
