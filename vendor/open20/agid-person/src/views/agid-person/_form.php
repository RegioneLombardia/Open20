<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\core\forms\AccordionWidget;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\models\Documenti;
use open20\amos\seo\widgets\SeoWidget;
use open20\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\person\models\AgidPerson;
use open20\agid\person\models\AgidPersonContentType;
use open20\agid\person\models\AgidPersonType;
use open20\agid\person\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm as ActiveForm2;
use open20\amos\attachments\components\CropInput;


$js2 = <<<JS
	var prettyUrlCal = $('#agidperson-nomecognome');
	var name=$("#agidperson-name");
	var surname=$("#agidperson-surname");
	name.keyup(function() {	setPrettyUrlPerson(); }).keyup();
	surname.keyup(function() {setPrettyUrlPerson();}).keyup();
	
	function setPrettyUrlPerson(){
	    var value = '';
	    
	    if (name.val() != ''){
		    var value= name.val().concat('-');
		}
	    if (surname.val() != ''){
		        var value= value.concat(surname.val());
		    }
		//if ($('#seodata-pretty_url').val() == ''){
			prettyUrlCal.val( value );
		    prettyUrlCal.text( value );
		    $('#agidperson-nomecognome').trigger("change"); 
			//}
	}
	$(document).ready(function(){
	        $('#agid_person_type_id').trigger('select2:select');
	        setPrettyUrlPerson();
	});
JS;
$this->registerJs($js2);

$url = Url::to(['document-list']);
/**
 * @var View $this
 * @var AgidPerson $model
 * @var ActiveForm2 $form
 */


