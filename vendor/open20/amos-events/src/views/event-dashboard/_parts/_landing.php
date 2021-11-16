<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\events\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use yii\bootstrap4\ActiveForm;
//use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\datecontrol\DateControl;

$idEvent = $model->id;
$js = <<<JS
    


    $('#landing-switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#landing-settings-div').show();
        }
        else {
           $('#landing-settings-div').hide();
        }
    });

$('#switch-social').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#social-sign-in').show();
        }
        else {
           $('#social-sign-in').hide();
        }
    });

$('#switch-username').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#register-form-fields').show();
        }
        else {
           $('#register-form-fields').hide();
        }
    });


$('#url-landing').keyup(function(){
     var pressed = $(this).val();
     pressed = pressed.toLowerCase();
    $(this).val(pressed);
});


    

JS;

$jsSeo = <<<JS
      $('#event-title').change(function(){
        var title = $(this).val();
                $.ajax({
                    url: '/seo/api/prettyurl',
                    type: 'POST',
                    data: {
                        slug: title
                    },
                    success: function (data) {
                        $('#url-landing').val(data['pretty_url']);
                    }
                });    
    });  
JS;

if ($model->isNewRecord) {
    $this->registerJs($jsSeo);
}


$jsPreviewLanding = <<<JS
$('#luya-template-id').change(function(){
     var value = $("input[name='EventLanding[luya_template_id]']:checked"). val();
         $.ajax({
               url: '/events/event-dashboard/get-preview-landing',
               type: 'get',
               data: {
                    'id': '$idEvent',
                    'nav_id' : value
               },
               success: function (data) {
                  $('#preview-template').html(data);
               }
           });
});
JS;
$this->registerJs($js);
$this->registerJs($jsPreviewLanding);

//pr($eventLanding->getErrors());
//pr($model->getErrors());
$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$moduleEvents = \Yii::$app->getModule('events');
$scope = null;
$patronage_luya_nav_id = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}
if (!empty($moduleEvents->patronage_luya_nav_id)) {
    $patronage_luya_nav_id = $moduleEvents->patronage_luya_nav_id;
}
$ispatronage = $model->isNewRecord ? false : ($model->eventType->patronage ? true : false);
$strHide = '';
if ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_INFORMATIVE) {
    $strHide = 'display:none;';
}
?>

