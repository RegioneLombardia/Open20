<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
 */

use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\seo\widgets\SeoWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use kartik\select2\Select2;
use open20\agid\dataset\models\AgidDataset;
use open20\agid\dataset\models\AgidDatasetContentType;
use open20\agid\dataset\models\AgidDatasetType;
use open20\agid\dataset\Module;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm as ActiveForm2;

/**
 * @var View $this
 * @var AgidDataset $model
 * @var ActiveForm2 $form
 */

?>
<div class="agid-dataset-form ">

    <?php 
        $form = ActiveForm::begin([
            'options' => [
                'id' => 'agid-dataset_' . ((isset($fid)) ? $fid : 0),
                'data-fid' => (isset($fid)) ? $fid : 0,
                'data-field' => ((isset($dataField)) ? $dataField : ''),
                'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
                'class' => ((isset($class)) ? $class : ''),
            ],
        ]);
    ?>

    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

        <div class="row">
            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form">Titolo</h2>
                <div class="row">
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'title')->textInput([
                                'maxlength' => true,
                            ])->label(Module::t('amosdataset', 'Titolo'))
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form">Categoria</h2>
                <div class="row">
                    <div class="col-md-6">
                        <?= 
                            $form->field($model, 'agid_dataset_content_type_id')->widget(Select2::className(),[
                                'data' => ArrayHelper::map(AgidDatasetContentType::find()->asArray()->all(), 'id', 'name'),
                                'options' => [
                                    'placeholder' => Module::t('amosdataset', 'Seleziona...'),
                                    'multiple' => false,
                                ],
                            ])->label(Module::t('amosdataset', 'Tipologia content type'))
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= 
                            $form->field($model, 'agid_dataset_type_id')->widget(Select2::className(),[
                                'data' => ArrayHelper::map(AgidDatasetType::find()->asArray()->all(), 'id', 'name'),
                                'options' => [
                                    'placeholder' => Module::t('amosdataset', 'Seleziona...'),
                                    'multiple' => false,
                                ],
                            ])->label(Module::t('amosdataset', 'Tipologia di dataset'))
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 section-form">
                <h2 class="subtitle-form">Descrizione</h2>
                <div class="row">
                    <div class="col-md-6">
                        <?=
                            $form->field($model, 'description')->widget(TextEditorWidget::className(), [
                                'clientOptions' => [
                                    'id' => 'description',
                                    'lang' => substr(Yii::$app->language, 0, 2),
                                ],
                            ])->label(Module::t('amosdataset', 'Descrizione'));
                        ?>
                    </div>
                </div>
                
            </div>

            <div class="col-md-12">
                <?= Html::tag('h2', \Yii::t('amosdataset', 'Tassonomia argomenti'), ['class' => 'subtitle-form']) ?>
                <?php
                    $moduleCwh = Yii::$app->getModule('cwh'); 

                    $scope = null;
                    if (!empty($moduleCwh)) {
                        $scope = $moduleCwh->getCwhScope();
                    }

                    echo \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                        'model' => $model,
                        'moduleCwh' => $moduleCwh,
                        'scope' => $scope
                    ]);
                ?>
            </div>

            <div class="row">
                <div class="col-xs-12">
                <?php if (Yii::$app->getModule('seo')) : ?>
                        <?=
                        AccordionWidget::widget([
                            'items' => [
                                [
                                    'header' => Module::t('amosperson', '#settings_seo_title'),
                                    'content' => SeoWidget::widget([
                                        'contentModel' => $model,
                                    ]),
                                ]
                            ],
                            'headerOptions' => ['tag' => 'h2'],
                            'options' => Yii::$app->user->can('SEO_USER') ? [] : ['style' => 'display:none;'],
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
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="col-md-12 nop">
                    <?=
                        WorkflowTransitionButtonsWidget::widget([
                            'form' => $form,
                            'model' => $model,
                            'workflowId' => AgidDataset::AGID_DATASET_WORKFLOW,
                            'viewWidgetOnNewRecord' => true,
                            
                            'closeButton' => Html::a( Module::t('amosdataset', 'Annulla'),
                                $referrer ? $referrer : '/dataset/agid-dataset',
                                [
                                    'class' => 'btn btn-outline-primary'
                                ]
                            ),
                            'draftButtons' => [
                                'default' => [
                                    'button' => Html::submitButton(
                                        Module::t('amosdataset', 'Salva'),
                                        ['class' => 'btn btn-primary']
                                    ),
                                ],
                            ],
                            'initialStatusName' => "DRAFT",
                            'initialStatus' => AgidDataset::AGID_DATASET_WORKFLOW_STATUS_DRAFT,
                        ]);
                    ?>
                </div>
            </div> 



        <div class="row">
            <div class="col-xs-12">
            <!-- <h2 class="subtitle-form">Settings</h2> -->

            <div class="col-md-8 col xs-12">
                <!-- < ?= RequiredFieldsTipWidget::widget();?> -->
                <!-- < ?= CloseSaveButtonWidget::widget(['model' => $model]);?> -->
                <?php ActiveForm::end();?>
            </div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>
