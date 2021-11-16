<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modues/operators/views
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\editors\Select;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use kartik\datecontrol\DateControl;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var backend\modules\operators\models\AgidPersonType $model
 * @var yii\widgets\ActiveForm $form
 */


?>


<?php $form = ActiveForm::begin([
    'options' => [
        'id' => 'agid-person-type_' . ((isset($fid)) ? $fid : 0),
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'class' => ((isset($class)) ? $class : '')
    ]
]);
?>
<?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>


<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description text -->
<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?><!-- description text -->
<?= RequiredFieldsTipWidget::widget(); ?>

<?= CloseSaveButtonWidget::widget(['model' => $model]); ?>

<?php ActiveForm::end(); ?></div>
<div class="col-md-4 col xs-12"></div>

<div class="clearfix"></div>


