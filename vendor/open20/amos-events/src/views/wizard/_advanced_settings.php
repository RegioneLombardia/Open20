<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\helpers\Html;
use kartik\datecontrol\DateControl;
use open20\amos\events\utility\EventsUtility;

$eventType = $model->eventType;
$eventTypePresent = !is_null($eventType);
$eventTypeWithLimitedSeats = ($eventTypePresent && $eventType->limited_seats);
$enableEventContainer = $moduleEvents->enableEventContainer;

$jsTicket = <<<JS
    $('#has-ticket').click(function(){
        var value = $(this).val();
        if(value == 1){
            $("#container-seats-management").show();
        }else {
            $("#container-seats-management").hide();
            $('#event-seats_management').val(0)
        }
    });

$('#event-seats_management').click(function(){
     var value = $(this).val();
     if(value == 1){
         $('#event-seats_available').val(0);
         $('#event-seats_available').attr('readonly','true')
     }
});
JS;

$this->registerJs($jsTicket);
?>
<div class="row">


    <div class="col-12"><h6><?= AmosEvents::t('amosevents', '#form_section_advanced') ?></h6></div>
    <div class="col-md-3 mr-auto">
        <p><?= AmosEvents::t('amosevents', "Informazioni aggiuntive") ?></p>
    </div>

    <div class="col-md-8">
        <!--        <div>-->
        <!--            --><?php
        //            $form->field($model, 'conference_call')
        //            ?>
        <!--        </div>-->
        <div>
            <!--            --><?php
            //            $form->field($model, 'dial_code')
            //            ?>
        </div>

        <?php if ($enableEventContainer) { ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'is_father')->widget(\kartik\widgets\SwitchInput::className(), [
                        'options' => ['id' => 'is-father'],
                        'pluginOptions' => [
                            //'size' => 'mini',
                            //                    'onText' => AmosIcons::show('check-circle'),
                            //                    'offText' => AmosIcons::show('circle-o')
                            'onText' => 'Si',
                            'offText' => 'No'
                        ],
                    ])->label(AmosEvents::t('amosevents', 'Is an event father')) ?>
                </div>

                <?php
                $hideFather = '';
                if ($model->is_father) {
                    $hideFather = 'display:none;';
                } ?>

                <div id="choose-father" class="col-md-6" style="<?= $hideFather ?>">
                    <?php if (EventsUtility::eventFathersQuery($currentDgId)->count() == 0) { ?>
                        <label><?= AmosEvents::t('amosevents', 'Evento padre') ?></label>
                        <p><?= AmosEvents::t('amosevents', 'Non è presente nessun evento padre') ?></p>
                    <?php } else { ?>
                        <?= $form->field($model, 'event_id')->widget(Select2::className(), [
                            'data' => ArrayHelper::map(EventsUtility::eventFathersQuery($currentDgId)->all(), 'id', 'title'),
                            'options' => [
                                'placeholder' => AmosEvents::t('amosevents', 'Select...'),
                                'id' => 'event-father-id'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => AmosEvents::t('amosevents', 'Select...'),
                            ],
                        ])->label(AmosEvents::t('amosevents', 'Evento padre')) ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>


        <div style="display:none" class="row">
            <!--            --><?php
            //            if ($moduleEvents->hidePubblicationDate == false) {
            //                ?>
            <!--                <div class="col-md-6">-->
            <!--                    --><?php //echo
            //                    $form->field($model, 'publication_date_begin')->widget(DateControl::className(),
            //                        [
            //                            'type' => DateControl::FORMAT_DATETIME
            //                        ])->hint(AmosEvents::t('amosevents', '#publication_date_begin_hint'))
            //                    ?>
            <!--                </div>-->
            <!--                <div class="col-md-6">-->
            <!--                    --><?php //echo
            //                    $form->field($model, 'publication_date_end')->widget(DateControl::className(),
            //                        [
            //                            'type' => DateControl::FORMAT_DATETIME
            //                        ])->hint(AmosEvents::t('amosevents', '#publication_date_end_hint'))
            //                    ?>
            <!--                </div>-->
            <!--            --><?php //} ?>
            <?php
            if ($moduleNotify && !empty($moduleNotify->enableNotificationContentLanguage) && $moduleNotify->enableNotificationContentLanguage) { ?>
                <div class="col-md-6">
                    <?php echo
                    \open20\amos\notificationmanager\widgets\NotifyContentLanguageWidget::widget(['model' => $model]);
                    ?>
                </div>
            <?php } ?>
            <?php if ($eventTypeWithLimitedSeats): ?>

                <div class="col-md-6">
                    <?= $form->field($model, 'numero_max_accompagnatori')->textInput(['type' => 'number']); ?>
                </div>

            <?php endif; ?>

            <!--            <div class="col-md-6">-->
            <!--                --><?php //echo
            //                $form->field($model, 'registration_date_begin')->widget(DateControl::className(),
            //                    [
            //                        'type' => DateControl::FORMAT_DATETIME
            //                    ])
            //                ?>
            <!--            </div>-->
            <!--            <div class="col-md-6">-->
            <!--                --><?php //echo
            //                $form->field($model, 'registration_date_end')->widget(DateControl::className(),
            //                    [
            //                        'type' => DateControl::FORMAT_DATETIME
            //                    ])
            //                ?>
            <!--            </div>-->


        </div>

        <div style="" class="row">

            <div class="col-lg-12" style="display:none">
                <?=
                $form->field($model, 'event_commentable')->dropDownList(
                    Html::getBooleanFieldsValues(),
                    [
                        'options' => $model->isNewRecord ? [$moduleEvents->forceEventCommentable => ['Selected' => true]] : null,
                        'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,
                    ]
                )
                ?>
            </div>

            <?php
            $strhideType = '';
            if (!$eventTypeWithLimitedSeats) {
                $strhideType = "display:none";
            }
            ?>
            <div class="informative-to-hide" style="<?= $strhideType ?>">

                <div class="col-lg-12" style="display:none">
                    <?php echo
                    $form->field($model, 'abilita_codice_fiscale_in_form')->dropDownList(
                        Html::getBooleanFieldsValues(),
                        ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
                    )
                    ?>
                </div>


                <div id="container-has-tickets" class="col-lg-12 informative-to-hide" style="<?= $strhideType ?>">
                    <?php echo
                    $form->field($model, 'has_tickets')->dropDownList(
                        Html::getBooleanFieldsValues(),
                        [
                            'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'),
                            'disabled' => false,
                            'id' => 'has-ticket'
                        ]
                    )
                    ?>
                </div>

                <div id="container-manage-list" class="col-lg-12 informative-to-hide" style="<?= $strhideType ?>">
                    <?php
                    if ($model->isNewRecord) {
                        $model->manage_waiting_list = true;
                    }
                    echo
                    $form->field($model, 'manage_waiting_list')->dropDownList(
                        Html::getBooleanFieldsValues(),
                        [
                            'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'),
                            'disabled' => false,
                            'id' => 'manage-waiting-list'
                        ]
                    )->label(AmosEvents::t('amosevents', 'Gestisci lista di attesa'))
                        ->hint(AmosEvents::t('amosevents', "Permette di far registrare gli utenti anche dopo il raggiungimento del numero massimo di posti, inserendoli in lista di attesa."));
                    ?>
                </div>


                <?php
                $strhide = '';
                if (!$model->has_tickets || !$eventTypeWithLimitedSeats) {
                    $strhide = "display:none";
                }
                ?>
                <?php if ($moduleEvents->enableSeatsManagement): ?>
                    <div id="container-seats-management" class="col-lg-12" style="<?= $strhide ?>">
                        <?php echo
                        $form->field($model, 'seats_management')->dropDownList(
                            Html::getBooleanFieldsValues(),
                            ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
                        )->hint(AmosEvents::t('amosevents',
                            "Con gestione posti uguale a 'si' occorre effettuare l'importazione dei posti da file excel"))
                        ?>
                    </div>
                <?php endif; ?>


                <div id="container-qr-code" class="col-lg-12" style="<?= $strhideType ?>">
                    <?php echo
                    $form->field($model, 'has_qr_code')->dropDownList(
                        Html::getBooleanFieldsValues(),
                        ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
                    )
                    ?>
                </div>

                <?php if ($moduleEvents->enableCalendarsManagement): ?>
                    <div id="container-seats-management" class="col-lg-6">
                        <?php echo
                        $form->field($model, 'slots_calendar_management')->dropDownList(
                            Html::getBooleanFieldsValues(),
                            ['prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'), 'disabled' => false,]
                        )
                        ?>
                    </div>
                <?php endif; ?>
            </div>


            <?php if ($moduleEvents->enableEventContainer): ?>
                <div id="container-publish-on-prl" class="col-lg-12">
                    <?php
                    if ($model->isNewRecord) {
                        $model->publish_on_prl = true;
                    }
                    echo
                    $form->field($model, 'publish_on_prl')->dropDownList(
                        Html::getBooleanFieldsValues(),
                        [
                            'id' => 'publish-to-prl-id',
                            'prompt' => AmosEvents::t('amosevents', 'Select/Choose' . '...'),
                            'disabled' => false,
                        ]
                    )
                        ->label(AmosEvents::t('amosevents', 'Pubblica anche su www.regione.lombardia.it'))
                    ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php // $this->render('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_modal_import', ['model' => $model]); ?>
