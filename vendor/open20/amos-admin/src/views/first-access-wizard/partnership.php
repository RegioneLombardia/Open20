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
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\core\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 */

$partnershipUrl = ['/'.AmosAdmin::getModuleName().'/first-access-wizard/associate-prevalent-partnership', 'id' => $model->id, 'viewM2MWidgetGenericSearch' => true];

/* @var \open20\amos\cwh\AmosCwh $moduleCwh */
$moduleCwh = \Yii::$app->getModule('cwh');

$moduleTag = \Yii::$app->getModule('tag');

?>

<div class="first-access-wizard-partnership">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'first-access-wizard-form',
            'class' => 'form',
            'enctype' => 'multipart/form-data', //to load images
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <?= $this->render('parts/header', ['model' => $model]) ?>

    <section>
        <div class="col-xs-12 nop">
            <h4><?= AmosAdmin::t('amosadmin', '#faw_partnership_text') ?></h4>
        </div>
    </section>
    <section>
        <div class="col-xs-12 nop">
            <div>
                <?php if (!is_null($model->prevalentPartnership)): ?>
                    <div class="col-xs-3 col-md-2">
                        <?php
                        $admin =  AmosAdmin::getInstance();
                        /** @var  $organizationsModule OrganizationsModuleInterface*/
                        $organizationsModule = \Yii::$app->getModule($admin->getOrganizationModuleName());
                        $widgetClass = $organizationsModule->getOrganizationCardWidgetClass();
                        echo $widgetClass::widget(['model' => $model->prevalentPartnership]);
                        ?>
                    </div>
                    <div class="col-xs-4">
                        <div><?= $model->prevalentPartnership->getTitle() ?></div>
                        <div><?= Html::a(AmosAdmin::t('amosadmin', 'Change prevalent partnership'), $partnershipUrl, ['class' => 'btn btn-primary']) ?></div>
                    </div>
                <?php else: ?>
                    <div class="col-xs-12 text-center nop">
                        <div><?= AmosAdmin::tHtml('amosadmin', 'Prevalent partnership not selected') ?></div>
                        <div><?= Html::a(AmosAdmin::t('amosadmin', 'Select prevalent partnership'), $partnershipUrl, ['class' => 'btn btn-primary']) ?></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?= $form->field($model, 'prevalent_partnership_id')->hiddenInput()->label(false) ?>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => (isset($moduleCwh) && isset($moduleTag)) ? Yii::$app->getUrlManager()->createUrl(['/'.AmosAdmin::getModuleName().'/first-access-wizard/interests']) : Yii::$app->getUrlManager()->createUrl(['/'.AmosAdmin::getModuleName().'/first-access-wizard/role-and-area']),
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
