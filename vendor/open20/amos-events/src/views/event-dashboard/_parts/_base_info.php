<?php

use open20\amos\events\AmosEvents;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\events\assets\WizardEventAsset;
use yii\bootstrap4\ActiveForm;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use open20\amos\events\utility\EventsUtility;

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$eventTypeSearchModel = $moduleEvents->createModel('EventTypeSearch');
$types = $eventTypeSearchModel::searchGenericContextEventTypes()->all();
$moduleCwh = \Yii::$app->getModule('cwh');
$scope = null;
if (!empty($moduleCwh)) {
    $scope = $moduleCwh->getCwhScope();
}
$eventType = $model->eventType;
$eventTypePresent = !is_null($eventType);
$eventTypeWithLimitedSeats = ($eventTypePresent && $eventType->limited_seats);



$limitedSeatsTypes = \open20\amos\events\models\Event::getEventsTypeWithLimitedSeats();
$informativeTypes = \open20\amos\events\models\Event::getEventsTypeWithInformativeType();
$privateTypes = \open20\amos\events\models\Event::getEventsTypeWithPrivate();
$patronageTypes = \open20\amos\events\models\Event::getEventsTypeWithPatronage();
$streamingTypes = \open20\amos\events\models\Event::getEventsTypeForStreaming();

$limitedSeatsTypeIds = [];
foreach ($limitedSeatsTypes as $type) {
    $limitedSeatsTypeIds[] = $type->id;
}
$informativeTypesIds = [];
foreach ($informativeTypes as $type) {
    $informativeTypesIds[] = $type->id;
}
$privateTypesIds = [];
foreach ($privateTypes as $type) {
    $privateTypesIds[] = $type->id;
}
$patronageTypesIds = [];
foreach ($patronageTypes as $type) {
    $patronageTypesIds[] = $type->id;
}
$streamingTypesIds = [];
foreach ($streamingTypes as $type) {
    $streamingTypesIds[] = $type->id;
}

$implode = implode(',', $limitedSeatsTypeIds);
$implodeInformative = implode(',', $informativeTypesIds);
$implodePrivate = implode(',', $privateTypesIds);
$implodePatronage = implode(',', $patronageTypesIds);
$implodeStreaming = implode(',', $streamingTypesIds);

$currentDg = EventsUtility::getCurrentDg();
$currentDgId = null;
if ($currentDg) {
    $currentDgId = $currentDg->id;
}

$enableEventContainer = $moduleEvents->enableEventContainer;
$patronage_luya_nav_id = '';
if(!empty($moduleEvents->patronage_luya_nav_id)){
    $patronage_luya_nav_id = $moduleEvents->patronage_luya_nav_id;
}

$locationStreaming = \open20\amos\events\models\EventLocation::find()->andWhere(['is_location_streaming' => true])->one();
if($locationStreaming){
    $idLocationStreaming = $locationStreaming->id;
}
else {
    $idLocationStreaming = 99999999;
}
$currentType = $model->event_type_id ? $model->event_type_id : 9999999;
$js = <<<JS
    function hideShowTags(remove){
           var custom_or_default = $("input[name='Event[use_default_custom_tags]']:checked").val();
            if(custom_or_default == 2){
                $('#cont-customTagsDefault').hide();
                $('#cont-customTags').show();
                if(remove){
                    $('#custom-tags-default-id').val('');
                    $('#custom-tags-default-id').trigger('change');
                }
            }
            else {
                 $('#cont-customTagsDefault').show();
                $('#cont-customTags').hide(); 
                if(remove){
                $('#custom-tags-id').tagit("removeAll");
                }
            }
    }
    
    $('#EventType').on('select2:select, change',function(){
        var ids = '$implode';
        var informativeIDS = '$implodeInformative';
        var privateIDS = '$implodePrivate';
        var patronageIDS = '$implodePatronage';
        var streamingIDS = '$implodeStreaming';
        var arrayInfIds = informativeIDS.split(",");
        var privateIds = privateIDS.split(",");
        var patronageIds = patronageIDS.split(",");
        var streamingIds = streamingIDS.split(",");
        var arrayIds = ids.split(",");
        
        // limited_seats
        if(arrayIds.indexOf($(this).val()) >= 0){
            $('#limited-seats-container').show();
            $('#container-has-tickets').show();
            $('#container-qr-code').show();
        }else {
             $('#limited-seats-container').hide();
             $('#container-has-tickets').hide();
             $('#container-qr-code').hide();
        }
        
        // if type informative hide some ssections
       if(arrayInfIds.indexOf($(this).val()) >= 0){
            // console.log($('.informative-to-hide'));
            $('.informative-to-hide').each(function(){
                $(this).hide();
            });
        }else {
               $('.informative-to-hide').each(function(){
                $(this).show();
            });
        }
        
          // if type private
          //console.log(privateIds);
       if(privateIds.indexOf($(this).val()) >= 0){
           $('#publish-to-prl-id').val(0);
        }
        
       if(patronageIds.indexOf($(this).val()) >= 0){
           $('#event-images').hide();
           $('#hr-image').hide();
           $('#luya-template-id .custom-radio').each(function(){
               var radio = $(this);
               var input = $(this).children('input');
              if($(input).val() !== '$patronage_luya_nav_id'){
                   $(radio).hide();
              }
              else {
                  $(input).trigger('click');
              }
           });
        }else{
            $('#luya-template-id .custom-radio').each(function(){
               var radio = $(this);
               $(radio).show();
           });
           $('#event-images').show();
           $('#hr-image').show();
        }
        
        var streaming_radio = $('#event-event_location_id input[value="$idLocationStreaming"]');
        if(streamingIds.indexOf($(this).val()) < 0){
            $(streaming_radio).parents('.custom-control.custom-radio').hide();
             $(streaming_radio).removeAttr('checked')
        }
        else{
            $(streaming_radio).parents('.custom-control.custom-radio').show();
        }
        
        
        // get description of an event
       $.ajax({
            url: '/events/event-dashboard/description-type-event',
           type: 'get',
           data: {event_type_id: $(this).val()},
           success: function (data) {
              $('#tooltip-type').html(data);
           }
      });
    });

    $('#default-tags').change(function(){
         hideShowTags(true);
    });
    
    
    
