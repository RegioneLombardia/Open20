<?php

use frontend\modules\metrics\models\Operators;
use open20\design\components\bootstrapitalia\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * @var $model Operators
 * @var $data array
 *
 */

$this->title = 'Reportistica';
$this->params['breadcrumbs'][] = $this->title;


$dateControlConf = [
    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
    'ajaxConversion' => false,
    'widgetOptions' => [
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]
];

ArrayHelper::multisort($data['all-operators'], ['nome', 'cognome'], [SORT_ASC, SORT_ASC]);
$usersExportColumns = [
    'matricola' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'matricola',
        'label' => 'Matricola',
    ],
    'nome' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nome',
        'label' => 'Nome',
    ],
    'cognome' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cognome',
        'label' => 'Cognome',
    ],
    'codice_fiscale' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'codice_fiscale',
        'label' => 'Codice fiscale',
    ],
    'profile' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'profile',
        'label' => 'Ruolo',
    ],
    'email' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'email',
        'label' => 'Email',
    ],
    'ultimo_accesso' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ultimo_accesso',
        'label' => 'Ultimo login',
        'value' => function ($model) {
            return Yii::$app->formatter->asDatetime($model['ultimo_accesso'], 'php:d/m/Y h:i:s');
        }
    ],
    'data_inizio_servizio' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'data_inizio_servizio',
        'label' => 'Inizio servizio',
        'value' => function ($model) {
            if (empty($model['data_inizio_servizio'])) return '';
            return Yii::$app->formatter->asDate($model['data_inizio_servizio'], 'php:d/m/Y');
        }

    ],
    'data_fine_servizio' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'data_fine_servizio',
        'label' => 'Fine servizio',
        'value' => function ($model) {
            if (empty($model['data_fine_servizio'])) return '';
            return Yii::$app->formatter->asDate($model['data_fine_servizio'], 'php:d/m/Y');
        }
    ],
    'active' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'active',
        'label' => 'Utente attivo in piattaforma',
        'value' => function ($model) {
            return ($model['active'] > 0) ? 'attivo' : 'inattivo';
        }
    ],
];


$contentExportColumns = [
    'type' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'type',
        'label' => 'Tipo contenuto',
    ],
    'created_at' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'created_at',
        'label' => 'Creato il',
        'value' => function ($model) {
            return Yii::$app->formatter->asDatetime($model['created_at'], 'php:d/m/Y h:i:s');
        }
    ],
    'nominativo' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nominativo',
        'label' => 'Nominativo',
        'value' => function ($model) {
            $up = \open20\amos\admin\models\UserProfile::findOne(['user_id' => $model['created_by']]);
            return $up->getNomeCognome();
        }
    ],
    'title' => [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'title',
        'label' => 'Titolo',
    ],
];

$fileSuffix = '';
if ($model->start_operators_date) {
    $fileSuffix .=  '-dal-' . Yii::$app->formatter->asDate($model->start_operators_date, 'php:d-m-Y');
}
if ($model->end_operators_date) {
    $fileSuffix .=  '-al-' . Yii::$app->formatter->asDate($model->end_operators_date, 'php:d-m-Y');
}

?>
<div class="loading" style="display: none;"></div>

<p class="lead">
    In questa pagina è possibile effettuare una ricerca per contenuti e operatori e create un report in base alle selezioni desiderate.
    E' possibile combinare la ricerca sia per contenuti che per operatori. I dati estratti possono essere esportati in diversi formati.
</p>

