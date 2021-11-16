<?php

use open20\amos\events\AmosEvents;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\helpers\Html;

$variables = \open20\amos\events\utility\EventMailUtility::getVariablesEmail();
$variablesJson = \yii\helpers\Json::encode($variables);
$isPublicOpenToAll = ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_OPEN && $model->eventType->limited_seats == false);

$tinyMCECallback = <<< JS
    function (editor) {

        let usersList = $variablesJson;
        let options = [];

        editor.on('change', function () {
                tinymce.triggerSave();
        });
        
        //iterate the user array and create the options with text and 
        //onclick event to insert the content on click to the editor

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

?>

<div class="row">
    <div class="col-md-6">
        <?php echo $form->field($landing, 'streaming_type')->widget(\kartik\select2\Select2::className(), [
            'data' => $landing->getLabelTypeStreaming(),
            'options' => ['placeholder' => AmosEvents::t('amosevents', 'Select...')]
        ]); ?>
    </div>
    <div class="col-md-6">
        <?php echo $form->field($landing, 'streaming_url'); ?>
    </div>
</div>
<div class="row">

    <div class="col-md-6">
        <?php echo $form->field($landing, 'date_begin_streaming')->label(AmosEvents::t('amosevents', 'Data avvio streaming'))->widget(
            \kartik\datecontrol\DateControl::className(),
            [
                'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME
            ]
        ) ?>
    </div>

    <?php if ($isPublicOpenToAll) { ?>
        <div class="col-md-6">
            <?php echo $form->field($landing, 'date_sending_memo')->label(AmosEvents::t('amosevents', 'Data invio promemoria'))->widget(
                \kartik\datecontrol\DateControl::className(),
                [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME
                ]
            ) ?>
        </div>
    <?php } ?>
</div>

<?php if ($isPublicOpenToAll) { ?>
    <?php
    if(empty($landing->text_sending_memo)){
        $landing->text_sending_memo = AmosEvents::t('amosevents', "Gentile {NOME} {COGNOME},<br>
                ti ricordiamo che l'evento '{TITOLO}' a cui ti sei iscritto inizierà a breve, non perdertelo!<br>
                <br>Collegati per tempo a {URL_LANDING}: lo streaming avrà inizio il {DATA_INIZIO_STREAMING} alle ore {ORA_INIZIO_STREAMING}!<br>
                <br>Fai partire il countdown, noi ti aspettiamo!");
    }
    if(empty($landing->subject_sending_memo)){
        $landing->subject_sending_memo = AmosEvents::t('amosevents', "{NOME}, l’evento '{TITOLO}' sta per cominciare");
    }
    ?>
    <hr class="dashed">
    <div class="row">
        <div class="col-md-6">
            <?php echo $form->field($landing, 'subject_sending_memo'); ?>
        </div>
        <div class="col-md-12">
            <?=
            $form->field($landing, 'text_sending_memo')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'clientOptions' => [
                    'toolbar' => "fullscreen | undo redo code | styleselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media insertdatetime | removeformat | keyvalues",
                    'setup' => new \yii\web\JsExpression($tinyMCECallback),

                ]
            ])
                ->label(AmosEvents::t('amosevents', 'Testo email'))
            ?>
        </div>
    </div>
<?php } ?>
