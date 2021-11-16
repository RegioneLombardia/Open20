<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event-wizard
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var \open20\amos\events\models\Event $model
 */

$moduleCwh = Yii::$app->getModule('cwh');
$moduleTag = Yii::$app->getModule('tag');

$this->title = AmosEvents::t('amosevents',"Nuovo Evento");

?>

<div class="event-wizard-publication">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>

    <?php
    $moduleNews = \Yii::$app->getModule(AmosEvents::getModuleName());
    if($moduleNews->hidePubblicationDate == false){?>
    <section>
        <div class="row">
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'publication_date_begin')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                ]); ?>
            </div>
            <div class="col-lg-4 col-sm-4">
                <?= $form->field($model, 'publication_date_end')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                ]); ?>
            </div>
        </div>
    </section>
    <?php } ?>
    <section>
        <div class="row">
            <?php if (isset($moduleCwh) && in_array(get_class($model), $moduleCwh->modelsEnabled)): ?>
                <?= \Yii::$app->controller->renderFile('@vendor/open20/amos-cwh/src/views/pubblicazione/cwh.php',
                    [
                        'model' => $model,
                        'form' => $form

                    ]); ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
                <?= \open20\amos\tag\widgets\TagWidget::widget([
                    'model' => $model,
                    'attribute' => 'tagValues',
                    'form' => \yii\base\Widget::$stack[0]
                ]); ?>
            <?php endif; ?>
        </div>
    </section>

    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/event-wizard/organizational-data', 'id' => $model->id]),
        'continueLabel' => AmosEvents::tHtml('amosevents', 'Finish and save'),
        'continueOptions' => [
            'data-confirm' => AmosEvents::t('amosevents', 'You are finishing the event creation and you cannot go back. Do you want to continue?')
        ],
        'cancelUrl' => Yii::$app->session->get(AmosEvents::beginCreateNewSessionKey())
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
