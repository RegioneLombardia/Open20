<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\views\discussioni-topic-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\discussioni\AmosDiscussioni;
use yii\base\Widget;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniTopic $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = \open20\amos\discussioni\AmosDiscussioni::t('amosdiscussioni', '#discussions_wizard_page_title');

?>

<div class="discussioni-topic-wizard-publication col-xs-12 nop">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'discussioni-topic-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    
    <?php if (count(\yii\base\Widget::$stack) && isset($model)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php $moduleCwh = Yii::$app->getModule('cwh'); ?>
                <?php if (isset($moduleCwh) && in_array(get_class($model), $moduleCwh->modelsEnabled) && $moduleCwh->behaviors): ?>
                    <?php /**@var \open20\amos\cwh\AmosCwh $moduleCwh */ ?>
                    <?= Yii::$app->controller->renderFile('@vendor/open20/amos-cwh/src/views/pubblicazione/cwh.php', [
                        'model' => $model,
                        'form' => Widget::$stack[0]
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php $moduleTag = Yii::$app->getModule('tag'); ?>
                <?php if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
                    <?php /**@var \open20\amos\tag\AmosTag $moduleTag */ ?>
                    <?= \open20\amos\tag\widgets\TagWidget::widget([
                        'model' => $model,
                        'attribute' => 'tagValues',
                        'form' => Widget::$stack[0]
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/discussioni/discussioni-topic-wizard/details', 'id' => $model->id]),
        'cancelUrl' => Yii::$app->session->get(AmosDiscussioni::beginCreateNewSessionKey()),
        'contentAlreadyExists' => true
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
