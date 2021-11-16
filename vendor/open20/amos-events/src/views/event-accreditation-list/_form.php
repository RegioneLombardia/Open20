<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views
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

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\EventAccreditationList $model
 * @var yii\widgets\ActiveForm $form
 * @var integer $eventId
 */


?>
<div class="event-accreditation-list-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'event-accreditation-list_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
            <div class="col-md-8 col xs-12"><!-- title string -->
                <?php
                    $eventIdHiddenOptions = [];
                    if(!empty($eventId)) {
                        $eventIdHiddenOptions['value'] = $eventId;
                    }
                ?>
                <?= $form->field($model, 'event_id')->hiddenInput($eventIdHiddenOptions)->label(false); ?>
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- position integer -->
                <?= $form->field($model, 'position')->textInput() ?><?= RequiredFieldsTipWidget::widget(); ?><?= CloseSaveButtonWidget::widget(['model' => $model]); ?><?php ActiveForm::end(); ?></div>
            <div class="col-md-4 col xs-12"></div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>