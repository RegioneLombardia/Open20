<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\views\agid-service
 * @category   CategoryName
 */

use open20\amos\attachments\components\CropInput;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\widgets\DestinatariPlusTagWidget;
use open20\amos\documenti\models\Documenti;
use open20\amos\seo\widgets\SeoWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\service\models\AgidService;
use open20\agid\service\models\AgidServiceContentType;
use open20\agid\service\models\AgidServiceStatus;
use open20\agid\service\models\AgidServiceType;
use open20\agid\service\Module;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm as ActiveForm2;

/**
 * @var View $this
 * @var AgidService $model
 * @var ActiveForm2 $form
 * @var AmosCwh $moduleCwh
 * @var array $scope
 */

?>
<div class="agid-service-form">
    
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'agid-service_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <!--sezione nome-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', '#title'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>

        <!--sezione tipologia -->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', 'Tipologia'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'agid_content_type_service_id')->widget(Select::className(), [
                        'data' => ArrayHelper::map(AgidServiceContentType::find()->orderBy('name')->all(), 'id', 'name'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'multiple' => false,
                            'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                        ]
                    ]); ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'agid_service_type_id')->widget(Select::className(), [
                        'data' => ArrayHelper::map(AgidServiceType::find()->orderBy('name')->all(), 'id', 'name'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'multiple' => false,
                            'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                        ]
                    ]); ?>
                </div>
                
            </div>

        </div>

        <!--sezione descrizione-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', '#description'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'long_description')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'recipients_description')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
            </div>

            
        </div>

        

        <!--sezione stato-->
        <div class="col-xs-12 ">
            <h2 class="subtitle-form"><?= Module::t('amosservice', 'Stato del servizio'); ?></h2>

            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'agid_service_status_id')->widget(Select::className(), [
                        'data' => ArrayHelper::map(AgidServiceStatus::find()->orderBy('name')->all(), 'id', 'name'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'multiple' => false,
                            'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ]); ?>
                </div>
                <div class="col-md-6 "><!-- name string -->
                    <?= $form->field($model, 'service_status_motivation')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
            </div>
            
        </div>

       


        <!--sezione altre informazioni-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', '#other_information'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'persons_apply')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'geographical_apply')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-6 ">
                    <?= $form->field($model, 'procedure_apply')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'output')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'outcome_procedure_apply')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                
            </div>
        </div>


        <!--sezione immagine-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', 'Immagine'); ?></h2>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'agidServiceImage')->widget(CropInput::classname(), [
                        'jcropOptions' => ['aspectRatio' => '1.7']
                    ]) ?>
                </div>
            </div>
        </div>


        <!--sezione doc-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', 'Documenti'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                            <?= $form->field($model, 'documenti')->widget(Select::className(), [
                                'data' => ArrayHelper::map(Documenti::find()->orderBy('titolo')->asArray()->all(), 'id', 'titolo'),
                                'language' => substr(Yii::$app->language, 0, 2),
                                'options' => [
                                    'multiple' => true,
                                    'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ]
                            ]); ?>
                </div>
            </div>
        </div>

        


        
        <!--sezione canali-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', '#channels'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'digital_channel_url')->textInput(['maxlength' => true]) ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'authentication_way')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'physical_channel')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'physical_channel_reservation')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
            </div>
        </div>

       
        <!--sezione utilizzo servizio-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', '#service_use'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'instructions')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'costs')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'constrains')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'phases_deadline')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>

                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'special_case')->widget(TextEditorWidget::className(), [
                        
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?php
                        //  view data
                        foreach ($model->agidServiceRelatedServiceMm as $key => $value) {
                            $agid_service_related_service_mm[] = $value->agid_related_service_id;
                        }
                    ?>
                    <?=
                        $form->field($model, 'agid_service_related_service_mm[]')->widget(\kartik\select2\Select2::className(),[
                            'data' => ArrayHelper::map(\open20\agid\service\models\AgidService::find()
                                ->andWhere(['deleted_at' => null])
                                ->andWhere(['<>', 'id', $model->id ])
                                ->asArray()->all(), 'id', 'name'),
                            'language' => substr(Yii::$app->language, 0, 2),
                            'options' => [
                                'id' => 'agid_service_related_service_mm',
                                'multiple' => true,
                                'placeholder' => 'Seleziona ...',
                                'value' => isset($agid_service_related_service_mm) ? $agid_service_related_service_mm : null,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ]
                        ])->hint(Module::t('amosservice', '#agid_service_related_service_mm'))
                        ->label(Module::t('amosservice', '#agid_service_related_service_mm'));
                    ?>
                </div>
            </div>
        </div>

        <!--sezione contatti-->
        <div class="col-xs-12 section-form">
            <h2 class="subtitle-form"><?= Module::t('amosservice', '#contacts'); ?></h2>
            <div class="row">
                <div class="col-md-6 ">
                    <?php 
                        foreach ($model->agidServiceOrganizationalUnitMm as $key => $value) {

                            $agid_service_organizational_unit_mm[] = $value->agid_organizational_unit_id;
                        }
                    ?>
                    <?= 
                        $form->field($model, 'agid_service_organizational_unit_mm[]')->widget(Select::className(), [
                            'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                            'language' => substr(Yii::$app->language, 0, 2),
                            'options' => [
                                'multiple' => true,
                                'placeholder' => Module::t('amosservice', '#select_choose') . '...',
                                'value' => isset($agid_service_organizational_unit_mm) ? $agid_service_organizational_unit_mm : null,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ])->label(Module::t('amosservice', '#responsible_office')); 
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?= 
                        $form->field($model, 'further_information')->widget(TextEditorWidget::className(), [
                            'clientOptions' => [
                                'lang' => substr(Yii::$app->language, 0, 2),
                            ],
                        ])->label(Module::t('amosservice', '#further_information'));
                    ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'agid_uo_area_id')->widget(Select::className(), [
                        'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                        'language' => substr(Yii::$app->language, 0, 2),
                        'options' => [
                            'multiple' => false,
                            'placeholder' => Module::t('amosservice', '#select_choose') . '...'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ]); ?>
                </div>
                <div class="col-md-6 ">
                    <?= $form->field($model, 'external_links')->widget(TextEditorWidget::className(), [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                        ],
                    ]);
                    ?>
                </div>
                
            </div>
        </div>
            <?php if (!empty($moduleCwh)): ?>
                <div class="col-md-12 col xs-12">
                    <?= Html::tag('h2', Module::t('amosservice', '#tag'), ['class' => 'subtitle-form']) ?>
                    <div class="col-xs-12 receiver-section">
                        <?= DestinatariPlusTagWidget::widget([
                            'model' => $model,
                            'moduleCwh' => $moduleCwh,
                            'scope' => $scope
                        ]); ?>
                    </div>
                </div>
            <?php endif; ?>
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
                            'options' => Yii::$app->user->can('ADMIN') ? [] : ['style' => 'display:none;'],
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
            <div class="col-md-12 col xs-12">
                <?= RequiredFieldsTipWidget::widget(); ?>
                <?= WorkflowTransitionButtonsWidget::widget([
                    'form' => $form,
                    'model' => $model,
                    'workflowId' => AgidService::AGID_SERVICE_WORKFLOW,
                    'viewWidgetOnNewRecord' => true,
                    'closeButton' => Html::a(Module::t('amosservice', 'Annulla'),
                        $referrer ? $referrer : '/service/agid-service',
                        [
                            'class' => 'btn btn-outline-primary'
                        ]
                    ),
                    'initialStatusName' => "DRAFT",
                    'initialStatus' => AgidService::AGID_SERVICE_STATUS_DRAFT,
                    'draftButtons' => [
                        'default' => [
                            'button' => Html::submitButton(
                                Module::t('service', 'Salva'),
                                ['class' => 'btn btn-outline-primary']
                            ),
                        ],
                    ]
                ]); ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