$('#is-father').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
             $('#choose-father').hide();
             $('#event-father-id').val('');
             $('#event-father-id').trigger('change');
             $('#container-type-id').hide();
             $('#label-type-informative').show();
             $('#limited-seats-container').hide();
             $('#EventType').val(100);
             $('#EventType').trigger('change');

             
        }
        else {
             $('#container-type-id').show();
             $('#choose-father').show();
             $('#label-type-informative').hide();
        }
    });


    hideShowTags(false);
    
        //show hide radio evento streaming
           var streamingIDS_x = '$implodeStreaming';
           var streamingIds_x = streamingIDS_x.split(",");
           var streaming_radio_x = $('#event-event_location_id input[value="$idLocationStreaming"]');
            if(streamingIds_x.indexOf($('#EventType').val()) < 0){
                $(streaming_radio_x).parents('.custom-control.custom-radio').hide();
                $(streaming_radio_x).removeAttr('checked')
            }
            else{
                $(streaming_radio_x).parents('.custom-control.custom-radio').show();
            }

JS;

$this->registerJs($js);

?>

<?php
$tagitPlaceholderJs = <<< JS
$(document).ready(function(){
    $('.tagit-new > input').attr('placeholder','Inserisci una parolachiave');
})
JS;
$this->registerJs($tagitPlaceholderJs);
?>
<!-- TAGIT WIDGET STYLE -->
<style>
    ul.tagit > li.tagit-new {
        float: none;
    }

    ul.tagit > li.tagit-new > input {
        width: 100%;
    }
</style>

