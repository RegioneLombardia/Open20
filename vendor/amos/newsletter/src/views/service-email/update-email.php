<?php
/**
 * @var $email
 */

use amos\newsletter\Module;
use yii\helpers\Html;
use \open20\amos\core\forms\ActiveForm;

$this->title = Module::t('amosnewsletter', 'Update email');
$this->params['breadcrumbs'][] = $this->title;
$module = \Yii::$app->getModule('newsletter');
?>

<div class="col-xs-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="control-group col-xs-12">
        <label class="label-control"><?= Module::t('amosnewsletter', 'Subject') ?></label>
        <?php echo Html::textInput('Subject', $email->Subject, ['class' => 'form-control']) ?>
    </div>
    <div class="control-group col-xs-12">
        <label class="label-control"><?= Module::t('amosnewsletter', 'Content') ?></label>
        <?php echo \open20\amos\core\forms\TextEditorWidget::widget([
            'name' => 'Content',
            'value' => $email->Content,
            'options' => ['rows' => 15]
        ]); ?>
    </div>

    <div class="control-group col-xs-12 m-t-20">
        <label ><?= Module::t('amosnewsletter', "Use dynamic fields")?></label>
        <?php
        echo \kartik\widgets\SwitchInput::widget([
            'name' => 'UseDynamicField',
            'value' => $email->UseDynamicField,
            'pluginOptions' => [
                'size' => 'mini',
                'onText' => 'Si',
                'offText' => 'No',
            ],

        ]);
    ?>
    </div>
    <div class="col-xs-12 m-t-20">
        <?= Html::a(Module::t('amosnewsletter', 'Back'), ['email-templates', 'idList' => $email->idList], ['class' => 'btn btn-secondary']) ?>
        <?= Html::submitButton(Module::t('amosnewsletter', 'Save'), ['class' => 'btn btn-navigation-primary pull-right']); ?>
    </div>
    <strong>

        <?php ActiveForm::end(); ?>
</div>

