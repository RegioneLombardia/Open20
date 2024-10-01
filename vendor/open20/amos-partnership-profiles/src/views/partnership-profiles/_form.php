<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsInput;
use open20\amos\attachments\components\AttachmentsTableWithPreview;
use open20\amos\comuni\models\IstatNazioni;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\forms\WorkflowTransitionWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ArrayUtility;
use open20\amos\partnershipprofiles\controllers\PartnershipProfilesController;
use open20\amos\partnershipprofiles\models\DevelopmentStage;
use open20\amos\partnershipprofiles\models\IntellectualProperty;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\partnershipprofiles\models\PartnershipProfilesType;
use open20\amos\partnershipprofiles\models\WorkLanguage;
use open20\amos\partnershipprofiles\Module;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use open20\amos\partnershipprofiles\models\PartnershipProfilesCategoryRoles;

/**
 * @var yii\web\View $this
 * @var open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 * @var yii\widgets\ActiveForm $form
 * @var string|null $fid
 */

// Tab ids
$idTabCard = 'tab-card';
$idTabMoreInformation = 'tab-more-information';
$idTabAttachments = 'tab-attachments';

/** @var PartnershipProfilesController $appController */
$appController = Yii::$app->controller;

$partnershipProfileDateId = Html::getInputId($model, 'partnership_profile_date');
$expirationInMonthsId = Html::getInputId($model, 'expiration_in_months');
$calculatedExpiryDateId = 'calculated-expiry-date-id';

$js = "
    function calcEndDateHour() {
        if (($('#" . $partnershipProfileDateId . "').val() != '') && ($('#" . $expirationInMonthsId . "').val() != '')) {
            var dataArray = {
                partnershipProfileDate: $('#" . $partnershipProfileDateId . "').val(),
                expirationInMonths: $('#" . $expirationInMonthsId . "').val()
            };
            $.ajax({
                url: '" . Url::to(['partnership-profiles/calculate-expiry-date']) . "',
                type: 'post',
                data: dataArray,
                dataType: 'json',
                success: function (response) {
                console.log(response);
                    $('#" . $calculatedExpiryDateId . "').html(response.dateTimeToView);
                }
            });
        } else {
            $('#" . $calculatedExpiryDateId . "').html('-');
        }
    }

    $('#" . $partnershipProfileDateId . "').on('change', function (event) {
        calcEndDateHour();
    });

    $('#" . $expirationInMonthsId . "').on('change', function (event) {
        calcEndDateHour();
    });
    
    calcEndDateHour();
";
$this->registerJs($js, View::POS_READY);

$module = \Yii::$app->getModule('partnershipprofiles');
$moduleCwh = \Yii::$app->getModule('cwh');
$enableCategories = $module->enableCategories;
$communityConfigurationsId = null;

if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    if (isset($scope['community'])) {
        $communityConfigurationsId = 'communityId-' . $scope['community'];
    }
}

$enabledFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'] : (!empty($module->fieldsConfigurations['fields']) ? $module->fieldsConfigurations['fields'] : []);
$enabledTabs = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['tabs']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['tabs'] : (!empty($module->fieldsConfigurations['tabs']) ? $module->fieldsConfigurations['tabs'] : []);

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'partnership-profiles_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'enctype' => 'multipart/form-data', // To load images
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
]);
?>

<?= WorkflowTransitionWidget::widget([
    'form' => $form,
    'model' => $model,
    'workflowId' => PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW,
    'classDivIcon' => 'pull-left',
    'classDivMessage' => 'pull-left message',
    'viewWidgetOnNewRecord' => true,
    'translationCategory' => 'amospartnershipprofiles'
]); ?>

