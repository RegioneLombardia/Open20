<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var amos\userimporter\models\search\UserImporterTaskSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="user-import-task-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- name -->
<div class="col-md-4"> <?= 
$form->field($model, 'name')->textInput(['placeholder' => 'ricerca per name' ]) ?>

 </div> 

<!-- task_date -->
<div class="col-md-4"> <?= 
$form->field($model, 'task_date')->textInput(['placeholder' => 'ricerca per task date' ]) ?>

 </div> 

<!-- user_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'user_id')->textInput(['placeholder' => 'ricerca per user id' ]) ?>

 </div> 

<!-- event_group_referent_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'event_group_referent_id')->textInput(['placeholder' => 'ricerca per event group referent id' ]) ?>

 </div> 


                <div class="col-md-4">
                    <?= 
                    $form->field($model, 'eventGroupReferent')->textInput(['placeholder' => 'ricerca per '])->label('');
                     ?> 
                </div>
                <!-- tot_input_processed -->
<div class="col-md-4"> <?= 
$form->field($model, 'tot_input_processed')->textInput(['placeholder' => 'ricerca per tot input processed' ]) ?>

 </div> 

<!-- tot_input_imported -->
<div class="col-md-4"> <?= 
$form->field($model, 'tot_input_imported')->textInput(['placeholder' => 'ricerca per tot input imported' ]) ?>

 </div> 

<!-- file_input -->
<div class="col-md-4"> <?= 
$form->field($model, 'file_input')->textInput(['placeholder' => 'ricerca per file input' ]) ?>

 </div> 

<!-- file_success_input -->
<div class="col-md-4"> <?= 
$form->field($model, 'file_success_input')->textInput(['placeholder' => 'ricerca per file success input' ]) ?>

 </div> 

<!-- file_errors_input -->
<div class="col-md-4"> <?= 
$form->field($model, 'file_errors_input')->textInput(['placeholder' => 'ricerca per file errors input' ]) ?>

 </div> 

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
