<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\slideshow\AmosSlideshow;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var open20\amos\slideshow\models\Slideshow $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="slideshow-form col-xs-12">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-sm-6">

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">

            <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-6">

            <?= $form->field($model, 'eval_contoller_method')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-sm-6">

            <?= $form->field($model, 'default_not_show_again')->dropdownList([0 => 'Non selezionato', 1 => 'Selezionato']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">

            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?=
            $form->field($route, 'role')->widget(Select2::classname(),
                [
                    'data' => $route->getAllRoles(),
                    'options' => ['placeholder' => AmosSlideshow::t('amosslideshow',
                        'Selezionare i ruoli che vedranno lo slideshow ...'),
                        'id' => 'role-id'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-sm-8">
            <?= $form->field($route, 'route')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-3 col-sm-4">

            <?= $form->field($route, 'already_view')->dropdownList([0 => 'NO', 1 => 'SI']) ?>
        </div>
    </div>
    <div class="row">

    </div>

    <div class="clearfix"></div>

    <div class="col-xs-12 note_asterisk nop">
        <p>I campi <span class="red">*</span> sono obbligatori.</p>
    </div>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
