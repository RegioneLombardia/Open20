<?php
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\helpers\Html;
use \open20\amos\core\forms\ActiveForm;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\gamification\models\Gamification;
use open20\gamification\Module;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var open20\gamification\models\Gamification $model
 * @var yii\widgets\ActiveForm $form
 * @var \open20\amos\core\user\User $data
 */

$moduleSeo = \Yii::$app->getModule('seo');
$hideSeoModuleClass = '';

?>

<div class="gamification-form">
    <?php $form = ActiveForm::begin([
        'options' => [
        'id' => 'gamification' . ((isset($fid))? $fid : 0),
        'data-fid' => (isset($fid))? $fid : 0,
        'data-field' => ((isset($dataField))? $dataField : ''),
        'data-entity' => ((isset($dataEntity))? $dataEntity : ''),
        'class' => ((isset($class))? $class : '')
        ]
    ]); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::tag('h2', 'Informazioni base', ['class' => 'subtitle-form']) ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($model, 'descrizione')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'starting_date')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATETIME
                ])
            ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'finish_date')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATETIME
                ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= AccordionWidget::widget([
                        'items' => [
                            [
                                'header' => 'Punteggi',
                                'content' => $this->render('sections/_points', ['model' => $model,'form' => $form])
                            ]
                        ],
                        'headerOptions' => ['tag' => 'h2'],
                        'clientOptions' => [
                            'collapsible' => true,
                            'active' => 'false',
                            'icons' => [
                                'header' => 'ui-icon-amos am am-plus-square',
                                'activeHeader' => 'ui-icon-amos am am-minus-square',
                            ]
                        ],
                    ]);
            ?>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12 col xs-12">
            <?php

            $statusToRenderToHide = $model->getStatusToRenderToHide();

            $draftBtnText = $model->isNewRecord ? Module::t('gamification', 'Salva in bozza') : Module::t('gamification', 'Salva');
            $draftBtnDescription = $model->isNewRecord ? Module::t('gamification', "Potrai richiedere l'apertura della gamification in seguito") : Module::t('gamification', "Salva e mantieni lo stato attuale");
            $draftButtons = [
                    'default' => [
                        'button' => Html::submitButton($draftBtnText, ['class' => 'btn btn-primary']),
                        'description' => $draftBtnDescription,
                    ],

            ];

            echo WorkflowTransitionButtonsWidget::widget([
                'form' => $form,
                'model' => $model,
                'workflowId' => Gamification::GAMIFICATION_WORKFLOW,
                'viewWidgetOnNewRecord' => true,
                //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
                'closeButton' => Html::a(
                    Module::t('gamification', 'Annulla'),
                    'index',
                    ['class' => 'btn btn-secondary']
                ),
                'initialStatusName' => "DRAFT",
                'initialStatus' => Gamification::GAMIFICATION_WORKFLOW_STATUS_DRAFT,
                'statusToRender' => $statusToRenderToHide['statusToRender'],
                //POII-1147 gli utenti validatore/facilitatore o ADMIN possono sempre salvare la news => parametro a false
                //altrimenti se stato VALIDATO => pulsante salva nascosto
                'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
                'draftButtons' => $draftButtons
            ]);
            ?>
            <?php ActiveForm::end(); ?></div><div class="col-md-4 col xs-12">

        </div>
    </div>

</div>
