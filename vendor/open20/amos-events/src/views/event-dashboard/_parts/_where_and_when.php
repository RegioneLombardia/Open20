<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\assets\WizardEventAsset;
use yii\bootstrap4\ActiveForm;
//use open20\amos\core\forms\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use \kartik\datecontrol\DateControl;


/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$scope = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}


$this->registerJs("
    $('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "').change(function(){
        if($('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "').val() == ''){
            $('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date-remove').remove();
        } else {
            if($('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate .input-group-addon.kv-date-remove').length == 0){
                $('#event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp-kvdate').append('<span class=\"input-group-addon kv-date-remove\" title=\"Pulisci campo\"><i class=\"glyphicon glyphicon-remove\"></i></span>');
                initDPRemove('event-begin_date" . ((isset($fid)) ? $fid : 0) . "-disp');
            }
        }
    });
", yii\web\View::POS_READY);

$eventId = $model->id;
$js = <<<JS
    $('#switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#settings-div').show();
        }
        else {
           $('#settings-div').hide();
        }
    });

    function getEntrances(){
         var value = $("input[name='Event[event_location_id]']:checked").val();
         var url = '/events/event-location/get-entrances?id='+value;
         if('$eventId' !== ''){
             url = url+'&eventId='+'$eventId';
         }
           $.ajax({
            url: url,
           type: 'get',
           success: function (data) {
              console.log(data);
                $('#location-container').html(data['html']);
                if(typeof(initMap_event_place_id) === typeof(Function)){
                    initMap_event_place_id();
                   // console.log('entrato');
                }
           }
      });
    }
    
    $('input[name="Event[event_location_id]"]').click(function(){
       getEntrances();
    });
    

   getEntrances();
    
JS;
$this->registerJs($js);

$jsEnterTime = <<<js
      $('#begin-hour-id').change(function(){
        var date = $('#begin-date-id').val();
        var hour = $(this).val();
        var datecomplete = date+ ' '+hour;
        // console.log(datecomplete);
        var objdate = new Date(datecomplete);
        objdate.setMinutes(objdate.getMinutes() - 30);
         // console.log(objdate.toLocaleTimeString());
         $('#enter-time-id-disp').timepicker('setTime',objdate.toLocaleTimeString());
    });
js;


if($model->isNewRecord){
    $this->registerJs($jsEnterTime);
}

