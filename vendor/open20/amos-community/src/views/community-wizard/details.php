<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community-wizard
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\assets\AmosCommunityAsset;
use open20\amos\community\utilities\CommunityUtil;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\community\models\Community $model
 */

$isSubCommunity =  !is_null($model->parent_id);
$this->title = AmosCommunity::t('amoscommunity', 'New Community');
if($isSubCommunity){
    $this->title = AmosCommunity::t('amoscommunity', '#new_subcommunity');
}
$assetBundle = AmosCommunityAsset::register($this);

$canCreateRootCommunity = Yii::$app->user->can('COMMUNITY_CREATOR');
?>

<?php
$form = ActiveForm::begin([
        'options' => [
            'id' => 'community_form_' . $model->id,
            'class' => 'form',
            'enctype' => 'multipart/form-data',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]
);
$customView = Yii::$app->getViewPath() . '/imageField.php';
?>

<div class="information">
    <div class="row">
        <div class="col-sm-7 col-md-8">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <br/>
            <?= $form->field($model, 'description')->widget(TextEditorWidget::className(), [
                'options' => [
                    'id' => 'description' . $model->id,
                    'class' => 'description-redactor'
                ],
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2),
                    'placeholder' => AmosCommunity::t('amoscommunity', 'Insert the community description'),
                ],
            ])->label($model->getAttributeLabel('description'), ['for' => 'description' . $model->id]);
            ?>
        </div>
        <div class="col-sm-5 col-md-4">
            <div class="upload_logo col-xs-12">
                <?= $form->field($model,
                    'communityLogo')->widget(\open20\amos\attachments\components\AttachmentsInput::classname(), [
                    'options' => [ // Options of the Kartik's FileInput widget
                        'multiple' => false, // If you want to allow multiple upload, default to false
                    ],
                    'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                        'maxFileCount' => 1 // Client max files
                    ]
                ])->label($model->getAttributeLabel('communityLogo')) ?>
            </div>
        </div>
    </div>
    <!---------------------- choose main Community or child of an other Community/Entity ------------------------------>
    <?php
    $communities = CommunityUtil::getParentList(null, $model->parent_id);
    //this part is visible only if the logged user has permission of community_create whitin a scope/entity
    if (!empty($communities)):
        ?>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <label class="control-label note_asterisk"><?= AmosCommunity::t('amoscommunity',
                        "Community/Organization of reference") ?> <span class="red">*</span></label>

                <?php if($canCreateRootCommunity): ?>
                    <div class="radio">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="pull-left">
                                    <?= Html::img($assetBundle->baseUrl . '/images/root-community.jpg',
                                        ['height' => '100px']) ?>
                                </div>
                                <?= \open20\amos\core\helpers\Html::radio('community_reference',
                                    (!$isSubCommunity == null), [
                                        'label' => AmosCommunity::t('amoscommunity',
                                            "This is a root Community, there is no community/organization of reference"),
                                        'id' => 'root_community',
                                        'value' => 0,
                                        'onclick' => "$('#parent_id').prop('disabled', true); "
                                    ]); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="radio">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="pull-left">
                                <?= Html::img($assetBundle->baseUrl . '/images/child-community.jpg',
                                    ['height' => '100px']) ?>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                            <?= \open20\amos\core\helpers\Html::radio('community_reference',
                                ($model->parent_id != null), [
                                    'label' => AmosCommunity::t('amoscommunity',
                                        "Create this community under the following community/organization of reference:"),
                                    'id' => 'child_community',
                                    'value' => 1,
                                    'onclick' => "$('#parent_id').prop('disabled', false);"
                                ]); ?>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <?= $form->field($model, 'parent_id')->widget(\kartik\select2\Select2::className(), [
                                    'data' => $communities,
                                    'options' => [
                                        'multiple' => false,
                                        'id' => 'parent_id',
                                        'placeholder' => AmosCommunity::t('amoscommunity', 'Select') . '...',
                                        'class' => 'dynamicCreation',
                                        'data-field' => 'id',
                                        'disabled' => !$isSubCommunity
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => $model->isNewRecord  && $canCreateRootCommunity
                                    ]
                                ])->label('', ['class' => 'hidden']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!---------------------- END section Community/Entity of reference --------------------------------------->

</div>

<div class="col-xs-12 note_asterisk nop">
    <p><?= AmosCommunity::tHtml('amoscommunity', 'The fields marked with ') ?><span class="red">*</span><?= AmosCommunity::tHtml('amoscommunity', ' are required') ?></p>
</div>

<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl([
        '/community/community-wizard/introduction',
        'id' => $model->id
    ]),
    'cancelUrl' => Yii::$app->session->get(AmosCommunity::beginCreateNewSessionKey())
]) ?>

<?php ActiveForm::end(); ?>
