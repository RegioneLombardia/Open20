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
use open20\amos\community\models\Community;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

/**
 * @var \open20\amos\community\models\Community $model
 * @var bool $canPublish
 */

$this->title = AmosCommunity::t('amoscommunity', 'New Community');
if(!is_null($model->parent_id)){
    $this->title = AmosCommunity::t('amoscommunity', '#new_subcommunity');
}
$this->params['breadcrumbs'][] = ['label' => AmosCommunity::t('amoscommunity', 'Community'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$previousStep = 'access-type';
$moduleTag = Yii::$app->getModule('tag');
if (isset($moduleTag) && in_array(Community::className(), $moduleTag->modelsEnabled)) {
    $previousStep = 'tag';
}

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
]);
?>

<div class="completion">

    <div class="row">
        <div class="col-xs-12">
            <p><?= AmosCommunity::tHtml('amoscommunity', "It is now possible to publish the community or to save it as draft in order to add additional information and publish it at a leter time.") ?></p>
            <!-- AmosCommunity::tHtml('amoscommunity',"E' ora possibile pubblicare la community o salvarla in bozza nel caso si vogliano aggiungere ulteriori informazioni e pubblicare in un secondo momento") -->
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 col-sm-6 text-center m-t-30">
                <?= Html::a(AmosIcons::show('square-check', ['class' => 'am-2'], 'dash') .
                    '<p class="icon-inline">' .
                    ($canPublish ? AmosCommunity::tHtml("amoscommunity", "Publish") : AmosCommunity::tHtml("amoscommunity", "Publication request"))
                    . '</p>'
                    ,
                    Yii::$app->urlManager->createUrl(['community/community/publish', 'id' => $model->id]),
                    [
                        'title' => $canPublish ? AmosCommunity::tHtml("amoscommunity", "Publish") : AmosCommunity::tHtml("amoscommunity", "Publication request"),
                        'class' => 'btn btn-navigation-primary publish-icon',
                    ],
                    false //verify permission to Url
                )
                ?>
            </div>
            <div class="col-xs-12 col-sm-6 text-center m-t-30">
                <?= Html::a(
                    AmosIcons::show('square-editor', ['class' => 'am-2'], 'dash') .
                    '<p class="icon-inline">' . AmosCommunity::tHtml("amoscommunity", "Save as draft") . '</p>',
                    Yii::$app->urlManager->createUrl(['community/community/view', 'id' => $model->id, 'tabActive' => 'tab-registry']),
                    [
                        'title' => AmosCommunity::t("amoscommunity", "Save as draft"),
                        'class' => 'btn btn-secondary save-draft-icon',
                    ]
                )
                ?>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'id')->hiddenInput()->label('') ?>
</div>

<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/community/community-wizard/'.$previousStep, 'id' => $model->id]),
    'cancelUrl' => Yii::$app->session->get(AmosCommunity::beginCreateNewSessionKey()),
    'contentAlreadyExists' => true,
    'viewContinueBtn' => false
]) ?>

<?php ActiveForm::end(); ?>
