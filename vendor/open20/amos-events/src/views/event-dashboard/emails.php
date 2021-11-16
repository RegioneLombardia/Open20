<?php

use open20\amos\events\AmosEvents;
use yii\bootstrap4\ActiveForm;
//use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = AmosEvents::t('amosevents', 'Modifica i modelli email predefiniti');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$eventType = $model->eventType;
$eventTypePresent = !is_null($eventType);
$eventTypeWithLimitedSeats = ($eventTypePresent && $eventType->limited_seats);
$js = <<<JS
$(document).on('click', '.btn-send-test', function(e) {
    // console.log(this);
    e.preventDefault();
    var text = $(this).attr('data-attr_text');
    var subject = $(this).attr('data-attr_subject');
    var email = $(this).attr('data-key');
    var event = $('#event-id').val();
      $.ajax({
            url: '/events/event-dashboard/send-email-test?attr_text='+text+'&attr_subject='+subject+'&field_email='+email+'&eid='+event,
            data: $('form').serialize(),
            method: 'post',
            success: function(data){
                alert('messaggio spedito a '+ data)
            }
      });
});


  
JS;
$this->registerJs($js);
?>


    <div class="dove-evento my-4">

<?php $form = ActiveForm::begin(); ?>

    <div class="row variable-gutters mt-3">
        <div class="col-md-6 mb-5">
            <?= $this->render('../_parts/_item_email_template', [
                'emailTemplates' => $emailTemplates,
                'form' => $form,
                'attribute_text' => 'save_the_date',
                'attribute_subject' => 'save_the_date_subject',
                'field_email' => 'email_test_1',
                'title' => AmosEvents::t('amosevents', '<strong>Save the date</strong>')
            ]); ?>
        </div>

        <?php if ($model->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) { ?>
            <div class="col-md-6 mt-5 mt-md-0 mb-5">
                <?= $this->render('../_parts/_item_email_template', [
                    'emailTemplates' => $emailTemplates,
                    'form' => $form,
                    'attribute_text' => 'registration_email',
                    'attribute_subject' => 'registration_email_subject',
                    'field_email' => 'email_test_2',
                    'title' => AmosEvents::t('amosevents', '<strong>Invito alla registrazione</strong>')
                ]); ?>
            </div>
        <?php } ?>
    

<?php if ($model->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) { ?>
    <hr class="dashed my-5">
    
        <div class="col-md-6 mt-5 mt-md-0 mb-5 dashed pt-5">
            <?= $this->render('../_parts/_item_email_template', [
                'emailTemplates' => $emailTemplates,
                'form' => $form,
                'attribute_text' => 'confirm_registration',
                'attribute_subject' => 'confirm_registration_subject',
                'field_email' => 'email_test_3',
                'title' => AmosEvents::t('amosevents', '<strong>Conferma di registrazione</strong>')
            ]); ?>
        </div>

        <?php if ($eventTypeWithLimitedSeats) { ?>
            <div class="col-md-6 mt-5 mt-md-0 mb-5 dashed pt-5">
                <?= $this->render('../_parts/_item_email_template', [
                    'emailTemplates' => $emailTemplates,
                    'form' => $form,
                    'attribute_text' => 'info_waiting_list',
                    'attribute_subject' => 'info_waiting_list_subject',
                    'field_email' => 'email_test_4',
                    'title' => AmosEvents::t('amosevents', '<strong>Info lista di attesa</strong>')
                ]); ?>
            </div>
        <?php } ?>
    

    <hr class="dashed my-5">

    
        <div class="col-md-6 mt-5 mt-md-0 mb-5 dashed pt-5">
            <?= $this->render('../_parts/_item_email_template', [
                'emailTemplates' => $emailTemplates,
                'form' => $form,
                'attribute_text' => 'send_tickets',
                'attribute_subject' => 'send_tickets_subject',
                'field_email' => 'email_test_5',
                'title' => AmosEvents::t('amosevents', '<strong>Invio biglietti</strong>')
            ]); ?>
        </div>
    
<?php } ?>
</div>
<?= $form->field($emailTemplates, 'event_id')->hiddenInput(['id' => 'event-id'])->label(false); ?>

    <div class="buttons row">

        <?php echo Html::submitButton(AmosEvents::t('amosevents', 'Salva le modifiche'), ['class' => 'btn btn-primary ml-auto']) ?>

    </div>
<?php $form = ActiveForm::end(); ?>