<div class="landing-evento">
    <div>
        <div class="my-4 d-flex py-4 d-inline-flex w-100 title-substeps affix-top">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_web"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Configura la landing page di presentazione") ?>
                <a href="#" data-toggle="tooltip" data-html="true"
                   title="<?= AmosEvents::t('amosevents', "Scegli il colore per personalizzare l'aspetto della landing page.") ?>">
                    <svg class="icon icon-xs icon-secondary mb-2">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                    </svg>
                </a>
            </h5>
        </div>
        <div class="row variable-gutters">
            <div class="col-md-12">
                <div class="input-color-landing">
                    <?php if (empty($eventLanding->luya_template_id) && count($arrayTemplates) > 0) {
                        $i = 0;
                        foreach ($arrayTemplates as $key => $tmp) {
                            if ($i == 0) {
                                $eventLanding->luya_template_id = $key;
                            }
                            if ($ispatronage && $key == $patronage_luya_nav_id) {
                                $eventLanding->luya_template_id = $key;
                                $arrayTemplates = [$key => $tmp];
                                break;
                            }
                            $i++;
                        }
                    } else {
                        if ($ispatronage) {
                            foreach ($arrayTemplates as $key => $tmp) {
                                if ($key == $patronage_luya_nav_id) {
                                    $eventLanding->luya_template_id = $key;
                                    $arrayTemplates = [$key => $tmp];
                                    break;
                                }
                            }
                        }
                    } ?>
                    <?= $form->field($eventLanding, 'luya_template_id')
                        ->radioList($arrayTemplates, ['id' => 'luya-template-id'])
                        ->label("<strong>" . AmosEvents::t('amosevents', "Scegli il colore") . "</strong>") ?>
                </div>
                <?php if (\Yii::$app->controller->action->id !== 'landing-settings') { ?>
                    <?= Html::a(AmosEvents::t('amoesevents', 'Aggiorna anteprima'), '', ['id' => 'update-preview', 'class' => 'btn btn-xs btn-secondary']) ?>
                <?php } ?>
                <div id="preview-template" class="mt-3">
                    <?php
                    if (!empty($eventLanding->luya_template_id)) {
                        echo $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_preview_landing', ['model' => $model, 'eventLanding' => $eventLanding, 'htmlTemplate' => $htmlTemplate]);
                        //                        pr($htmlTemplate[$eventLanding->luya_template_id]);
                        //                        echo $htmlTemplate[$eventLanding->luya_template_id];
                    }
                    ?>


                </div>
            </div>
            <div class="d-none">


                <?php $eventLanding->event_id = $model->id ?>
                <?= $form->field($eventLanding, 'event_id')->hiddenInput()->label(false); ?>
            </div>
        </div>
    </div>

    <?php if (false) { ?>
        <hr class="dashed">

        <div class="gestisci-modulo">
            <div class="my-4 d-flex informative-to-hide" style="<?= $strHide ?>">
                <div class="mr-2">
                    <svg class="icon">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_edit"></use>
                    </svg>
                </div>

                <h5 class="font-weight-bold ">
                    <?= AmosEvents::t('amosevents', "Gestisci modulo di iscrizione all’evento") ?>
                    <a href="#" data-toggle="tooltip" data-html="true"
                       title="<?= AmosEvents::t('amosevents', "Indica per quanto tempo il modulo di registrazione sarà visibile nella landing page.") ?>">
                        <svg class="icon icon-xs icon-secondary mb-2">
                            <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                        </svg>
                    </a>
                </h5>
            </div>


            <div class="row variable-gutters">
                <?php if ($model->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) { ?>

                    <div class="col-md-3 mr-auto informative-to-hide">
                        <!--                        <p class="text-muted">-->
                        <!--                            --><?php //AmosEvents::t('amosevents', "Il modulo di registrazione all'evento sarà visibile solo per un certo periodo di tempo nella tua landing page. Scegli le date qui a fianco.") ?>
                        <!---->
                        <!--                        </p>-->
                    </div>
                <?php } ?>
                <div class="col-md-8">
                    <!--                    --><?php //if ($model->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) { ?>

                    <!--                        <div class="row .informative-to-hide">-->
                    <!--                            <div class="col-xl-6">-->
                    <!--                                --><?php
                    //                                $form->field($model, 'registration_date_begin')->widget(
                    //                                    DateControl::className(),
                    //                                    [
                    //                                        'type' => DateControl::FORMAT_DATETIME
                    //                                    ]
                    //                                )
                    //                                ?>
                    <!--                            </div>-->
                    <!--                            <div class="col-xl-6">-->
                    <!--                                --><?php
                    //                                $form->field($model, 'registration_date_end')->widget(
                    //                                    DateControl::className(),
                    //                                    [
                    //                                        'type' => DateControl::FORMAT_DATETIME
                    //                                    ]
                    //                                )
                    //                                ?>
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    --><?php //} ?>
                    <!--                    --><?php
                    //                    if ($moduleEvents->hidePubblicationDate == false) {
                    //                        ?>
                    <!--                        <div class="row">-->
                    <!--                            <div class="col-xl-6">-->
                    <!--                                --><?php
                    //                                $form->field($model, 'publication_date_begin')->widget(
                    //                                    DateControl::className(),
                    //                                    [
                    //                                        'type' => DateControl::FORMAT_DATETIME
                    //
                    //                                    ]
                    //                                )->hint(AmosEvents::t('amosevents', '#publication_date_begin_hint'))
                    //                                ?>
                    <!--                            </div>-->
                    <!--                            <div class="col-xl-6">-->
                    <!--                                --><?php
                    //                                $form->field($model, 'publication_date_end')->widget(
                    //                                    DateControl::className(),
                    //                                    [
                    //                                        'type' => DateControl::FORMAT_DATETIME
                    //                                    ]
                    //                                )->hint(AmosEvents::t('amosevents', '#publication_date_end_hint'))
                    //                                ?>
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    --><?php //} ?>


                </div>
            </div>
        </div>
    <?php } ?>


</div>

<hr class="dashed">

