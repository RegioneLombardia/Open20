<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\emailmanager\models\EmailSpool;
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model EmailSpool */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="email-spool-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'transport')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'template')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'priority')->textInput() ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'model_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'model_id')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'to_address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'from_address')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <?= $form->field($model, 'sent')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATETIME,
                'saveFormat' => 'php:U',
                'displayFormat' => 'dd-MM-yyyy HH:mm:ss',
            ]) ?>
        </div>
    </div>
    <div class="col-xs-12 note_asterisk nop">
        <p>I campi <span class="red">*</span> sono obbligatori.</p>
    </div>
    <div class="btnViewContainer pull-right">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
