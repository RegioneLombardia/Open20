<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\first-access-wizard
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\controllers\FirstAccessWizardController;
use open20\amos\admin\models\UserProfileArea;
use open20\amos\admin\models\UserProfileRole;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;
use yii\web\View;
/**
 * @var \yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

/** @var FirstAccessWizardController $appController */
$appController = Yii::$app->controller;

$roleName = Html::getInputName($model, 'user_profile_role_id');
$areaName = Html::getInputName($model, 'user_profile_area_id');
$otherRoleId = Html::getInputId($model, 'user_profile_role_other');
$otherAreaId = Html::getInputId($model, 'user_profile_area_other');
$js = "
$('input[name=\"" . $roleName . "\"]').on('change', function(event) {
    if ($(this).val() != " . UserProfileRole::OTHER . ") {
        $('#" . $otherRoleId . "').attr('disabled', true).val('');
        $('#" . $otherRoleId . "').hide();
    } else {
        $('#" . $otherRoleId . "').attr('disabled', false);
        $('#" . $otherRoleId . "').show();
    }
});
$('input[name=\"" . $areaName . "\"]').on('change', function(event) {
    if ($(this).val() != " . UserProfileArea::OTHER . ") {
        $('#" . $otherAreaId . "').attr('disabled', true).val('');
        $('#" . $otherAreaId . "').hide();
    } else {
        $('#" . $otherAreaId . "').attr('disabled', false);
        $('#" . $otherAreaId . "').show();
    }
});

if($('input[name=\"" . $roleName . "\"][value=\"".UserProfileRole::OTHER."\"]').attr('checked') == 'checked') {
    $('#" . $otherRoleId . "').show();
}

if($('input[name=\"" . $areaName . "\"][value=\"".UserProfileArea::OTHER."\"]').attr('checked') == 'checked') {
    $('#" . $otherAreaId . "').show();
}

";

$this->registerJs($js, View::POS_READY);

$this->registerCss("
    #" . $otherAreaId . " {
        display:none;
    }
    #" . $otherRoleId . " {
        display:none;
    }
");

?>

<div class="first-access-wizard-role-and-area">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'first-access-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <?= $this->render('parts/header', ['model' => $model]) ?>

    <section>
        <div class="row">
            <div class="col-xs-12">
                <h4><?= AmosAdmin::t('amosadmin', '#faw_rea_text',[
                        'appName' => Yii::$app->name
                    ]) ?></h4>
            </div>
            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'user_profile_role_id', [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}",
                ])
                    ->radioList($appController->getRoles())
                    ->label($model->getAttributeLabel('role'), ['class' => 'bold'])
                    ->hint($model->getAttributeHint('role'), ['class' => 'text-danger clearfix bold'])
                ?>
                <?= $form->field($model, 'user_profile_role_other')->textInput([
                    'maxlength' => true,
                    'readonly' => false,
                    'disabled' => ($model->user_profile_role_id != UserProfileRole::OTHER),
                    'placeholder' => AmosAdmin::t('amosadmin', 'Other Role')
                ])->label(false) ?>
            </div>

            <div class="col-md-6 col-xs-12">
                <?= $form->field($model, 'user_profile_area_id', [
                    'template' => "{label}\n{hint}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}"
                ])
                    ->radioList($appController->getAreas())
                    ->label($model->getAttributeLabel('area'), ['class' => 'bold'])
                    ->hint($model->getAttributeHint('area'), ['class' => 'text-danger clearfix bold'])
                ?>
                <?= $form->field($model, 'user_profile_area_other')->textInput([
                    'maxlength' => true,
                    'readonly' => false,
                    'disabled' => ($model->user_profile_area_id != UserProfileArea::OTHER),
                    'placeholder' => AmosAdmin::t('amosadmin', 'Other Area')
                ])->label(false) ?>
            </div>
        </div>
    </section>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/'.AmosAdmin::getModuleName().'/first-access-wizard/introducing-myself'])
    ]) ?>
    <?php ActiveForm::end(); ?>

</div>