<div class="tipo-evento my-4">
    <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
        <div class="mr-2">
            <svg class="icon">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_event"></use>
            </svg>
        </div>

        <h5 class="font-weight-bold ">
            <?= AmosEvents::t('amosevents', "Che tipo di evento vuoi creare?") ?>
        </h5>
    </div>


    <div class="row variable-gutters">
        <div class="col-md-12">



            <?php $hidetype = '';
            if($model->is_father){
                $hidetype = 'display:none';
            }?>

            <div class="col-md-12 mt-4" id="container-type-id" style="<?= $hidetype?>">

                <?= $form->field($model, 'event_type_id')->widget(Select2::className(), [
                    'data' => ArrayHelper::map($types, 'id', 'title'),
                    'language' => substr(Yii::$app->language, 0, 2),
                    'options' => [
                        'multiple' => false,
                        'id' => 'EventType',
                        'placeholder' => AmosEvents::t('amosevents', 'Seleziona il tipo di evento') . '...',
                        'class' => 'dynamicCreation',
                        'data-model' => 'event_type',
                        'data-field' => 'name',
                        'data-module' => 'events',
                        'data-entity' => 'event-type',
                        'data-toggle' => 'tooltip'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]) ?>
                <p><small id="tooltip-type" class="form-text text-muted"><?= !empty($model->eventType) ? $model->eventType->description : ''?></small></p>
            </div>
            <?php
            if(!empty($hidetype)){
                $showLabelType = '';
            }else {
                $showLabelType = 'display:none';
            }?>
            <div id="label-type-informative" style="<?= $showLabelType?>">
                <?php $informativeTypeModel = \open20\amos\events\models\EventType::find()->andWhere(['id' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE])->one()?>
                <p><strong><?= AmosEvents::t('amosevents', 'Tipo evento').'</strong>: '. ($informativeTypeModel ? $informativeTypeModel->title : '')?></p>
            </div>


            <?php
            if ($eventTypeWithLimitedSeats) {
                $diplayNone = '';
            } else {
                $diplayNone = 'display:none';
            }
            $disabled = false;
            if ($model->isSeatManagement()) {
                $disabled = true;
            }
            /** @var Event $eventModel */
            $eventModel = $moduleEvents->createModel('Event');
            ?>

            <div id="limited-seats-container" style="<?= $diplayNone ?>">
                <?= $form->field($model, 'seats_available')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
            </div>
            <!-- Lasciare così la findOne perché deve prendere sempre il valore da db e non quello caricato nel model (tipo quando si salva e ci sono errori). Forse basta fare getOldAttribute ma è da testare -->
            <?php
            $modelFromDb = open20\amos\events\models\Event::findOne($model->id);
            if ($modelFromDb && $modelFromDb->isSeatManagement()) { ?>
                <div class="mt-4">
                    <?php
                    echo Html::button(
                        AmosEvents::t('amosevents', "Importa posti"),
                        [
                            'class' => 'btn btn-primary pull-left',
                            'data-toggle' => 'modal',
                            'data-target' => '#modalImport',
                        ]
                    );
                    ?>
                </div>
            <?php } ?>


        </div>


    </div>


</div>
<hr class="dashed">

<div class="info-base my-4">
    <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
        <div class="mr-2">
            <svg class="icon">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_info"></use>
            </svg>
        </div>

        <h5 class="font-weight-bold">
            <?= AmosEvents::t('amosevents', "Informazioni di base") ?>
            <a href="#" data-toggle="tooltip" data-html="true"
               title="<?= AmosEvents::t('amosevents', "Il titolo verrà visualizzato nella landing page del tuo evento") ?>">
                <svg class="icon icon-xs icon-secondary mb-2">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                </svg>
            </a>

        </h5>
    </div>

    <div class="row variable-gutters my-6">
        <div class="col-md-6">

            <?= $form->field($model, 'title')->textInput(['id' => 'event-title', 'placeholder' => AmosEvents::t('amosevents', 'Inserisci il titolo del tuo evento')])
                ->hint(AmosEvents::t('amosevents', "Il titolo verrà visualizzato nella landing page del tuo evento")) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'event_category_id')->label(AmosEvents::t('amosevents', 'Tipologia'))->hint(AmosEvents::t('amosevents', "La Tipologia specifica l'evento in base alle sue caratteristiche di contenuto e modalità di svolgimento"))
                ->widget(Select2::className(), [
                    'data' => ArrayHelper::map(\open20\amos\events\models\EventCategory::find()->all(), 'id', 'name'),
                    'options' => ['placeholder' => AmosEvents::t('amosevents', 'Seleziona la tipologia di evento...')]
                ]) ?>
        </div>
    </div>

    <div class="row variable-gutters my-5">
        <div class="col-md-6">
            <?= $form->field($model, 'summary')->textarea(['rows' => 5, 'placeholder' => AmosEvents::t('amosevents', 'Scrivi un Sottotitolo per il tuo evento. Usa un massimo di 50 caratteri.')])->label(AmosEvents::t('amosevents', 'Sottotitolo'))
                ->hint(AmosEvents::t('amosevents', "Il Sottotitolo precisa il titolo del tuo evento.")) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textarea(['rows' => 5, 'placeholder' => AmosEvents::t('amosevents', 'Scrivi una Descrizione per il tuo evento e aggiungi dettagli come l\'argomento, gli sponsor o gli ospiti in evidenza. Usa un massimo di 150 caratteri.')])
                ->hint(AmosEvents::t('amosevents', "La Descrizione è visibile su <a target='_blank' href='https://www.regione.lombardia.it'>www.regione.lombardia.it</a>, può essere inserita nelle email di invito e può essere usata come criterio per la ricerca interna alla piattaforma.")) ?>
        </div>

    </div>
