<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var backend\modules\landings\models\search\PreferenceLandingProtagonistSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="preference-landing-protagonist-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- preference_landing_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'preference_landing_id')->textInput(['placeholder' => 'ricerca per preference landing id' ]) ?>

 </div> 


                <div class="col-md-4">
                    <?= 
                    $form->field($model, 'eventLanding')->textInput(['placeholder' => 'ricerca per '])->label('');
                     ?> 
                </div>
                <!-- name -->
<div class="col-md-4"> <?= 
$form->field($model, 'name')->textInput(['placeholder' => 'ricerca per name' ]) ?>

 </div> 

<!-- surname -->
<div class="col-md-4"> <?= 
$form->field($model, 'surname')->textInput(['placeholder' => 'ricerca per surname' ]) ?>

 </div> 

<!-- company -->
<div class="col-md-4"> <?= 
$form->field($model, 'company')->textInput(['placeholder' => 'ricerca per company' ]) ?>

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
