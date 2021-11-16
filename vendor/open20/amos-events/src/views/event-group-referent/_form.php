<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos/events/src/views 
 */
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;
use open20\amos\events\AmosEvents;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventGroupReferent $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="event-group-referent-form col-xs-12 nop">

    <?php
    $form = ActiveForm::begin([
            'options' => [
                'id' => 'event-group-referent_'.((isset($fid)) ? $fid : 0),
                'data-fid' => (isset($fid)) ? $fid : 0,
                'data-field' => ((isset($dataField)) ? $dataField : ''),
                'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
                'class' => ((isset($class)) ? $class : '')
            ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12">
            <h2 class="subtitle-form"><?= \open20\amos\events\AmosEvents::t('amosevents', 'Settings') ?></h2>
            <div><!-- denominazione string -->
                <?= $form->field($model, 'denominazione')->textInput(['maxlength' => true]) ?><!-- descrizione text -->
                <?=
                $form->field($model, 'descrizione')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                    'options' => ['placeholder' => AmosEvents::t('amosevents', 'Scrivi il programma del tuo evento. Usa un massimo di 1500 caratteri.')],
                    'clientOptions' => [
                        'toolbar' => \open20\amos\events\utility\EventsUtility::getToolbarTextEditor(),

                    ]
                ])
                ?><!-- note text -->
                <?=
                $form->field($model, 'note')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                    'options' => ['placeholder' => AmosEvents::t('amosevents', 'Scrivi il programma del tuo evento. Usa un massimo di 1500 caratteri.')],
                    'clientOptions' => [
                        'toolbar' => \open20\amos\events\utility\EventsUtility::getToolbarTextEditor(),

                    ]
                ]);
                ?><?= RequiredFieldsTipWidget::widget(); ?>

                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?><?php ActiveForm::end(); ?>
                </div>

        </div>
        <div class="clearfix"></div>

    </div>
</div>
