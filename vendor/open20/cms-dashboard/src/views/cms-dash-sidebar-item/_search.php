<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/cms-dashboard/src/views
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var models\CmsDashSidebarItemSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="cms-dash-sidebar-item-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

    <!-- link -->
    <div class="col-md-4"> <?=
        $form->field($model, 'link')->textInput(['placeholder' => 'ricerca per link' ]) ?>

    </div>

    <!-- link_shortcut -->
    <div class="col-md-4"> <?=
        $form->field($model, 'link_shortcut')->textInput(['placeholder' => 'ricerca per link shortcut' ]) ?>

    </div>

    <!-- label -->
    <div class="col-md-4"> <?=
        $form->field($model, 'label')->textInput(['placeholder' => 'ricerca per label' ]) ?>

    </div>

    <!-- description -->
    <div class="col-md-4"> <?=
        $form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description' ]) ?>

    </div>

    <!-- icon_name -->
    <div class="col-md-4"> <?=
        $form->field($model, 'icon_name')->textInput(['placeholder' => 'ricerca per icon name' ]) ?>

    </div>

    <!-- id_container -->
    <div class="col-md-4"> <?=
        $form->field($model, 'id_container')->textInput(['placeholder' => 'ricerca per id container' ]) ?>

    </div>

    <!-- class_container -->
    <div class="col-md-4"> <?=
        $form->field($model, 'class_container')->textInput(['placeholder' => 'ricerca per class container' ]) ?>

    </div>

    <!-- isVisible -->
    <div class="col-md-4"> <?=
        $form->field($model, 'isVisible')->textInput(['placeholder' => 'ricerca per is visible' ]) ?>

    </div>

    <!-- isTargetBlank -->
    <div class="col-md-4"> <?=
        $form->field($model, 'isTargetBlank')->textInput(['placeholder' => 'ricerca per is target blank' ]) ?>

    </div>

    <!-- position -->
    <div class="col-md-4"> <?=
        $form->field($model, 'position')->textInput(['placeholder' => 'ricerca per position' ]) ?>

    </div>

    <!-- created_at -->  <?php // echo $form->field($model, 'created_at') ?>

    <!-- updated_at -->  <?php // echo $form->field($model, 'updated_at') ?>

    <!-- deleted_at -->  <?php // echo $form->field($model, 'deleted_at') ?>

    <!-- created_by -->  <?php // echo $form->field($model, 'created_by') ?>

    <!-- updated_by -->  <?php // echo $form->field($model, 'updated_by') ?>

    <!-- deleted_by -->  <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
