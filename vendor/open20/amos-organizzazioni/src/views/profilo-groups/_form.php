<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-groups
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\organizzazioni\Module;
use open20\amos\organizzazioni\widgets\GroupOrganizationsWidget;
use kartik\alert\Alert;

/**
 * @var yii\web\View $this
 * @var \open20\amos\organizzazioni\models\ProfiloGroups $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="profilo-groups-form">
    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // important
    ]);
    ?>

    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12">
            <?= $form->field($model, 'description')->widget(TextEditorWidget::className(), [
                'clientOptions' => [
                    'placeholder' => Module::t('amosinvitations', '#message_field_placeholder'),
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]) ?>
        </div>
        <?php if ($model->isNewRecord): ?>
            <div class="col-xs-12">
                <?= Alert::widget([
                    'type' => Alert::TYPE_WARNING,
                    'body' => Module::t('amosorganizzazioni', '#alert_add_organizations_to_group'),
                    'closeButton' => false
                ]); ?>
            </div>
        <?php else: ?>
            <?= GroupOrganizationsWidget::widget([
                'model' => $model,
                'isUpdate' => true
            ]); ?>
        <?php endif; ?>
    </div>
    
    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget([
        'model' => $model
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
