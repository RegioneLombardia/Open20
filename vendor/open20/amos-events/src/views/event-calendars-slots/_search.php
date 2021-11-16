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
* @var open20\amos\events\models\EventCalendarsSlotsSearch $model
* @var yii\widgets\ActiveForm $form
*/

    $this->registerJs("
            $('#event-calendars-slotssearch-date').change(function(){
        if($('#event-calendars-slotssearch-date').val() == ''){
        $('#event-calendars-slotssearch-date-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
        if($('#event-calendars-slotssearch-date-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
        $('#event-calendars-slotssearch-date-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
        initDPRemove('event-calendars-slotssearch-date-disp');
        }
        }
        });
        ", yii\web\View::POS_READY);

?>
<div class="event-calendars-slots-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
    'action' => (isset($originAction) ? [$originAction] : ['index']),
    'method' => 'get',
    'options' => [
    'class' => 'default-form'
    ]
    ]);
    ?>

    <!-- id -->  <?php // echo $form->field($model, 'id') ?>

 <!-- event_calendars_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'event_calendars_id')->textInput(['placeholder' => 'ricerca per event calendars id' ]) ?>

 </div> 

<!-- date --><!-- DATE -->
<div class="col-md-4">
			<?= $form->field($model, 'date')->widget(DateControl::classname(), [
                           				'options' => [ 'layout' => '{input} {picker} ' . (($model->date == '')? '' : '{remove}')]
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

<!-- user_id -->
<div class="col-md-4"> <?= 
$form->field($model, 'user_id')->textInput(['placeholder' => 'ricerca per user id' ]) ?>

 </div> 


                <div class="col-md-4">
                    <?= 
                    $form->field($model, 'user')->textInput(['placeholder' => 'ricerca per utente'])->label('Utente');
                     ?> 
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
