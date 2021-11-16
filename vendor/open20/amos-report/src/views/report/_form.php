<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\views\report
 * @category   CategoryName
 */

use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\TextEditorWidget;
use open20\amos\core\helpers\Html;
use open20\amos\report\AmosReport;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

?>

<p><?= AmosReport::t('amosreport',
        'Indica di seguito  il tipo ed il contenuto della tua segnalazione;<br/>puoi segnalare errori o contenuti che ritieni non appropriati e richiedere una rettifica se necessario.') ?></p>
<?php

$Report = new \open20\amos\report\models\Report();
$form = ActiveForm::begin([
    'id' => 'report-form',
    'enableClientValidation' => true,
    'options' => [
        'class' => 'form default-form col-xs-12 nop',
    ],
    'action' => '/report/report/create'
]) ?>

<?= $form->field($Report, 'context_id')->hiddenInput(['value' => $context_id])->label(false); ?>
<?= $form->field($Report, 'classname')->hiddenInput(['value' => $classname])->label(false); ?>
<?= $form->field($Report, 'creator_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>

<?= $form->field($Report, 'type')->widget(Select2::className(), [
    'data' => ArrayHelper::map(open20\amos\report\models\ReportType::find()->orderBy('name')->all(),
        'id', 'name'),
    'options' => [
        'multiple' => false,
        'placeholder' => AmosReport::t('amosreport', 'Enter name of the report type'),
        'id' => 'type-id',
        'class' => 'dynamicCreation',
        'disabled' => false
    ],
]) ?>

<?= $form->field($Report, 'content')->widget(TextEditorWidget::className(), [
    'clientOptions' => [
        'placeholder' => AmosReport::t('amosreport', 'Description...'),
        'lang' => substr(Yii::$app->language, 0, 2)
    ]
]) ?>
<div class="col-xs-12 note_asterisk nop">
    <?= AmosReport::t('amosreport', 'The fields marked with ') ?><span class="red">*</span><?= AmosReport::t('amosreport', ' are required') ?>
</div>
<?= RequiredFieldsTipWidget::widget(); ?>
<div class="form-group">
    <div class="bk-btnFormContainer">
        <?= Html::submitButton(AmosReport::t('amosreport', 'Send'),
            ['class' => 'btn btn-navigation-primary', 'id' => 'submitReportForm']) ?>
        <?= Html::button(AmosReport::t('amosreport', 'Cancel'),
            ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal']) ?>
    </div>
</div>
<?php
ActiveForm::end();
?>
