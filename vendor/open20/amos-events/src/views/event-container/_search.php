<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-events/src/views 
 */
use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
* @var yii\web\View $this
* @var open20\amos\events\models\search\EventContainerSearch $model
* @var yii\widgets\ActiveForm $form
*/


?>
<div class="event-search my-2 collapse" id="form-search-events">
    <?php $form = ActiveForm::begin([
    'method' => 'get',
    'options' => [
      'class' => 'default-form'
    ]
  ]);

  echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));

 ?>

 <!-- title -->
<div class="col-md-4"> <?= 
$form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title' ]) ?>

 </div> 

<!-- description -->
<div class="col-md-4"> <?php
//$form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description' ]) ?>

 </div> 

<!-- luya_template_id -->
<div class="col-md-4"> <?php
//$form->field($model, 'luya_template_id')->textInput(['placeholder' => 'ricerca per luya template id' ]) ?>

 </div> 

<!-- luya_page_id -->
<div class="col-md-4">
    <?php
//$form->field($model, 'luya_page_id')->textInput(['placeholder' => 'ricerca per luya page id' ]) ?>

 </div> 

<!-- created_at -->  <?php // echo $form->field($model, 'created_at') ?>

 <!-- updated_at -->  <?php // echo $form->field($model, 'updated_at') ?>

 <!-- deleted_at -->  <?php // echo $form->field($model, 'deleted_at') ?>

 <!-- created_by -->  <?php // echo $form->field($model, 'created_by') ?>

 <!-- updated_by -->  <?php // echo $form->field($model, 'updated_by') ?>

 <!-- deleted_by -->  <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="col-md-12">
        <div class="float-right">
            <?= Html::a(
                \open20\amos\events\AmosEvents::t('amosevents', 'Cancel'),
                [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-xs']
            ) ?>
            <?= Html::submitButton(\open20\amos\events\AmosEvents::t('amosevents', 'Search'), ['class' => 'btn btn-xs btn-outline-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