<div class="impostazioni-avanzate my-5">
    <div class="row">
        <div class="col-md-3 mr-auto pb-3">
            <div class="text-muted d-flex">
                <div class="mr-2">
                    <svg class="icon icon-secondary">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_settings"></use>
                    </svg>
                </div>

                <h5 class="font-weight-bold"><?= AmosEvents::t('amosevents', "Impostazioni avanzate landing") ?></h5>
            </div>
        </div>

        <div class="col-md-8 pb-3">
            <?= $form->field($eventLanding, 'advanced_options_landing')->widget(\kartik\widgets\SwitchInput::className(), [
                'options' => ['id' => 'landing-switch-settings'],
                'pluginOptions' => [
                    //'size' => 'mini',
                    //                    'onText' => AmosIcons::show('check-circle'),
                    //                    'offText' => AmosIcons::show('circle-o')
                    'onText' => '+',
                    'offText' => '-'
                ],
            ])->label(false) ?>
        </div>
    </div>

    <?php $hideAdvanced = 'display:none';
    if ($eventLanding->advanced_options_landing) {
        $hideAdvanced = '';
    }
    ?>
    <div id="landing-settings-div" style="<?= $hideAdvanced ?>" class="p-2 p-sm-5 neutral-1-bg-a1">
        <!--        --><?php // if ($model->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE) { ?>

        <div class="informative-to-hide" style="<?= $strHide ?>">
            <div class="row">
                <div class="col-md-3 mr-auto">
                    <p><?= AmosEvents::t('amosevents', "Permetti agli utenti di registrarsi con il proprio account social") ?></p>
                </div>
                <div class="col-md-8">
                    <div class="d-flex">
                        <div class="mr-auto">
                            <p>
                                <strong><?= AmosEvents::t('amosevents', "Consenti registrazione tramite social") ?></strong>
                            </p>
                        </div>

                        <div>
                            <?= $form->field($eventLanding, 'social_reg')->widget(\kartik\widgets\SwitchInput::className(), [
                                'options' => ['id' => 'switch-social'],
                                'pluginOptions' => [
                                    //'size' => 'mini',
                                    //                    'onText' => AmosIcons::show('check-circle'),
                                    //                    'offText' => AmosIcons::show('circle-o')
                                    'onText' => 'Si',
                                    'offText' => 'No'
                                ],
                            ])->label(false) ?>
                        </div>
                    </div>

                    <?php
                    $displayNone = '';
                    if (!$eventLanding->social_reg) {
                        $displayNone = 'display:none';
                    } ?>
                    <div id="social-sign-in" class="social-signin" style="<?= $displayNone ?>">
                        <?= $form->field($eventLanding, 'facebook_reg')->checkbox()->label('Facebook') ?>
                        <?= $form->field($eventLanding, 'twitter_reg')->checkbox()->label('Twitter') ?>
                        <?= $form->field($eventLanding, 'linkedin_reg')->checkbox()->label('Linkedin') ?>
                        <?= $form->field($eventLanding, 'goolge_reg')->checkbox()->label('Google') ?>
                        <?= $form->field($eventLanding, 'apple_reg')->checkbox()->label('Apple') ?>

                    </div>
                </div>
            </div>
            <hr class="dashed">


            <div class="row mt-5 pt-2">
                <div class="ml-auto col-md-8">
                    <div class="d-flex">
                        <div class="mr-auto">
                            <p>
                                <strong><?= AmosEvents::t('amosevents', "Consenti registrazione tramite SPID/CNS") ?></strong>
                            </p>
                        </div>
                        <div>
                            <?= $form->field($eventLanding, 'spid_cns_reg')->widget(\kartik\widgets\SwitchInput::className(), [
                                'options' => ['id' => 'switch-spid'],
                                'pluginOptions' => [
                                    //'size' => 'mini',
                                    //                    'onText' => AmosIcons::show('check-circle'),
                                    //                    'offText' => AmosIcons::show('circle-o')
                                    'onText' => 'Si',
                                    'offText' => 'No'
                                ],
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="dashed">
            <div class="row mt-5">
                <div class="col-md-3 mr-auto">
                    <p><?= AmosEvents::t('amosevents', "Spunta i campi opzionali che vuoi richiedere in caso di nuova registrazione.<br>I campi Nome, Cognome e Email sono sempre obbligatori.") ?></p>

                </div>
                <div class="col-md-8">

                    <div class="d-flex">
                        <div class="mr-auto">
                            <p>
                                <strong><?= AmosEvents::t('amosevents', "Consenti registrazione tramite Nome, Cognome, Email") ?></strong>
                            </p>
                        </div>
                        <div>
                            <?= $form->field($eventLanding, 'user_name_reg')->widget(\kartik\widgets\SwitchInput::className(), [
                                'options' => ['id' => 'switch-username'],
                                'pluginOptions' => [
                                    //'size' => 'mini',
                                    //                    'onText' => AmosIcons::show('check-circle'),
                                    //                    'offText' => AmosIcons::show('circle-o')
                                    'onText' => 'Si',
                                    'offText' => 'No'
                                ],
                            ])->label(false) ?>
                        </div>
                    </div>


                    <?php
                    $displayNone = '';
                    if (!$eventLanding->user_name_reg) {
                        $displayNone = 'display:none';
                    } ?>
                    <div id="register-form-fields" style="<?= $displayNone ?>">
                        <p>
                            <strong><?= AmosEvents::t('amosevents', ' Richiedi inoltre i seguenti campi opzionali') . ': ' ?></strong>
                        </p>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($eventLanding, 'ask_sex')->checkbox()->label('Genere') ?>
                                <?= $form->field($eventLanding, 'ask_age')->checkbox()->label('Età') ?>
                                <?= $form->field($eventLanding, 'ask_county')->checkbox()->label('Provincia') ?>
                                <?= $form->field($eventLanding, 'ask_city')->checkbox()->label('Comune') ?>
                                <?= $form->field($eventLanding, 'ask_telefon')->checkbox()->label('Telefono') ?>
                                <?= $form->field($eventLanding, 'ask_fiscal_code')->checkbox()->label('Codice fiscale') ?>
                                <?= $form->field($eventLanding, 'ask_company')->checkbox()->label('Azienda') ?>
                                <!--                    --><?php // $form->field($eventLanding, 'ask_tags')->checkbox()->label('Tag di interesse informativo')
                                ?>
                            </div>
                            <div class="col-md-8">
                                <?= $form->field($eventLanding, 'ask_sex_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                                <?= $form->field($eventLanding, 'ask_age_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                                <?= $form->field($eventLanding, 'ask_county_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                                <?= $form->field($eventLanding, 'ask_city_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                                <?= $form->field($eventLanding, 'ask_telefon_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                                <?= $form->field($eventLanding, 'ask_fiscal_code_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                                <?= $form->field($eventLanding, 'ask_company_required')->checkbox()->label('<i>Obbligatorio</i>') ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <hr class="dashed">
        </div>
        <!--    --><?php //} ?>


        <div class="row mt-5">
            <div class="col-md-3 mr-auto">
                <p><?= AmosEvents::t('amosevents', "Modifica l'URL per ottimizzarlo per i motori di ricerca. Usa URL brevi, leggibili e che contengano le keywords del tuo evento.") ?></p>
            </div>
            <div class="col-md-8">


                <p><strong><?= AmosEvents::t('amosevents', "Modifica l'URL della landing page: ") ?></strong></p>
                <div class="row">
                    <div class="col-lg mb-3">
                        <?= \Yii::$app->params['platform']['frontendUrl'] . '/it/' ?>
                    </div>
                    <div class="col-lg mb-3">
                        <?= $form->field($eventLanding, 'url')->textInput(['id' => 'url-landing'])->label(false) ?>
                    </div>
                </div>

            </div>
        </div>

        <hr class="dashed">

        <div style="<?= $strHide ?>" class="informative-to-hide row mt-5">
            <div class="col-md-3 mr-auto">
                <p><?= AmosEvents::t('amosevents', "Modifica i messaggi predefiniti che vengono mostrati agli utenti dopo l'iscrizione, se l'iscrizione è già stata effettuata o se i posti disponibili sono esauriti.") ?></p>
            </div>
            <div class="col-md-8">


                <p><strong><?= AmosEvents::t('amosevents', "Messaggi predefiniti: ") ?></strong></p>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if (empty($eventLanding->thank_you_subscribe)) {
                            $eventLanding->thank_you_subscribe = AmosEvents::t('amosevents', 'Ciao! Grazie per esserti iscritto all\'evento. Ti invieremo a breve una mail di conferma. Ti aspettiamo!');
                        }
                        if (empty($eventLanding->thank_you_registered)) {
                            $eventLanding->thank_you_registered = AmosEvents::t('amosevents', 'Ciao! Abbiamo già ricevuto la tua iscrizione all\'evento. Controlla la tua posta in arrivo, ti abbiamo inviato una mail di conferma.');
                        }
                        if (empty($eventLanding->thank_you_waiting_list)) {
                            $eventLanding->thank_you_waiting_list = AmosEvents::t('amosevents', 'Ciao, ci dispiace informarti che i posti disponibili sono esauriti. Ti abbiamo inserito in una lista di attesa e ti scriveremo quando si libererà un posto. Continua a seguire la pagina dell\'evento per ogni aggiornamento!');
                        }
                        ?>
                        <?= $form->field($eventLanding, 'thank_you_subscribe')->textarea(['rows' => 5, 'placeholder' => AmosEvents::t('amosevents', 'Ciao! Grazie per esserti iscritto all\'evento. Ti invieremo a breve una mail di conferma. Ti aspettiamo!')])
                            ->label(AmosEvents::t('amosevents', "Messaggio di ringraziamento dopo l'iscrizione")) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($eventLanding, 'thank_you_registered')->textarea(['rows' => 5, 'placeholder' => AmosEvents::t('amosevents', 'Ciao! Abbiamo già ricevuto la tua iscrizione all\'evento. <em> Controlla la tua posta in arrivo, ti abbiamo inviato una mail di conferma.</em>')])
                            ->label(AmosEvents::t('amosevents', "Messaggio di avviso di iscrizione già avvenuta")) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($eventLanding, 'thank_you_waiting_list')->textarea(['rows' => 5, 'placeholder' => AmosEvents::t('amosevents', 'Ciao, ci dispiace informarti che i posti disponibili sono esauriti. Ti abbiamo inserito in una lista di attesa e ti scriveremo quando si libererà un posto. Continua a seguire la pagina dell\'evento per ogni aggiornamento!')])
                            ->label(AmosEvents::t('amosevents', "Messaggio di avviso per esaurimento posti disponibili")) ?>
                    </div>
                </div>

            </div>
        </div>


    </div>

</div>