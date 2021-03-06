<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\partnershipprofiles\views\expressions-of-interest
 * @category   CategoryName
 */

use lispa\amos\core\forms\ActiveForm;
use lispa\amos\core\forms\CloseSaveButtonWidget;
use lispa\amos\core\forms\CreatedUpdatedWidget;
use lispa\amos\core\forms\editors\Select;
use lispa\amos\core\forms\RequiredFieldsTipWidget;
use lispa\amos\partnershipprofiles\controllers\ExpressionsOfInterestController;
use lispa\amos\partnershipprofiles\models\ExpressionsOfInterest;
use lispa\amos\partnershipprofiles\Module;
use lispa\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget;
use lispa\amos\workflow\widgets\WorkflowTransitionButtonsWidget;
use lispa\amos\partnershipprofiles\utility\ExpressionsOfInterestUtility;
use lispa\amos\partnershipprofiles\models\PartnershipProfiles;
use lispa\amos\core\helpers\Html;
use yii\redactor\widgets\Redactor;

/**
 * @var yii\web\View $this
 * @var lispa\amos\partnershipprofiles\models\ExpressionsOfInterest $model
 * @var lispa\amos\partnershipprofiles\models\PartnershipProfiles $partnershipProfile
 * @var yii\widgets\ActiveForm $form
 * @var string|null $fid
 */

// Tab ids
$idTabCard = 'tab-card';

/** @var ExpressionsOfInterestController $appController */
$appController = Yii::$app->controller;
$module = \Yii::$app->getModule('partnershipprofiles');
$onlyOneOrganization = $module->enableOnlyOneOrganization;

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'expressions-of-interest_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : ''),
        'enctype' => 'multipart/form-data', // To load images
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
]);
?>

<?= WorkflowTransitionStateDescriptorWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW,
        'classDivIcon' => '',
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => false
    ]);
?>

<div class="<?= Yii::$app->controller->id ?>-form col-xs-12 nop">
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>
    <?= $this->render('parts/partnership_profile_title', ['model' => $model]) ?>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'partnership_offered')->widget(Redactor::className(), [
                'clientOptions' => [
                    'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                    'buttonsHide' => [
                        'image',
                        'file'
                    ],
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'additional_information')->widget(Redactor::className(), [
                'clientOptions' => [
                    'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                    'buttonsHide' => [
                        'image',
                        'file'
                    ],
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'clarifications')->widget(Redactor::className(), [
                'clientOptions' => [
                    'plugins' => ['clips', 'fontcolor', 'imagemanager'],
                    'buttonsHide' => [
                        'image',
                        'file'
                    ],
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
    </div>
    <?php if($onlyOneOrganization) {
        $organization = ExpressionsOfInterestUtility::getOnlyOneOrganization();
        if($organization){
            $classname = get_class($organization);
            $model->user_network_reference =  $classname::tableName(). '-' . $organization->id;
            echo "<div hidden >".$form->field($model, 'user_network_reference')->hiddenInput()->label(false)."</div>" ?>
            <p><strong><?= Module::t('amospartnershipprofiles', '#organization_reference_single') . ': </strong>' . $organization->name?></p>
       <?php } ?>
    <?php } else if (ExpressionsOfInterestUtility::viewCommunityOrOrganizationList($model)){ ?>
        <div class="row">
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'user_network_reference')->widget(Select::className(), [
                    'data' => $appController->getReferenceCommunityOrOrganizationList($model),
                    'options' => [
                        'lang' => substr(Yii::$app->language, 0, 2),
                        'multiple' => false,
                        'placeholder' => Module::t('amospartnershipprofiles', 'Select/Choose') . '...',
                    ]
                ]); ?>
            </div>
        </div>
    <?php } ?>
    <div class="clearfix"></div>

    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>

    <?php
        $statusToRenderToHide = $model->getStatusToRenderToHide();
    ?>

    <?=
    WorkflowTransitionButtonsWidget::widget([
        'form' => $form,
        'model' => $model,
        'workflowId' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW,
        'viewWidgetOnNewRecord' => true,
        //'closeSaveButtonWidget' => CloseSaveButtonWidget::widget($config),
        'closeButton' => Html::a(Module::t('amospartnershipprofiles', 'Annulla'), \Yii::$app->session->get('previousUrl'),
            ['class' => 'btn btn-secondary']),
        'initialStatusName' => "DRAFT",
        'initialStatus' => ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT,
        'statusToRender' => $statusToRenderToHide['statusToRender'],
        'hideSaveDraftStatus' => $statusToRenderToHide['hideDraftStatus'],
        'draftButtons' => [
            ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_TOVALIDATE => [
                'button' => Html::submitButton(Module::t('amospartnershipprofiles', 'Salva'), ['class' => 'btn btn-workflow']),
                'description' => 'le modifiche e mantieni la notizia in "richiesta di pubblicazione"'
            ],
            ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_RELEVANT => [
                'button' => \lispa\amos\core\helpers\Html::submitButton(Module::t('amospartnershipprofiles', 'Salva'),
                    ['class' => 'btn btn-workflow']),
                'description' => Module::t('amospartnershipprofiles', 'le modifiche e mantieni la soluzione "pubblicata"'),
            ],
            'default' => [
                'button' => Html::submitButton(Module::t('amospartnershipprofiles', 'Salva in bozza'),
                    ['class' => 'btn btn-workflow']),
                'description' => Module::t('amospartnershipprofiles', 'potrai richiedere la pubblicazione in seguito'),
            ]
        ]
    ]);
    ?>
</div>
<?php ActiveForm::end(); ?>
