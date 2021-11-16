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
* @var open20\amos\events\models\search\EventCalendars $model
* @var yii\widgets\ActiveForm $form
*/

    $this->registerJs("
            $('#event-calendarssearch-date_start').change(function(){
        if($('#event-calendarssearch-date_start').val() == ''){
        $('#event-calendarssearch-date_start-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
        if($('#event-calendarssearch-date_start-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
        $('#event-calendarssearch-date_start-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
        initDPRemove('event-calendarssearch-date_start-disp');
        }
        }
        });
            $('#event-calendarssearch-date_end').change(function(){
        if($('#event-calendarssearch-date_end').val() == ''){
        $('#event-calendarssearch-date_end-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
        if($('#event-calendarssearch-date_end-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
        $('#event-calendarssearch-date_end-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
        initDPRemove('event-calendarssearch-date_end-disp');
        }
        }
        });
        ", yii\web\View::POS_READY);

?>
<div class="event-calendars-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- event_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'event_id')->textInput(['placeholder' => 'ricerca per event id' ]) ?>

 </div> 


                <div class="col-md-4">
                    <?= 
                    $form->field($model, 'event')->textInput(['placeholder' => 'ricerca per evento'])->label('Evento');
                     ?> 
                </div>
                <!-- title -->
<div class="col-md-4"> <?= 
$form->field($model, 'title')->textInput(['placeholder' => 'ricerca per title' ]) ?>

 </div> 

<!-- description -->
<div class="col-md-4"> <?= 
$form->field($model, 'description')->textInput(['placeholder' => 'ricerca per description' ]) ?>

 </div> 

<!-- date_start --><!-- DATE -->
<div class="col-md-4">
			<?= $form->field($model, 'date_start')->widget(DateControl::classname(), [
                           				'options' => [ 'layout' => '{input} {picker} ' . (($model->date_start == '')? '' : '{remove}')]
                        			]); ?>
</div>
<!-- date_end --><!-- DATE -->
<div class="col-md-4">
			<?= $form->field($model, 'date_end')->widget(DateControl::classname(), [
                           				'options' => [ 'layout' => '{input} {picker} ' . (($model->date_end == '')? '' : '{remove}')]
                        			]); ?>
</div>
<!-- hour_start -->
<div class="col-md-4"> <?= 
$form->field($model, 'hour_start')->textInput(['placeholder' => 'ricerca per hour start' ]) ?>

 </div> 

<!-- hour_end -->
<div class="col-md-4"> <?= 
$form->field($model, 'hour_end')->textInput(['placeholder' => 'ricerca per hour end' ]) ?>

 </div> 

<!-- slot_duration -->
<div class="col-md-4"> <?= 
$form->field($model, 'slot_duration')->textInput(['placeholder' => 'ricerca per slot duration' ]) ?>

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
