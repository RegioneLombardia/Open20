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


$js = <<<JS
$('#update-preview').click(function(e){
    e.preventDefault();
    var tmpImageSrc = $('#main-image .preview-container img').attr('src');
    var title = $('#event-title').val();
    var location_id = $("input[name='Event[event_location_id]']:checked").val();
    var entrance_id = $("input[name='Event[event_location_entrance_id]']:checked").val();
    var date_begin = $('#begin-date-id-disp').val();

    var location_id_dom = $("#event-event_location_id input[value='"+location_id+"']").attr('id');
    var entrance = $("#event-event_location_entrance_id label[for='event[event_location_entrance_id]-"+entrance_id+"']").text();
    var location = $("#event-event_location_id label[for='"+location_id_dom+"']").text();
    var style = 'background-image: url('+tmpImageSrc+')';
    
    $('#preview-template .preview-landing').attr('style', style);
    $('#preview-template .preview-title-landing').text(title);
    $('#preview-template .preview-info-event').text(location);
    $('#preview-template .preview-date-event').text(date_begin);
    
});

$('#switch-logo-mobile').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#container-logo-mobile').show();
        }else {
            $('#container-logo-mobile').hide(); 
        }
});
JS;
$this->registerJs($js);
?>
<?php $hideImage = '';
if ($model->eventType->patronage) {
    $hideImage = 'display:none';
} ?>
<div id="event-images" style="<?= $hideImage ?>">
    <div class="immagine-landing my-4">
        <div class="py-4 d-inline-flex w-100 title-substeps affix-top">
            <div class="mr-2">
                <svg class="icon">
                    <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_image"></use>
                </svg>
            </div>

            <h5 class="font-weight-bold ">
                <?= AmosEvents::t('amosevents', "Inserisci un'immagine di copertina") ?>
                <a href="#" data-toggle="tooltip" data-html="true"
                   title="<?= AmosEvents::t('amosevents', "Per personalizzare il tuo evento, inserisci un'immagine di copertina. L'immagine di copertina è visibile a chi può iscriversi all'evento.<br> Puoi caricare anche una Immagine dell'evento per Dispositivi Mobile, per avere una visualizzazione ottimale anche sui dispositivi mobili.") ?>">
                    <svg class="icon icon-xs icon-secondary mb-2">
                        <use xlink:href="<?= $spriteAsset->baseUrl ?>/material-sprite.svg#ic_help"></use>
                    </svg>
                </a>
            </h5>
        </div>


        <div class="row variable-gutters">
            <div id="main-image" class="col-md-12">
                <div>
                    <?=
                    $form->field($model, 'eventLogo')->widget(
                        \open20\amos\attachments\components\CropInput::classname(),
                        [
                            'jcropOptions' => ['aspectRatio' => '1.7', 'placeholder' => AmosEvents::t('amosevents', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')],
                            'options' => [
                                'placeholder' => AmosEvents::t('amosevents', 'Per una visualizzazione ottimale, carica un\'immagine rettangolare 1920x1080 pixel.')
                            ]
                        ]
                    )->label(AmosEvents::t('amosevents', "Immagine principale dell'evento"))
                    ?>
                </div>
            </div>
        </div>


        <?php
        if (!empty($model->eventLogoMobile)) {
            $hideLogoMobile = '';
            $value = 1;
        } else {
            $hideLogoMobile = 'display:none';
            $value = 0;

        } ?>
        <div class="row variable-gutters my-4">
            <div class="col-md-12 pb-3">
                <label><?= AmosEvents::t('amosevents', "Vuoi inserire un’immagine per dispositivi mobili?") ?></label>
                <?php echo \kartik\widgets\SwitchInput::widget([
                    'name' => 'switch-logo-mobile',
                    'value' => $value,
                    'options' => ['id' => 'switch-logo-mobile' ],
                    'pluginOptions' => [
                        //'size' => 'mini',
                        //                    'onText' => AmosIcons::show('check-circle'),
                        //                    'offText' => AmosIcons::show('circle-o')
                        'onText' => 'Si',
                        'offText' => 'No'
                    ],
                ])
                ?>
<!--             --><?php //AmosEvents::t('amosevents', "Vuoi inserire un’immagine per dispositivi mobili?") ?>
            </div>
            <div id="container-logo-mobile" class="col-md-12" style="<?= $hideLogoMobile ?>">
                <div>
                    <?=
                    $form->field($model, 'eventLogoMobile')->widget(
                        \open20\amos\attachments\components\CropInput::classname(),
                        [
                            'jcropOptions' => ['aspectRatio' => '1.0']
                        ]
                    )->label(AmosEvents::t('amosevents', "Immagine dell'evento per Dispositivi Mobile"))
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>