$strHide = '';
if ($model->eventType->event_type == \open20\amos\events\models\EventType::TYPE_INFORMATIVE) {
    $strHide = 'display:none;';
}
?>
<hr class="dashed">
<div class="dove-evento my-4">
    <div>
        <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_place"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Dove si svolgerà l'evento?") ?>
                <a href="#" data-toggle="tooltip" data-html="true"
                   title="<?= AmosEvents::t('amosevents', "Per indicare dove si svolgerà il tuo evento, seleziona il Luogo di Svolgimento tra quelli in elenco. Se il luogo del tuo evento non è presente nell'elenco, seleziona Altro e aggiungi l'indirizzo manualmente.") ?>">
                    <svg class="icon icon-xs icon-secondary mb-2">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                    </svg>
                </a>
            </h5>
        </div>
        <div class="row variable-gutters">
            <div class="col-md-12 text-muted">
                <strong><?php echo $form->field($model, 'event_location_id')->label(AmosEvents::t('amosevents', 'Luogo di Svolgimento'))->radioList($locationsForRadio); ?></strong>
                <?php ?>
                <div id="location-container" class="my-5">
                </div>

            </div>
        </div>
    </div>
    <hr class="dashed">
    <div class="quando-evento my-4">
        <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_calendar-clock"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Quando si svolgerà l'evento") ?>
                <a href="#" data-toggle="tooltip" data-html="true"
                   title="<?= AmosEvents::t('amosevents', "Indica quando si svolgerà l'evento e quando sarà attivo il modulo di iscrizione all'evento.") ?>">
                    <svg class="icon icon-xs icon-secondary mb-2">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                    </svg>
                </a>
            </h5>
        </div>

        <div class="row variable-gutters">


            <div class="col-lg-6">
                <?= $form->field($model, 'beginDate')->widget(\kartik\datecontrol\DateControl::className(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                    'options' => [
                        'id' => 'begin-date-id',
                        //                                'layout' => '{input} {picker} ' . (($model->beginDate == '') ? '' : '{remove}')
                    ]
                ])->label(AmosEvents::t('amosevents', 'Begin Date'));
                ?>
            </div>
            <div class="col-lg-6">

                <?= $form->field($model, 'beginHour')->widget(\kartik\datecontrol\DateControl::className(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
                    'options' => [
                        'id' => 'begin-hour-id',
                        'layout' => '{input} {picker} ' . (($model->beginDate == '') ? '' : '{remove}')
                    ]
                ])->label(AmosEvents::t('amosevents', 'Begin Hour'));
                ?>
                <!--                        'beginDate',-->
                <!--                        'beginHour',-->
                <!--                        'endDate',-->
                <!--                        'endHour',-->
                <!--                        --><?php //$form->field($model, 'begin_date_hour')->widget(\kartik\datecontrol\DateControl::className(), [
                //                            'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                //                            'options' => [
                //                                'id' => $beginDateHourId,
                //                                'layout' => '{input} {picker} ' . (($model->begin_date_hour == '') ? '' : '{remove}')]
                //                        ]);
                ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'endDate')->widget(\kartik\datecontrol\DateControl::className(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                    'options' => [
                        'id' => 'end-date-id',
                        'layout' => '{input} {picker} ' . (($model->endDate == '') ? '' : '{remove}')
                    ]
                ])->label(AmosEvents::t('amosevents', 'End Date'));
                ?>
            </div>
            <div class="col-lg-6">

                <?= $form->field($model, 'endHour')->widget(\kartik\datecontrol\DateControl::className(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
                    'options' => [
                        'id' => 'end-hour-id',
                        'layout' => '{input} {picker} ' . (($model->endHour == '') ? '' : '{remove}')
                    ]
                ])->label(AmosEvents::t('amosevents', 'End Hour'));
                ?>
                <!--                        --><?php //$form->field($model, 'end_date_hour')->widget(\kartik\datecontrol\DateControl::className(), [
                //                            'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                //                            'options' => [
                //                                'id' => $beginDateHourId,
                //                                'layout' => '{input} {picker} ' . (($model->begin_date_hour == '') ? '' : '{remove}')]
                //                        ]);
                ?>
            </div>

        </div>
    </div>
    <hr class="dashed">
    <div id="publication-date-section" class="gestisci-modulo informative-to-hide" style="<?= $strHide ?>">

        <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
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


        <div class="row informative-to-hide variable-gutters" style="<?= $strHide ?>">
            <div class="col-xl-6">
                <?php if ($model->isNewRecord) {
                    $model->registration_date_begin = date('Y-m-d H:i:s');
                } ?>
                <?=
                $form->field($model, 'registration_date_begin')->label(AmosEvents::t('amosevents', 'Data e ora di apertura iscrizioni'))->widget(
                    DateControl::className(),
                    [
                        'type' => DateControl::FORMAT_DATETIME
                    ]
                )
                ?>
            </div>
            <div class="col-xl-6">
                <?=
                $form->field($model, 'registration_date_end')->label(AmosEvents::t('amosevents', 'Data e ora di chiusura iscrizioni'))->widget(
                    DateControl::className(),
                    [
                        'type' => DateControl::FORMAT_DATETIME
                    ]
                )
                ?>
            </div>
        </div>
    </div>
    <?php
    if ($moduleEvents->hidePubblicationDate == false) {
        if ($model->isNewRecord) {
            $model->publication_date_begin = date('Y-m-d H:i:s');
        }
        ?>
        <div class="row variable-gutters">
            <div class="col-xl-6">
                <?=
                $form->field($model, 'publication_date_begin')->label(AmosEvents::t('amosevents', 'Data e ora di inizio pubblicazione della landing page'))->widget(
                    DateControl::className(),
                    [
                        'type' => DateControl::FORMAT_DATETIME

                    ]
                )->hint(AmosEvents::t('amosevents', 'Se non imposti la data e l\'ora di inizio pubblicazione, coincideranno con l\'inizio dell\'evento.'))
                ?>
            </div>
            <div class="col-xl-6">
                <?=
                $form->field($model, 'publication_date_end')->label(AmosEvents::t('amosevents', 'Data e ora di fine pubblicazione della landing page'))->widget(
                    DateControl::className(),
                    [
                        'type' => DateControl::FORMAT_DATETIME
                    ]
                )->hint(AmosEvents::t('amosevents', 'Se non imposti data e ora di fine pubblicazione, l\'evento sarà sempre visibile.'))
                ?>
            </div>

            <div class="col-lg-6">
                <?php if(empty($model->enter_time)){
                    $model->enter_time =  date('H:i:s');
                }
                ?>
                <?= $form->field($model, 'enter_time')->widget(\kartik\datecontrol\DateControl::className(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
                    'options' => [
                        'id' => 'enter-time-id',
                    ]
                ])->label(AmosEvents::t('amosevents', 'Ora di entrata'));
                ?>
            </div>

        </div>
    <?php } ?>
<!--    <div class="row">-->
<!---->
<!--   -->
<!--    </div>-->

    <hr class="dashed">
</div>
