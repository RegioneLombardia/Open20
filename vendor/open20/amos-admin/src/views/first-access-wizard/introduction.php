<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\first-access-wizard
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;

/**
 * @var yii\web\View $this
 * @var \open20\amos\admin\models\UserProfile $model
 * @var \open20\amos\admin\models\UserProfile $facilitatorUserProfile
 */

?>

<div class="first-access-wizard-introduction">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'first-access-wizard-form',
            'class' => 'form',
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    <?= $form->errorSummary($model, ['class' => 'alert-danger alert fade in', 'role' => 'alert']); ?>
    <section>

        <div class="m-t-35">
            <h3>
                <?= AmosAdmin::t('amosadmin', '#faw_intro_title', [
                    'appName' => Yii::$app->name,
                    'name' => $model->nome,
                    'lastname' => $model->cognome
                ]) ?>
            </h3>
        </div>


        <div>
            <p class="lead">
                <?= AmosAdmin::t('amosadmin', '#faw_intro_text_1', [
                    'appName' => Yii::$app->name,
                ]) ?>
            </p>
            <p>
                <?= AmosAdmin::t('amosadmin', '#faw_intro_text_2') ?>
            </p>
            <p>
                <?= AmosAdmin::t('amosadmin', '#faw_intro_text_3') ?>
            </p>
        </div>
    </section>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'viewPreviousBtn' => false
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>