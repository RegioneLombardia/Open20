<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-partnership-profiles/src/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var open20\amos\partnershipprofiles\models\search\PartnershipProfilesCategorySearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="partnership-profiles-category-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- title -->
<div class="col-md-4"> <?= 
$form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title' ]) ?>

 </div> 

<!-- subtitle -->
<div class="col-md-4"> <?= 
$form->field($model, 'subtitle')->textInput(['placeholder' => 'ricerca per subtitle' ]) ?>

 </div> 

<!-- short_description -->
<div class="col-md-4"> <?= 
$form->field($model, 'short_description')->textInput(['placeholder' => 'ricerca per short description' ]) ?>

 </div> 

<!-- description -->
<div class="col-md-4"> <?= 
$form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description' ]) ?>

 </div> 

<!-- color_text -->
<div class="col-md-4"> <?= 
$form->field($model, 'color_text')->textInput(['placeholder' => 'ricerca per color text' ]) ?>

 </div> 

<!-- color_background -->
<div class="col-md-4"> <?= 
$form->field($model, 'color_background')->textInput(['placeholder' => 'ricerca per color background' ]) ?>

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
