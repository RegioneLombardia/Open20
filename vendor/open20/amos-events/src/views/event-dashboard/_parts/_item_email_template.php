<?php

use open20\amos\events\AmosEvents;
use yii\helpers\Html;
?>


<?php
$variables = \open20\amos\events\utility\EventMailUtility::getVariablesEmail();
$variablesJson = \yii\helpers\Json::encode($variables);

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
<div>
    <h5><?= $title ?></h5>
</div>

<div>
    <?php  if(empty($emailTemplates->$attribute_subject)){
        $emailTemplates->$attribute_subject = $emailTemplates->getDefaultSubject()[$attribute_subject];
    }
?>
    <?=
    $form->field($emailTemplates, $attribute_subject)->label(AmosEvents::t('amosevents','Oggetto email'));
    ?>
</div>

<div>
    <?php
    if(empty($emailTemplates->$attribute_text)){
        $emailTemplates->$attribute_text = $emailTemplates->getDefaultText()[$attribute_text];
}?>
    <?=
    $form->field($emailTemplates, $attribute_text)->widget(\open20\amos\core\forms\TextEditorWidget::className(),[
            'clientOptions' =>         [
                    'toolbar' => \open20\amos\events\utility\EventsUtility::getToolbarTextEditor(),
//                'toolbar' => "fullscreen | undo redo code | styleselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media insertdatetime | removeformat | keyvalues",
                'setup' => new \yii\web\JsExpression($tinyMCECallback),
                ]
    ])
        ->label(AmosEvents::t('amosevents','Testo email'))
    ?>
</div>

<div>
    <?= $form->field($emailTemplates, $field_email)->textInput(['placeholder' =>  AmosEvents::t('amosevents', 'Inserisci la tua email per un invio di prova...')])
        ->label(AmosEvents::t('amosevents', "Per vedere in anteprima l'aspetto della comunicazione, inserisci la tua email ed effettua un invio di prova")) ?>
</div>

<div>
    <?= Html::a(AmosEvents::t('amosevents', 'Invio di prova'), '', [
        'id' => 'btn-send-'.$field_email,
        'class' => 'btn btn-sm btn-secondary btn-send-test',
        'data-key' => $field_email,
        'data-attr_text' => $attribute_text,
        'data-attr_subject' => $attribute_subject,
    ]) ?>
</div>