?>
<div class="agid-person-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'agid-person_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

	<div class="row">
        <!--nome: corretto-->
        <div class="col-xs-12">
            <h2 class="subtitle-form">Nome</h2>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
            </div>

        </div>

        <!--ruolo e tipologia: corretto-->
        <div class="col-xs-12">
            <h2 class="subtitle-form">Ruolo e tipologia</h2>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'role')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'agid_person_type_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidPersonType::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'id' => 'agid_person_type_id',
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...'
                    ],
                    // 'pluginEvents' => [
                    //     "select2:select" => "enableDisablePP"
                    // ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12"><!-- name string -->
                <?= $form->field($model, 'agid_person_content_type_id')->widget(Select::className(), [
                    'data' => ArrayHelper::map(AgidPersonContentType::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...'
                    ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'role_description')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                    ],
                ]);
                ?>
            </div>
        </div>

        <!--ruolo e tipologia: corretto-->
        <div class="col-xs-12">
            <h2 class="subtitle-form">Organizzazione di riferimento</h2>


            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'manager_org')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => true,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...',
                        'value' => $model->manager_org,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>


        </div>


        <!-- TODO START task 5244  -->
        <div class="col-xs-12">
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'agid_organizational_unit_1_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...',
                        'value' => $model->agid_organizational_unit_1_id,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'person_function_1')->textarea(); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'agid_organizational_unit_2_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...',
                        'value' => $model->agid_organizational_unit_2_id,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'person_function_2')->textarea(); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'agid_organizational_unit_3_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...',
                        'value' => $model->agid_organizational_unit_3_id,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'person_function_3')->textarea(); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'agid_organizational_unit_4_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...',
                        'value' => $model->agid_organizational_unit_4_id,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'person_function_4')->textarea(); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'agid_organizational_unit_5_id')->widget(Select::classname(), [
                    'data' => ArrayHelper::map(AgidOrganizationalUnit::find()->orderBy('name')->all(), 'id', 'name'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'placeholder' => Module::t('amosperson', '#select_choose') . '...',
                        'value' => $model->agid_organizational_unit_5_id,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ]); ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'person_function_5')->textarea(); ?>
            </div>

        </div>




        <!--altre info:coretto-->
        <div class="col-xs-12">
            <h2 class="subtitle-form">Altre informazioni</h2>
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'skills')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'delegation')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12">
                <?php 
                    // $form->field($model, 'photo')->widget(AttachmentsInput::classname(), [
                    //     'options' => [
                    //         'multiple' => false,
                    //     ],
                    //     'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                    //         'maxFileCount' => 1, // Client max files
                    //         'showRemove' => true,
                    //         'indicatorNew' => false,
                    //         'allowedPreviewTypes' => ['image'],
                    //         'previewFileIconSettings' => false,
                    //         'overwriteInitial' => false,
                    //         'layoutTemplates' => false
                    //     ]
                    // ])->label('Foto') 
                ?>

                <?= 
                    $form->field($model, 'photo')->widget(CropInput::classname(), [
                        'jcropOptions' => ['aspectRatio' => '1.7']
                    ])
                ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'date_start_settlement')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE
                ])
                ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'date_end_assignment')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE
                ])
                ?>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?=
                $form->field($model, 'bio')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-6 col xs-12">
                <?=
                $form->field($model, 'other_info')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                    ],
                ]);
            ?>
            </div>
        </div>

        <!--contatti:corretto-->
        <div class="col-xs-12">
            <h2 class="subtitle-form">Contatti</h2>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-md-6 col xs-12">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <!--documenti:corretto-->
        <div class="col-xs-12">
            <h2 class="subtitle-form">Documenti</h2>
            <div class="col-md-6 col xs-12">
                <?php
                    $docCvDesc = empty($model->agid_document_cv_id) ? '' : Documenti::findOne($model->agid_document_cv_id)->titolo;
                    echo $form->field($model, 'agid_document_cv_id')->widget(Select2::classname(), [
                        'initValueText' => $docCvDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_cv_id) { return agid_document_cv_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_cv_id) { return agid_document_cv_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12">
                <?php
                    $docNominationDesc = empty($model->agid_document_nomination_id) ? '' : Documenti::findOne($model->agid_document_nomination_id)->titolo;
                    echo $form->field($model, 'agid_document_nomination_id')->widget(Select2::classname(), [
                        'initValueText' => $docNominationDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_nomination_id) { return agid_document_nomination_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_nomination_id) { return agid_document_nomination_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

			<div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?php

                    $docImportDesc = empty($model->agid_document_import_id) ? '' : Documenti::findOne($model->agid_document_import_id)->titolo;
                        echo $form->field($model, 'agid_document_import_id')->widget(Select2::classname(), [
                        'initValueText' => $docImportDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_import_id) { return agid_document_import_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_import_id) { return agid_document_import_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?php
                    $docOtherPostsDesc = empty($model->agid_document_other_posts_id) ? '' : Documenti::findOne($model->agid_document_other_posts_id)->titolo;
                    echo $form->field($model, 'agid_document_other_posts_id')->widget(Select2::classname(), [
                        'initValueText' => $docOtherPostsDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_other_posts_id) { return agid_document_other_posts_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_other_posts_id) { return agid_document_other_posts_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?php
                    $docBalanceDesc = empty($model->agid_document_balance_sheet_id) ? '' : Documenti::findOne($model->agid_document_balance_sheet_id)->titolo;
                    echo $form->field($model, 'agid_document_balance_sheet_id')->widget(Select2::classname(), [
                        'initValueText' => $docBalanceDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_balance_sheet_id) { return agid_document_balance_sheet_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_balance_sheet_id) { return agid_document_balance_sheet_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?php
                    $docTaxDesc = empty($model->agid_document_tax_return_id) ? '' : Documenti::findOne($model->agid_document_tax_return_id)->titolo;
                    echo $form->field($model, 'agid_document_tax_return_id')->widget(Select2::classname(), [
                        'initValueText' => $docTaxDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_tax_return_id) { return agid_document_tax_return_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_tax_return_id) { return agid_document_tax_return_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?php
                    $docElectionExpensesDesc = empty($model->agid_document_election_expenses_id) ? '' : Documenti::findOne($model->agid_document_election_expenses_id)->titolo;
                    echo $form->field($model, 'agid_document_election_expenses_id')->widget(Select2::classname(), [
                        'initValueText' => $docElectionExpensesDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_election_expenses_id) { return agid_document_election_expenses_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_election_expenses_id) { return agid_document_election_expenses_id.text; }'),
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-6 col xs-12 cl-type-person-politica" hidden>
                <?php
                    $docCBalanceDesc = empty($model->agid_document_changes_balance_sheet_id) ? '' : Documenti::findOne($model->agid_document_changes_balance_sheet_id)->titolo;
                    echo $form->field($model, 'agid_document_changes_balance_sheet_id')->widget(Select2::classname(), [
                        'initValueText' => $docCBalanceDesc,
                        'options' => ['multiple'=>false, 'placeholder' => 'Search for a document ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'error'; }"),
                            ],
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(agid_document_changes_balance_sheet_id) { return agid_document_changes_balance_sheet_id.text; }'),
                            'templateSelection' => new JsExpression('function (agid_document_changes_balance_sheet_id) { return agid_document_changes_balance_sheet_id.text; }'),
                        ],
                    ]);
                ?>
			</div>
        </div>

        <!--tip campi obbligatori-->
        <div class="col-xs-12">
            <div class="col-md-6 col-xs-12">
                <?= RequiredFieldsTipWidget::widget(); ?>
            </div>
        </div>



        <div class="row">
            <div class="col-md-12 ">
                <?php
                    //Html::tag('h2', \Yii::t('projectcards', '#settings_receiver_title'), ['class' => 'subtitle-form'])
                ?>
                <?= Html::tag('h2', Module::t('amosperson', '#tag'), ['class' => 'subtitle-form']) ?>
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
        </div>


        <div class="row">
	        <div style="display: none"><?= $form->field($model, 'nomecognome')->textInput(['maxlength' => true]) ?></div>

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


       <!--bottoni finali-->
        <div class="col-xs-12">
        <?=
            WorkflowTransitionButtonsWidget::widget([
                'form' => $form,
                'model' => $model,
                'workflowId' => AgidPerson::AGID_PERSON_WORKFLOW,
                'viewWidgetOnNewRecord' => true,
                'closeButton' => Html::a(Module::t('person', 'Annulla'),
                    $referrer ? $referrer : '/person/agid-person',
                    [
                        'class' => 'btn btn-outline-primary'
                    ]
                ),
                'initialStatusName' => "DRAFT",
                'initialStatus' => AgidPerson::AGID_PERSON_STATUS_DRAFT,
                'draftButtons' => [
                    'default' => [
                        'button' => Html::submitButton(
                            Module::t('person', 'Salva'),
                            ['class' => 'btn btn-outline-primary']
                        ),
                    ],
                ]
            ]);
            ?>

            <?php ActiveForm::end(); ?>
		</div>
    </div>
</div>





<?php

$script = <<< JS

    $('#agidperson-telephone').keyup(function(){

        this.value = this.value.replace(/[^0-9\.]/g,'');

    });



    $("#agid_person_type_id").ready(function(){

        var agid_person_type = $("#agid_person_type_id option:selected").text();

        if( agid_person_type == "" || agid_person_type == "Politica"){

            $(".cl-type-person-politica").removeAttr("hidden");

        }else{
            $(".cl-type-person-politica").attr("hidden", "hidden");
        }
    });

    $("#agid_person_type_id").change(function(){

        var agid_person_type = $("#agid_person_type_id option:selected").text();

        if( agid_person_type == "Politica" ){

            $(".cl-type-person-politica").removeAttr("hidden");

        }else{

            $(".cl-type-person-politica").attr("hidden", "hidden");
        }
    });


JS;
$this->registerJs($script);

?>




