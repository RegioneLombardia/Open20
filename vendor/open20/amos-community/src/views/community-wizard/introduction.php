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
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var \open20\amos\community\models\Community $model
 */
$parentId = Yii::$app->request->getQueryParam('parentId');
if(is_null($model->parent_id) && !is_null($parentId)){
    $model->parent_id = $parentId;
}
$isSubCommunity =  !is_null($model->parent_id);
$this->title = AmosCommunity::t('amoscommunity', 'New Community');
if($isSubCommunity){
    $this->title = AmosCommunity::t('amoscommunity', '#new_subcommunity');
}
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$urlCreate = ['community/community/create'];
if($isSubCommunity){
    $urlCreate['parentId'] =  $model->parent_id;
}

?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'id' => 'community_form_' . $model->id,
        'class' => 'form',
        'enctype' => 'multipart/form-data', //to load images
        'enableClientValidation' => true,
        'errorSummaryCssClass' => 'error-summary alert alert-error'
    ]
]);
?>

<div class="introduction">
    <div class="row">
        <div class="col-xs-12">
            <div class="pull-left">
                <div class="like-widget-img color-lightPrimary ">
                    <?= AmosIcons::show('group', [], 'dash'); ?>
                </div>
            </div>
            <div class="text-wrapper">
                <p><?= AmosCommunity::tHtml('amoscommunity', 'In the next steps, listed on the above progress bar, you will be asked to insert title, description and possibly logo for the community you wish to create, access type for participants (open, private or closed). You will be able to define regional strategic issues on which the community work will take place and technological scopes within its competence.') ?>
                </p>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="salta-wizard">
                <?= Html::a(AmosCommunity::tHtml('amoscommunity', "<strong>Skip this wizard</strong>, directly fill in community profile"),
                    Yii::$app->urlManager->createUrl($urlCreate)) ?>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'parent_id')->hiddenInput(['value' => $model->parent_id])->label(false) ?>
</div>

<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'viewPreviousBtn' => false,
    'cancelUrl' => Yii::$app->session->get(AmosCommunity::beginCreateNewSessionKey())
]) ?>

<?php ActiveForm::end(); ?>