<div class="<?= Yii::$app->controller->id ?>-form col-xs-12">
    <?php $this->beginBlock($idTabCard); ?>
    <?php if (!empty($enabledFields['title']) && $enabledFields['title'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    <?php } ?>
    <?php if (!empty($enabledFields['short_description']) && $enabledFields['short_description'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'short_description')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'placeholder' => Module::t('amospartnershipprofiles', '#partnership_profile_short_desc_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <?php if (!empty($enabledFields['extended_description']) && $enabledFields['extended_description'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'extended_description')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'placeholder' => Module::t('amospartnershipprofiles', '#partnership_profile_extended_desc_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <?php if (!empty($enabledFields['advantages_innovative_aspects']) && $enabledFields['advantages_innovative_aspects'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'advantages_innovative_aspects')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'placeholder' => Module::t('amospartnershipprofiles', '#partnership_profile_advantages_innovative_aspects_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <?php if (!empty($enabledFields['expected_contribution']) && $enabledFields['expected_contribution'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'expected_contribution')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'placeholder' => Module::t('amospartnershipprofiles', '#partnership_profile_expected_contribution_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <?php if (!empty($enabledFields['attrPartnershipProfilesTypesMm']) && $enabledFields['attrPartnershipProfilesTypesMm'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <div>
                    <?= $form->field($model, 'attrPartnershipProfilesTypesMm')->widget(Select::className(), [
                        'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(PartnershipProfilesType::find()->asArray()->all(), 'id', 'name'), 'amospartnershipprofiles'),
                        'options' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                            'multiple' => true,
                            'placeholder' => Module::t('amospartnershipprofiles', 'Select/Choose') . '...',
                            'data-model' => 'partnership_profiles_type',
                            'data-field' => 'name'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ]) ?>
                </div>
            </div>
        <?php } ?>

        <?php if (!empty($enabledFields['other_prospect_desired_collab']) && $enabledFields['other_prospect_desired_collab'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <div>
                    <?= $form->field($model, 'other_prospect_desired_collab')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($enableCategories) {?>
            <?php $otherCategoriesQuery = \open20\amos\partnershipprofiles\models\PartnershipProfilesCategory::find()
                  ->joinWith('partnershipProfilesCategoryRoles')
                ->innerJoin('auth_assignment',
                      'item_name='.PartnershipProfilesCategoryRoles::tableName().'.role and user_id ='.\Yii::$app->user->id)
                ->all();?>

            <div class="col-md-12">
                <?= $form->field($model, 'otherCategories')->widget(Select2::className(), [
                    'options' => [
                        'placeholder' => Module::t('amospartnershipprofiles', 'Select...'),
                        'id' => 'pp_categorie_mm_id-id',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'data' => ArrayHelper::map($otherCategoriesQuery, 'id', 'title')
                ])->label(Module::t('amospartnershipprofiles', 'Categorie')) ?>
            </div>
        <?php } ?>

    </div>
    <?php
    $module = Module::instance();
    if ($module->hidefacilitator == false) {
        echo $this->render('boxes/facilitators_box', ['form' => $form, 'model' => $model]);
    }
    ?>
    <div class="row">
        <?php if (!empty($enabledFields['contact_person']) && $enabledFields['contact_person'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
            </div>
        <?php } ?>

        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'partnership_profile_date')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ]) ?>
        </div>
    </div>

    <?php
    $attrHidden = '';
    if (isset($enabledFields['expiration_in_months']) && $enabledFields['expiration_in_months'] == false) {
        $attrHidden = 'hidden';
    }
    ?>
    <div class="row" <?= $attrHidden ?>>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'expiration_in_months')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <label class="m-l-5"><?= Module::t('amospartnershipprofiles', 'Calculated Expiry Date') ?></label>
            <div id="<?= $calculatedExpiryDateId ?>" class="m-l-5"></div>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php
    $itemsTab[] = [
        'label' => Module::tHtml('amospartnershipprofiles', 'Card'),
        'content' => $this->blocks[$idTabCard],
        'options' => ['id' => $idTabCard]
    ];
    ?>

    <?php $this->beginBlock($idTabMoreInformation); ?>
    <?php if (!empty($enabledFields['english_title']) && $enabledFields['english_title'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'english_title')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    <?php } ?>

    <?php if (!empty($enabledFields['english_short_description']) && $enabledFields['english_short_description'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'english_short_description')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'placeholder' => Module::t('amospartnershipprofiles', '#partnership_profile_english_short_desc_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>

    <?php if (!empty($enabledFields['english_extended_description']) && $enabledFields['english_extended_description'] == true) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'english_extended_description')->widget(TextEditorWidget::className(), [
                    'clientOptions' => [
                        'placeholder' => Module::t('amospartnershipprofiles', '#partnership_profile_english_extended_desc_placeholder'),
                        'lang' => substr(Yii::$app->language, 0, 2)
                    ]
                ]) ?>
            </div>
        </div>
    <?php } ?>


    <div class="row">
        <?php if (!empty($enabledFields['attrPartnershipProfilesCountriesMm']) && $enabledFields['attrPartnershipProfilesCountriesMm'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'attrPartnershipProfilesCountriesMm')->widget(Select::className(), [
                    'data' => ArrayHelper::map(IstatNazioni::find()->orderBy('nome')->asArray()->all(), 'id', 'nome'),
                    'options' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'multiple' => true,
                        'placeholder' => Module::t('amospartnershipprofiles', 'Select/Choose') . '...',
                        'data-model' => 'istat_nazioni',
                        'data-field' => 'nome',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); ?>
            </div>
        <?php } ?>

        <?php if (!empty($enabledFields['willingness_foreign_partners']) && $enabledFields['willingness_foreign_partners'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'willingness_foreign_partners')->dropDownList(Html::getBooleanFieldsValues()) ?>
            </div>
        <?php } ?>

    </div>
    <div class="row">
        <?php if (!empty($enabledFields['work_language_id']) && $enabledFields['work_language_id'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'work_language_id')->widget(Select::className(), [
                    'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(WorkLanguage::find()->asArray()->all(), 'id', 'work_language'), 'amospartnershipprofiles'),
                    'options' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'multiple' => false,
                        'placeholder' => Module::t('amospartnershipprofiles', 'Select/Choose') . '...',
                        'data-model' => 'work_language',
                        'data-field' => 'work_language'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label(Module::t('amospartnershipprofiles', 'Work language')) ?>
            </div>
        <?php } ?>

        <?php if (!empty($enabledFields['other_work_language']) && $enabledFields['other_work_language'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'other_work_language')->textInput(['maxlength' => true]) ?>
            </div>
        <?php } ?>

    </div>
    <div class="row">
        <?php if (!empty($enabledFields['development_stage_id']) && $enabledFields['development_stage_id'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'development_stage_id')->widget(Select::className(), [
                    'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(DevelopmentStage::find()->asArray()->all(), 'id', 'value'), 'amospartnershipprofiles'),
                    'options' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'multiple' => false,
                        'placeholder' => Module::t('amospartnershipprofiles', 'Select/Choose') . '...',
                        'data-model' => 'work_language',
                        'data-field' => 'work_language'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label(Module::t('amospartnershipprofiles', 'Development stage')) ?>
            </div>
        <?php } ?>

        <?php if (!empty($enabledFields['other_development_stage']) && $enabledFields['development_stage_id'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'other_development_stage')->textInput(['maxlength' => true]) ?>
            </div>
        <?php } ?>

    </div>
    <div class="row">
        <?php if (!empty($enabledFields['intellectual_property_id']) && $enabledFields['intellectual_property_id'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'intellectual_property_id')->widget(Select::className(), [
                    'data' => ArrayUtility::translateArrayValues(ArrayHelper::map(IntellectualProperty::find()->asArray()->all(), 'id', 'value'), 'amospartnershipprofiles'),
                    'options' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'multiple' => false,
                        'placeholder' => Module::t('amospartnershipprofiles', 'Select/Choose') . '...',
                        'data-model' => 'work_language',
                        'data-field' => 'work_language'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ])->label(Module::t('amospartnershipprofiles', 'Intellectual property')) ?>
            </div>
        <?php } ?>

        <?php if (!empty($enabledFields['other_intellectual_property']) && $enabledFields['other_intellectual_property'] == true) { ?>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'other_intellectual_property')->textInput(['maxlength' => true]) ?>
            </div>
        <?php } ?>

    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>


    <?php
    if (!empty($enabledTabs[$idTabMoreInformation]) && $enabledTabs[$idTabMoreInformation] == true) {
        $itemsTab[] = [
            'label' => Module::tHtml('amospartnershipprofiles', 'More information'),
            'content' => $this->blocks[$idTabMoreInformation],
            'options' => ['id' => $idTabMoreInformation]
        ];
    }
    ?>

    <?php $this->beginBlock($idTabAttachments); ?>
    <?= $form->field($model, 'partnershipProfileAttachments')->widget(AttachmentsInput::classname(), [
        'options' => [ // Options of the Kartik's FileInput widget
            'multiple' => true, // If you want to allow multiple upload, default to false
        ],
        'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
            'maxFileCount' => 100,
            'showPreview' => false// Client max files
        ]
    ]) ?>
    <?= AttachmentsTableWithPreview::widget([
        'model' => $model,
        'attribute' => 'partnershipProfileAttachments'
    ]) ?>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>

    <?php
    if (!empty($enabledTabs[$idTabAttachments]) && $enabledTabs[$idTabAttachments] == true) {
        $itemsTab[] = [
            'label' => Module::tHtml('amospartnershipprofiles', 'Attachments'),
            'content' => $this->blocks[$idTabAttachments],
            'options' => ['id' => $idTabAttachments]
        ];
    }
    ?>

    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?php
    $closeSaveWidgetConf = [
        'model' => $model,
        'urlClose' => $appController->getFormCloseUrl()
    ];
    ?>
    <?= CloseSaveButtonWidget::widget($closeSaveWidgetConf); ?>
</div>
<?php ActiveForm::end(); ?>
