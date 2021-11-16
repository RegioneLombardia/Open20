<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use \yii\bootstrap4\ActiveForm;
// use open20\amos\core\forms\ActiveForm;
use open20\amos\events\models\search\UserEventSearch;
use yii\helpers\Html;
use open20\amos\events\models\EventCommunication;

$this->title = AmosEvents::t('amosevents', 'Modifica comuncazione');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;
$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

$variables = \open20\amos\events\utility\EventMailUtility::getVariablesEmail();
$variablesJson = \yii\helpers\Json::encode($variables);
$tinyMCECallback = <<< JS
    function (editor) {

        let usersList = $variablesJson;
        let options = [];

        editor.on('change', function () {
                tinymce.triggerSave();
        });
        $.each(usersList, function(label, mapping) {
            options.push({
                text: label, 
                onclick: function() { 
                    editor.insertContent(label); 
                }
            });
        });

        //add the dropdown button to the editor 
        editor.addButton('keyvalues', {
            type: 'menubutton',
            text: 'Variabili',
            icon: false,
            menu: options
        });
    }
JS;

$event_id = $model->id;

$js = <<<JS
$(document).on('click', '#btn-send-email', function(e) {
    // console.log(this);
    e.preventDefault();
    var textemail = $('#text-email').val();
    console.log(textemail);
    $('#text-email').trigger('change');
      $.ajax({
            url: '/events/event-dashboard/send-communication-test?eid=$event_id',
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


<div class="utenti">
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'form-search']
    ]); ?>
    <div>

        <div class="my-4 d-flex">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_contact_mail"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Modifica Comunicazione") ?>
            </h5>
        </div>


        <div class="row variable-gutters">
            <div class="col-md-3 mr-auto">
                <p class="text-muted">
                    <?= AmosEvents::t('amosevents', "Comunica agli iscritti eventuali variazioni nei dettagli dell'evento") ?>
                </p>
                <div class="d-none d-md-block">
                    <img src="<?= $wizardAsset->baseUrl ?>/img/utenti-evento.png" class="img-fluid pt-5">
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong><?= AmosEvents::t('amosevents', 'N. utenti trovati: ') . $n_users ?></strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($eventCommunication, 'subject_email')
                            ->textInput(['id' => 'subject-email'])
                            ->label(AmosEvents::t('amosevents', "Oggetto della comunicazione"));; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $form->field($eventCommunication, 'text_email')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                            'options' => ['id' => 'text-email'],
                            'clientOptions' => [
                                'toolbar' => \open20\amos\events\utility\EventsUtility::getToolbarTextEditor(),
                                'setup' => new \yii\web\JsExpression($tinyMCECallback)
                            ]
                        ])->label(AmosEvents::t('amosevents', "Testo della comunicazione"));
                        ?>
                    </div>
                </div>

                <hr class="dashed">


                <div class="row">
                    <div class="col-md-12">
                        <?php echo $form->field($eventCommunication, 'title')->label(AmosEvents::t('amosevents', "Titolo comunicazione"));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($eventCommunication, 'communication_type')->radioList([
                            EventCommunication::TYPE_INVITED_SENT => EventCommunication::getCommunicationTypeLabels()[EventCommunication::TYPE_INVITED_SENT],
                            EventCommunication::TYPE_REGISTERED => EventCommunication::getCommunicationTypeLabels()[EventCommunication::TYPE_REGISTERED],
                            EventCommunication::TYPE_INVITED_NOT_REGISTED => EventCommunication::getCommunicationTypeLabels()[EventCommunication::TYPE_INVITED_NOT_REGISTED],
                        ])->label(AmosEvents::t('amosevents', 'Invia comunicazione a'));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($eventCommunication, 'email')->label(AmosEvents::t('amosevents', "Vuoi vedere l'aspetto della mail in anteprima? 
                    Inserisci un indirizzo email per effettuare  l'invio di test")); ?>
                        <?= Html::a(AmosEvents::t('amosevents', 'Invio di test'), '', [
                            'id' => 'btn-send-email',
                            'class' => 'btn btn-sm btn-secondary btn-send-test',
                        ]) ?>
                    </div>



                </div>
            </div>
        </div>
        <div class="buttons d-flex mt-5 pt-4">
            <?= Html::a(AmosEvents::t('amosevents', 'Back'), ['/events/event-dashboard/communications', 'id' => $model->id], [
                'class' => 'btn btn-secondary mr-auto',
            ]) ?>
            <?= Html::submitButton(AmosEvents::t('amosevents', "Salva"), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php $eventCommunication->event_id = $model->id; ?>
        <?= $form->field($eventCommunication, 'event_id')->hiddenInput()->label(false) ?>

        <?php ActiveForm::end(); ?>