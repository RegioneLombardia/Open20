<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = AmosEvents::t('amosevents', 'Crea un nuovo evento');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$scope = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}

$js = <<<JS
    $('#switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#settings-div').show();
        }
        else {
           $('#settings-div').hide();
        }
    });

// $('#btn-send-test').click(function(e) {
//     e.preventDefault();
//   $.ajax({
//         url: '/events/wizard/send-email-test',
//         data:{
//             email: $('#email-test').val()
//         },
//         method: 'post',
//         success: function(data){
//             alert('messagio spedito a '+$('#email-test').val())
//         }
//   });
// });
JS;
$this->registerJs($js);

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
//$this->registerJs($js);
//pr($model->getErrors());

?>
<div class="heading border-bottom mb-4 pb-2">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="mb-0 pr-2 text-muted"><?= (empty(trim($this->title)) ?: $this->title) ?></h3>
        </div>
        <div>
            <div class="step">2</div>
        </div>

    </div>
</div>
<?php $form = ActiveForm::begin(); ?>
<div class="configura-email">

    <div>
        <div class="py-4 d-flex title-substeps affix-top">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_mail"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Modifica i modelli email predefiniti") ?>
                <a href="#" data-toggle="tooltip" data-html="true" title=" <?= AmosEvents::t('amosevents', "Puoi scegliere il <strong>colore</strong> da abbinare alla grafica e alla foto di copertina che hai scelto, per personalizzare al meglio il tuo evento.") ?>">
                <svg class="icon icon-xs icon-secondary mb-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                </svg>
            </a>
            </h5>
        </div>

        <div class="row variable-gutters">
            <div class="col-12 mt-3">
                <div class="row variable-gutters">
                    <div class="col-md-6">
                        <?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_item_email_template', [
                            'emailTemplates' => $emailTemplates,
                            'form' => $form,
                            'attribute_text' => 'save_the_date',
                            'attribute_subject' => 'save_the_date_subject',
                            'field_email' => 'email_test_1',
                            'title' => AmosEvents::t('amosevents', '<strong>Save the date</strong>')
                        ]); ?>
                    </div>
                    <?php if ($model->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) { ?>
                        <div class="col-md-6 mt-5 mt-md-0">
                            <?= $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_item_email_template', [
                                'emailTemplates' => $emailTemplates,
                                'form' => $form,
                                'attribute_text' => 'registration_email',
                                'attribute_subject' => 'registration_email_subject',
                                'field_email' => 'email_test_2',
                                'title' => AmosEvents::t('amosevents', '<strong>Invito alla registrazione</strong>')
                            ]); ?>
                        </div>
                    <?php } ?>
                    <?php echo $form->field($emailTemplates, 'event_id')->hiddenInput(['id' => 'event-id'])->label(false); ?>
                </div>

            </div>

            <!--            <div class="col-md-8">-->
            <!--                <div class="control-group mb-5">-->
            <!--                    <label class="label-control text-muted font-weight-semibold">--><?php //echo AmosEvents::t('amosevents', 'Subject') 
            ?>
            <!--</label>-->
            <!--                    --><?php //echo Html::textInput('Subject', $email->Subject, ['class' => 'form-control']) 
            ?>
            <!--                </div>-->
            <!---->
            <!--                <div class="control-group mb-4">-->
            <!--                    <label class="label-control text-muted font-weight-semibold">--><?php //echo AmosEvents::t('amosevents', 'Content') 
            ?>
            <!--</label>-->
            <!--                    --><?php //echo \open20\amos\core\forms\TextEditorWidget::widget([
            //                        'name' => 'Content',
            //                        //                    'value' => $email->Content,
            //                        'options' => ['rows' => 15]
            //                    ]);
            ?>
            <!--                </div>-->
            <!--                <div class="control-group">-->
            <!--                    <label class="label-control">--><?php //echo AmosEvents::t('amosevents', "Vuoi vedere l'aspetto dell'anteprima? Inserisci un indirizzo email per effettuare un invio di test") 
            ?>
            <!--</label>-->
            <!--                    --><?php //echo Html::textInput('EmailTo', '', ['id' => 'email-test', 'class' => 'form-control']) 
            ?>
            <!--                    --><?php //echo Html::a(AmosEvents::t('amosevents', 'Invio di test'), ["/events/wizard/send-email-test", 'id' => $model->id], [
            //                        'class' => 'btn btn-primary mt-4',
            //                        'id' => 'btn-send-test'
            //                    ])
            ?>
            <!--                </div>-->
            <!---->
            <!--                --><?php //echo $form->field($model, 'title')->hiddenInput()->label(false); 
            ?>
            <!--            </div>-->
        </div>
        <!--        <hr class="dashed">-->
        <!--        <div class="row variable-gutters mb-5">-->
        <!--            <div class="col-md-3 mr-auto">-->
        <!--                <h5 class="font-weight-bold my-4">-->
        <!--                    <strong>--><?php //echo AmosEvents::t('amosevents', 'Fields') . ':' 
        ?>
        <!--</strong>-->
        <!--                </h5>-->
        <!--                <ul>-->
        <!--                    --><?php //foreach ($email->Fields as $field) { 
        ?>
        <!--                        <li>--><?php //echo $field->Description 
        ?>
        <!--</li>-->
        <!--                    --><?php //} 
        ?>
        <!--                </ul>-->
        <!--            </div>-->
        <!--            <div class="col-md-8">-->
        <!--                <div class="content-email shadow mt-5">-->
        <!--                    --><?php //echo  $email->Content 
        ?>
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->

    </div>


    <div class="buttons row">
        <?= WizardPrevAndContinueButtonWidget::widget([
            'model' => $model,
            'viewPreviousBtn' => true,
            'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/events/wizard/base-info', 'id' => $model->id]),

            'cancelUrl' => '',

        ]) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>