</div>
<hr class="dashed">
<div class="preferences-center">
    <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
        <div class="mr-2">
            <svg class="icon">
                <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_star"></use>
            </svg>
        </div>
        <h5 class="font-weight-bold ">
            <?= AmosEvents::t('amosevents', "Spunta 1 o più argomenti del Preference Centre per assegnare un Tag di Preference Centre al tuo evento.") ?>
        </h5>
    </div>


    <div class="row variable-gutters my-5">
        <div class="col-md-12">
            <div>
                <?= $form->field($model, 'preferencesTags')
                    ->checkboxList(EventsUtility::getLabelPreference(), ['encode' => false])->label(false) ?>
            </div>
        </div>

    </div>
    <div class="row variable-gutters my-5">
        <div class="col-md-3 mr-auto">
            <p class="text-muted">
                <?= AmosEvents::t('amosevents', "Aggiungi 1 o più Tag di Lista Evento per assegnare al tuo evento una o più parole chiave. Potrai ricercare il tuo evento nella lista Eventi usando il Tag di Lista Evento.") ?>
                <br>
            </p>
        </div>

        <div class="col-md-8">
            <?php
            if (!empty($model->customTags)) {
                $model->use_default_custom_tags = 2;
            }
            if (empty($model->use_default_custom_tags)) {
                $model->use_default_custom_tags = 1;
            } ?>

            <?= $form->field($model, 'use_default_custom_tags')->radioList([1 => 'Default', 2 => AmosEvents::t('amosevents', 'Other')], ['id' => 'default-tags'])->label(false) ?>
            <div id="cont-customTagsDefault" style="display:none">
                <?= $form->field($model, 'customTagsDefault')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(\open20\amos\events\models\Event::getTagCustomDefault(), 'id', 'nome'),
                    'options' => [
                        'id' => 'custom-tags-default-id',
                        'placeholder' => AmosEvents::t('amosevents', 'Select...')
                    ],
                    'pluginOptions' => ['multiple' => true]
                ])->label(AmosEvents::t('amosevents', 'Tag di Lista Evento')) ?>
            </div>
            <div id="cont-customTags" style="display:none">
                <?= $form->field($model, 'customTags')->widget(\xj\tagit\Tagit::className(), [
                    'options' => [
                        'id' => 'custom-tags-id',
                        'placeholder' => AmosEvents::t('amosevents', 'Inserisci una parolachiave')
                    ],
                ])->label(AmosEvents::t('amosevents', '')) ?>
            </div>
        </div>
    </div>
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

                <h5 class="font-weight-bold"><?= AmosEvents::t('amosevents', "Impostazioni avanzate Info evento") ?></h5>
            </div>
        </div>

        <div class="col-md-8 pb-3">
            <?= $form->field($model, 'advanced_options_event')->widget(\kartik\widgets\SwitchInput::className(),[
                'options' => ['id' => 'switch-settings'],
                'pluginOptions' => [
                    //'size' => 'mini',
                    //                    'onText' => AmosIcons::show('check-circle'),
                    //                    'offText' => AmosIcons::show('circle-o')
                    'onText' => '+',
                    'offText' => '-'
                ],
            ])->label(false)?>
        </div>
    </div>


    <?php $hideAdvanced = 'display:none';
    if($model->advanced_options_event){
        $hideAdvanced = '';
    }
    ?>
    <div id="settings-div" style="<?= $hideAdvanced?>" class="p-2 p-sm-5 neutral-1-bg-a1">
        <?= $this->render('@vendor/open20/amos-events/src/views/wizard/_advanced_settings', [
            'model' => $model,
            'form' => $form,
            'moduleEvents' => $moduleEvents,

        ]) ?>
    </div>


    <div class="row" hidden>
        <div>
            <div class="receiver-section">
                <?php echo
                \open20\amos\cwh\widgets\DestinatariPlusTagWidget::widget([
                    'model' => $model,
                    'moduleCwh' => $moduleCwh,
                    'scope' => $scope
                ]);
                ?>

                <?php
                $moduleSeo = \Yii::$app->getModule('seo');
                if (isset($moduleSeo)) { ?>
                    <div class="row">
                        <div>
                            <?= Html::tag(
                                'h2',
                                AmosEvents::t('amosevents', '#settings_seo_title'),
                                ['class' => 'subtitle-form']
                            )
                            ?>
                            <div class="col-12 receiver-section">
                                <?=
                                \open20\amos\seo\widgets\SeoWidget::widget([
                                    'contentModel' => $model,
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </div>


        </div>
    </div>
</div>