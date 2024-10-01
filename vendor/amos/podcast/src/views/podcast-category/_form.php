<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/amos/podcast/src/views
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
 * @var amos\podcast\models\PodcastCategory $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="podcast-category-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'podcast-category_' . ((isset($fid)) ? $fid : 0),
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

            <div class="col-xs-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?><!-- description text -->
            </div>
            <div class="col-xs-6">
                <?= $form->field($model, 'color')->widget(\kartik\color\ColorInput::className(), [
                    'options' => ['placeholder' => \amos\podcast\Module::t('amospodcast', 'Select/choose color') . '...'],
                    'pluginOptions' => ['appendTo' => '#event-type_' . ((isset($fid)) ? $fid : 0)],
                ]) ?>
            </div>
            <div class="col-xs-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 5]); ?>
            </div>

            <div class="col-xs-12">
                <?= RequiredFieldsTipWidget::widget(); ?>
                <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="clearfix"></div>

</div>