<div class="row m-t-40">
    <div class="col-md-3">
        <?php
        $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',

            // 'fieldConfig' => ['options' => ['class' => 'form-group mb-3 mr-2 me-2']], // spacing form field groups
            'options' => [
                'class' => 'default-form',
                'id' => 'search-id'
            ]
        ]);
        ?>
        <h6>Ricerca per contenuti</h6>
        <?php
        echo $form->field($model, 'start_contents_date')->widget(\kartik\datecontrol\DateControl::className(), $dateControlConf)->hint('Data inizio ricerca');
        ?>
        <?php
        echo $form->field($model, 'end_contents_date')->widget(\kartik\datecontrol\DateControl::className(), $dateControlConf)->hint('Data fine ricerca');
        ?>
        <?php
        echo $form->field($model, 'content_type')->widget(\kartik\select2\Select2::className(), [
            'data' => $model->getContentTypeChoices(),
            'options' => [
                'multiple' => true,
                'id' => 'content_type_choice-id',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => \frontend\modules\metrics\Module::t('metrics', 'Seleziona...')
            ]
        ]);
        ?>
        <?php
        echo $form->field($model, 'content_text')->input([]);
        ?>
        <?php
        // echo $form->field($model, 'content_tag')->input([]);
        ?>
        <h6>Ricerca per operatori</h6>
        <?php
        echo $form->field($model, 'start_operators_date')->widget(\kartik\datecontrol\DateControl::className(), $dateControlConf)->hint('Inizio data servizio');
        ?>
        <?php
        echo $form->field($model, 'end_operators_date')->widget(\kartik\datecontrol\DateControl::className(), $dateControlConf)->hint('Fine data servizio');
        ?>
        <?php
        echo $form->field($model, 'operator_type')->widget(\kartik\select2\Select2::className(), [
            'data' => $model->getOperatoreTypeChoices(),
            'options' => [
                'multiple' => true,
                'id' => 'operator_type_choice-id',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => \frontend\modules\metrics\Module::t('metrics', 'Seleziona...')
            ]
        ]);
        ?>

        <?php
        echo $form->field($model, 'operators')->widget(\kartik\select2\Select2::className(), [
            'data' => $model->getOperatorsForSelect(),
            'options' => [
                'multiple' => true,
                'id' => 'operators-id',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => \frontend\modules\metrics\Module::t('metrics', 'Seleziona...')
            ]
        ]);
        ?>
        <?= \open20\amos\core\helpers\Html::a(Yii::t('statistics', 'Annulla'), \yii\helpers\Url::to('/metrics/operators/index'), ['class' => 'btn btn-navigation-secondary']) ?>
        <?= \open20\amos\core\helpers\Html::submitButton(Yii::t('statistics', 'Cerca'), ['class' => 'btn btn-navigation-primary']) ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-9">
        <?php
        if (empty($data['all-operators']) && empty($data['all-operators'])) {
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        Nessun dato disponibile.
                    </div>
                </div>
            </div>

        <?php
        } else {
        ?>
            <div class="widget-report">
                <h4 class="pull-left">Contenuti</h4>
                <div class="pull-right">
                    <?php
                    echo \kartik\export\ExportMenu::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $data['contents_export'],
                            'pagination' => false,
                        ]),
                        'columns' => $contentExportColumns,
                        'filename' => 'export_contenuti' . $fileSuffix,
                        'messages' =>  [ 'allowPopups' => \Yii::t('app','Disabilita qualisiasi blocker popup nel tuo browser per permettere il download.')]
                    ]);
                    ?>
                </div>
                <div class="clearfix"></div>
                <p><small>Tutti i contenuti creati dagli operatori selezionati</small></p>

                <div class="content-pie m-t-20">


                    <?php
                    echo \open20\amos\sondaggi\components\GraficiGoogle::widget([
                        'visualization' => 'PieChart',
                        'data' => $data['contents'],
                        'options' => [
                            'is3D' => false,
                            'title' => null,
                            'sliceVisibilityThreshold' => 0,
                            'showTip' => true,
                            'legend' => [
                                'position' => 'top',
                                'maxLines' => 2,
                                'alignment' => 'center',
                            ],
                            'tooltip' => [
                                'trigger' => 'selection',
                            ],
                            'height' => 500,
                        ]
                    ]);
                    ?>
                </div>
                <div class="row">
                    <?php
                    $fist = true;
                    foreach ($data['contents'] as $val) :
                        if ($fist) {
                            $fist = false;
                            continue;
                        }
                        $contentType = $val[0];
                        $contentNumber = $val[1];
                    ?>
                        <div class="col-sm-3 col-xs-6">
                            <div class="item-contenuti-report">
                                <div class="numero-contenuto"><?= $contentNumber ?></div>
                                <div class="tipo-contenuto"><?= $contentType ?></div>
                                <?php
                                $toRoute[] = '/metrics/operators/content-detail';
                                $qParams = Yii::$app->request->getQueryParams();
                                if (!empty($qParams)) {
                                    $qParams['Operators']['content_type'] = [
                                        0 => $contentType
                                    ];
                                }
                                $qParams['content'] = $contentType;
                                foreach ($qParams as $key => $val) {
                                    $toRoute[$key] = $val;
                                }
                                ?>
                                <a class="btn btn-xs btn-primary" href="<?= \yii\helpers\Url::to($toRoute); ?>">Dettagli</a>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <div class="widget-report">
                <h4 class="pull-left">Operatori</h4>
                
                <span class="pull-right">


                    <?php
                    echo \kartik\export\ExportMenu::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $data['all-operators'],
                            'sort' => [
                                'attributes' => ['active', 'nome', 'cognome'],
                            ],
                            'pagination' => false,
                        ]),
                        'columns' => $usersExportColumns,
                        'filename' => 'export_utenti' . $fileSuffix,
                    ]);
                    ?>
                </span>
                <div class="clearfix"></div>
                <p><small>Tutti gli operatori selezionati</small></p>
                <div class="content-pie m-t-20">
                    <?php
                    echo \open20\amos\sondaggi\components\GraficiGoogle::widget([
                        'visualization' => 'PieChart',
                        'data' => $data['graf-data'],
                        'options' => [
                            'is3D' => false,
                            'title' => null,
                            'sliceVisibilityThreshold' => 0,
                            'showTip' => true,
                            'legend' => [
                                'position' => 'top',
                                'maxLines' => 2,
                                'alignment' => 'center',
                            ],
                            'tooltip' => [
                                'trigger' => 'selection',
                            ],
                            'height' => 500,
                        ]
                    ]);
                    ?>
                </div>
                <?php

                echo \open20\amos\core\views\DataProviderView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $data['all-operators'],
                        'sort' => [
                            'attributes' => ['active', 'nome', 'cognome'],
                        ],
                        'pagination' => false,
                    ]),
                    'currentView' => [
                        'name' => 'list',
                        'label' => 'Elenco operatori',
                        'url' => '?currentView=list'
                    ],
                    'listView' => [
                        'itemView' => '_list_view_item'
                    ],
                ]);
                ?>
            </div>
        <?php
        };
        ?>
    </div>
</div>