<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views
 */

use open20\amos\core\helpers\Html;
use yii\bootstrap4\ActiveForm;
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
 * @var open20\amos\events\models\EventLandingProtagonist $model
 * @var yii\widgets\ActiveForm $form
 */


?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'event-landing-protagonist_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : '')
    ]
]);
?>
<?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

<div class="row">
    <div class="col-md-6">
    </div>
    <div class="col-ms-6">
        <?= $form->field($model, 'image')->label(AmosEvents::t('amosevents', 'Immagine del protagonista')) ->widget(\open20\amos\attachments\components\CropInput::classname(), [
            'enableUploadFromGallery' => false,
            'jcropOptions' => ['aspectRatio' => '1']
        ]); ?>
    </div>
</div>
<div class="row">

    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- surname string -->
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?><!-- company string -->
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'company')->textInput(['maxlength' => true])?>
    </div>
    <?= $form->field($model, 'event_landing_id')->hiddenInput()->label(false) ?>
</div>
<?= RequiredFieldsTipWidget::widget(); ?>


<div class="buttons">
    <?php echo Html::submitButton(\open20\amos\events\AmosEvents::t('amosevents', "Aggiungi protagonista"), [
        'class' => 'btn btn-primary']) ?>

</div>
<?php ActiveForm::end(); ?>

