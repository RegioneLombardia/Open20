<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\SingleSelectionListTopicIcon;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\utility\TargetTagUtility;
use yii\helpers\VarDumper;
use yii\web\View;

/**
 * @var PreferenceUserTargetAttribute $targetAttributes
 */

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$labelTitle1 = '';
$labelTitle2 = '';

switch ($currentTargetCode) {
    case 'pctarget_enteeoperatore':
        $labelTitle1 = 'Ente / Operatore';
        $labelTitle2 = 'Enti / Operatori';
        break;
    case 'pctarget_cittadino':
        $labelTitle1 = 'Cittadino';
        $labelTitle2 = 'Cittadini';
        break;
    case 'pctarget_impresa':
        $labelTitle1 = 'Impresa';
        $labelTitle2 = 'Imprese';
        break;

    default:
        $labelTitle1 = '';
        $labelTitle2 = '';
        break;
}
?>


<div class="container pb-5">
    <div class="row variable-gutters">

        <!-- START SIDEBAR -->
        <div class="col-lg-3 col-md-4 d-none d-md-block">
            <!-- MENU SCELTA TARGET -->
            <div class="sidebar-wrapper sidebar-preference it-line-right-side h-100 affix-parent">
                <div class="sidebar-linklist-wrapper affix-top">
                    <div class="link-list-wrapper">
                        <ul class="link-list">
                            <li>
                                <h3 class="no_toc">Le tue iscrizioni:</h3>
                            </li>
                            <?php
                            $svgIconList = '<svg class="icon icon-tertiary icon-right"><use xlink:href="' . $bootstrapItaliaAsset->baseUrl . '/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-right"></use></svg>';
                            $statusActive = '<div><span class="badge badge-pill badge-primary status-iscrizioni status-active"><svg class="icon icon-white icon-xs m-0 mr-1"><use xlink:href="' . $bootstrapItaliaAsset->baseUrl . '/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-check"></use></svg>Attivo</span></div>';
                            $statusInActive = '<div><span class="badge badge-pill badge-outline-secondary status-iscrizioni text-muted"><svg class="icon icon-secondary icon-xs m-0 mr-1"><use xlink:href="' . $bootstrapItaliaAsset->baseUrl . '/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-minus"></use></svg>Non attivo</span></div>';
                            foreach ($listOfTarget as $targetTag) :
                                $isSelectedForThisUser = TargetTagUtility::isTargetSelectedForUser($targetTag, Yii::$app->user->id);
                                ?>
                                        <li>
                                            <?= Html::a(
                                                Html::tag('span', $targetTag->nome) . $svgIconList . ($isSelectedForThisUser? $statusActive: $statusInActive),
                                                [
                                                    'settings',
                                                    'target' => $targetTag->codice
                                                ],
                                                [
                                                    'class' => ($isSelectedForThisUser? 'list-item medium right-icon': 'list-item disabled') . (($targetTag->codice == $currentTargetCode)? ' active': ''),
                                                    'title' => ($isSelectedForThisUser?
                                                        'Visualizza preferenze come' . ' ' . $targetTag->nome:
                                                        'Non sei iscritto come' . ' ' . $targetTag->nome)
                                                ]
                                            ) ?>

                                        </li>


                            <?php
                            endforeach;

                            if(!$isTargetActive) {
                                $this->registerJs('$("#collapse1").collapse(\'show\');', View::POS_READY);
                            }

                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SIDEBAR -->

        <!-- START MAIN -->
        <div class="col-lg-9 col-md-8">
            <div class="setting-preference">
                <h1 class="mb-0 mt-4 logo-text-font">Lombardia <span>Informa</span></h1>
                <p class="text-muted">Rimani informato su tutte le tematiche di tuo interesse</p>
                
                <div class="collapse-div collapse-lg border border-light rounded" role="tablist">
                    <div class="collapse-header lightgrey-bg-c1 px-4 py-3" id="heading1">
                        <a data-toggle="collapse" role="button" href="#collapse1" aria-expanded="false" aria-controls="collapse1" class="btn btn-lg border-0 p-0">
                            <div class="d-flex flex-wrap align-items-center">
                                <img src="/img/phone.svg" class="mb-1 pr-3" alt="icona telefono">
                                <div class="h5">Modalità di contatto</div>
                            </div>

                            <div class="tertiary-color font-weight-normal">Gestisci i canali di contatto tramite i quali puoi ricevere comunicazioni come <strong><?= $labelTitle1 ?></strong></div>
                        </a>
                    </div>
                    <div id="collapse1" class="collapse" role="tabpanel" aria-labelledby="heading1">
                        <div class="collapse-body px-4 py-3">
                            <div class="row variable-gutters">
                                <div class="status-toggle col-sm-3 d-flex align-items-center order-sm-2 border-left border-light">
                                    <div class="mx-auto">
                                        <div class="toggles text-center">
                                            <label for="toggle-stato-id-<?= $targetAttributes->id ?>">
                                                <input type="checkbox" id="toggle-stato-id-<?= $targetAttributes->id ?>" <?= $isTargetActive ? 'checked' : ''; ?>>
                                                <span class="lever"></span>
                                                <span class="d-block jsSwitchLabel"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($isTargetActive): ?>
                                    <div class="col-sm-9 order-sm-1">
                                        <div class="pb-3 mt-5">
                                            <?php
                                            echo Yii::$app->controller->renderPartial('_modal_target_mail', [
                                                'targetAttributes' => $targetAttributes,
                                                'currentTargetTag' => $currentTargetTag,
                                            ]);
                                            ?>
                                        </div>
                                        <div class="pb-3">
                                            <?php
                                            echo Yii::$app->controller->renderPartial('_modal_target_phone', [
                                                'targetAttributes' => $targetAttributes,
                                                'currentTargetTag' => $currentTargetTag,
                                            ]);
                                            ?>

                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-sm-9 order-sm-1">
                                        <div class="callout callout-highlight d-sm-flex warning pl-2">
                                            <div class="callout-title mr-2 mb-2">
                                                <svg class="icon"><use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules//bootstrap-italia/dist/svg/sprite.svg#it-info-circle"></use></svg>
                                            </div>
                                            <p class="text-sans-serif font-weight-normal">
                                                Questo target non è attivo.<br>
                                                Per gestire le tue preferenze e ricevere comunicazioni di interesse per le imprese attiva l’iscrizione ed 
                                                imposta le modalità di contatto.<br>
                                                <a href="#" onClick="return $('#toggle-stato-id-<?= $targetAttributes->id ?>').click();" title="Attiva questo target">Attiva questo target</a>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                     

                            </div>
                          
                        </div>
                        
                    </div>

                    <?php 
                        if($isHBEmail) {
                            ?>                            
                            <div class="alert alert-danger alert-dismissible fade show mb-0 settings-alert" role="alert">
                                <p>Come modalità di contatto nel tuo profilo è stata utilizzata la mail: 
                                <b><?= implode(", ", $hbEmail); ?></b><br />
                                Questa mail non è più raggiungibile ed è necessario sostituirla per ricevere comunicazioni da Lombardia Informa. </p>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php
                        }
                        if($isTargetActive){
                            if($targetAttributes->validated_phone_flag==0 && $targetAttributes->validated_email_flag==0){ //entrambi non validati?>
                                <div class="alert alert-danger alert-dismissible fade show mb-0 settings-alert" role="alert">
                                    <p>Attenzione nessuna modalità di contatto è stata verificata, completa la validazione per ricevere comunicazioni. </p>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                            <?php }else{
                                if(($targetAttributes->validated_email_flag==0) && (!empty($targetAttributes->email))){ //mail non validata ma tel si ?>
                                    <div class="alert alert-danger alert-dismissible fade show mb-0 settings-alert" role="alert">
                                        <p>Attenzione la modalità di contatto "email" non è stata validata.
                                            Accedi alla tua casella di posta e completa la validazione cliccando sul link. </p>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php }else{ //se contatto tel non valido ma email si 
                                    if(($targetAttributes->validated_phone_flag==0) && (!empty($targetAttributes->phone))){ ?>
                                        <div class="alert alert-danger alert-dismissible fade show mb-0 settings-alert" role="alert">
                                            <p>Attenzione la modalità di contatto "sms" non è stata validata.
                                                Controlla il tuo cellulare e verifica il codice che ti abbiamo inviato. </p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                <?php }
                                 }
                            } 
                        }    
                        ?>
                    


                   
                    <?php


                    $targetCode = $currentTargetCode->codice;
                    $jsId = $targetAttributes->id;
                    $this->registerJs(
                        <<<JS

    $("#toggle-stato-id-{$jsId}").click(function(event) {

        if($(this).prop("checked") == true) {
            $(this).parent().find('.jsSwitchLabel').text('Attivo').delay(1000);
            $.post({
                url: '/preferenceuser/preference/enable-target-ajax',
                type: 'post',
                data: {
                        target_code: "{$currentTargetCode}",
                    },
                success: function (data) {
                    if(data == 'true'){                     
                        location.reload();
                    } else {                                                
                        
                        //$(this).prop('checked', false);

                        $('#messages-attributes-id').html(' \
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                        Errore, impossibile abilitare il target: ' + data + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                            <span aria-hidden="true">&times;</span> \
                        </button> \
                        </div> \
                        ');
                        
                    }
                }
            });
        }

        if($(this).prop("checked") == false) {
            $(this).parent().find('.jsSwitchLabel').text('Disattivo');
            $.post({
                url: '/preferenceuser/preference/disable-target-ajax',
                type: 'post',
                data: {
                        target_code: "{$currentTargetCode}",
                    },
                success: function (data) {
                    if(data == 'true'){                     
                        location.reload();
                    } else {                         

                        //$(this).prop('checked', true);

                        $('#messages-attributes-id').html(' \
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                        Errore, impossibile disabilitare il target: ' + data + ' \
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                            <span aria-hidden="true">&times;</span> \
                        </button> \
                        </div> \
                        ');
                    }
                }
            });
        }
    });


        var allToggle = $(".toggles");
        allToggle.each(function(){
            var input = $(this).find('input');
            if((input).prop("checked") == true){
                $(this).find('.jsSwitchLabel').text('Attivo');
            } else {
                $(this).find('.jsSwitchLabel').text('Disattivo');
            }
        })


JS
, View::POS_READY );
                    ?>

                </div>
                <div id="messages-attributes-id"></div>
                <?php
                if ((!empty($targetAttributes)) && $targetAttributes->hasErrors()) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                        <?php
                        
                        $errors = $targetAttributes->getErrors();
                        if (isset($errors['phone'])){
                            echo array_shift(array_values($errors['phone'])); 
                        }
                        if (isset($errors['email'])){
                            echo array_shift(array_values($errors['email'])); 
                        }
                        ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                    <span aria-hidden="true">&times;</span> 
                </button> 
                </div> 
                <?php
                endif;
                ?>


                <h5 class="text-uppercase pt-4">Le tue preferenze come <?= $labelTitle1 ?></h5>
                <p class="text-muted mb-0">Visualizza le tue tematiche preferite</p>
                <?php if (empty($allUserSelectedTopics)) : ?>

                    <div class="card-wrapper card-preference empty col-lg-4 col-md-6 mt-4">
                        <div class="card rounded bg-card-preference-bg">
                            <div class="card-body ">
                                <div class="categoryicon-top">
                                    <span class="h6">Nessuna preferenza attiva</span>
                                </div>
                                <p class="card-text">Ricorda di impostare le modalità di contatto per poter attivare le tue preferenze e ricevere le comunicazioni</p>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <div id="modal-preference-setting-area-id"></div>

                <?php
                $formId = 'selected-topic-choices-form-id';
                $choiceId = 'selected-topic-id';

                $this->registerJs(
                    <<<JS

$("#{$choiceId}").change(function(event) {

    $.get("/preferenceuser/preference/modal-preference-setting?id=" + $(this).val() + "&form={$formId}" , function( data ) {
        $("#modal-preference-setting-area-id" ).html( data );

        $("a[id='modal-disattiva-preferenze-anchor-id']").click(function(event) {
            event.preventDefault();
            
            $('#modal-preference-setting-id').modal('hide');
            $('#sure-preference-removing-id').modal('show');
            var modalConfirm = function(callback){
                $("#sure-preference-removing-conferma").on("click", function(){
                    callback(true);
                    $('#sure-preference-removing-id').modal('hide');
                });
                
                $("#sure-preference-removing-annulla").on("click", function(){
                    callback(false);
                    $('#sure-preference-removing-id').modal('hide');
                });
            };

            modalConfirm(function(confirm){
                if(confirm){
                    $("#{$formId}").submit();
                }
            });

        });

        $('#modal-preference-setting-id').modal('show');
    });
});

JS
, View::POS_READY );

                $form = ActiveForm::begin([
                    'options' => [
                        'id' => $formId,
                        'data-fid' => 0,
                        'data-field' => '',
                        'data-entity' => '',
                        'enctype' => 'multipart/form-data',
                    ],
                ]);

                echo SingleSelectionListTopicIcon::widget([
                    'choices' => $allUserSelectedTopics,
                    'attribute' => $nameTopicDeselected,
                    'classContainer' => 'col-lg-4 col-md-6',
                    'id' => $choiceId,
                    'viewTheme' => 2,
                    'isActionDisabled' => !$isTargetActive,
                    'baseIconsUrl' => '/img/icone-tematiche/',
                    'additionalView' => '@vendor/preference/userprofile/src/views/preference/_channel_enabled_on_card.php',
                    'isActive' => $isTargetActive,
                ]);              
                
                ?>
                    

                    
                
                
                <input type="hidden" name="target" value="<?= $tagRoot->codice ?>" \>


                




                <?php ActiveForm::end(); ?>






                <div class="row variable-gutters pt-4">
                    <div class="col-lg-7">
                        <h5 class="text-uppercase">Tutte le tematiche indirizzate a: <?= $labelTitle2 ?></h5>
                        <p class="text-muted mb-0">Attiva le tue tematiche preferite e gestisci i canali su cui vuoi rimanere informato</p>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-rounded">
                            <div class="form-group mb-0">
                                <div class="input-group">
                                    <label for="jsInputSearch" class="sr-only">Ricerca tematiche</label>
                                    <input type="search" class="form-control rounded-left small text-muted" id="jsInputSearch" name="js-input-search" placeholder="Cerca tra le tematiche disponibili">
                                    <div class="input-group-append">
                                        <button class="btn btn-xs border border-tertiary d-none" type="reset" id="btnjsInputReset">
                                            <svg class="icon icon-tertiary">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-close"></use>
                                            </svg>
                                            <span class="sr-only">Pulisci filtro di ricerca</span>
                                        </button>
                                        <button class="btn btn-xs border border-tertiary" type="button">
                                            <svg class="icon icon-tertiary mr-1">
                                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-search"></use>
                                            </svg>
                                            <span class="sr-only">Cerca</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $this->registerJs(
                            <<<JS

$('input#jsInputSearch').keyup(function() {
    var allTopicToChoise = $('#single-selection-list-topics-icon-to-choice div[id^="single-selection-list-topics-icon"]');
   if(this.value.length > 1) {
    $('button#btnjsInputReset').removeClass('d-none');
   var actualValue = this.value.toLowerCase();
   allTopicToChoise.each(function(){
        
        var titleTarget = $(this).find('span.h6').text().toLowerCase();
        // console.log(titleTarget);
        var descTarget = $(this).find('p.card-text').text().toLowerCase();
        // console.log(descTarget);
        if( ((titleTarget.indexOf(actualValue)) >= 0) || ((descTarget.indexOf(actualValue)) >= 0) ){
            //console.log(titleTarget.indexOf(actualValue));
            //console.log(descTarget.indexOf(actualValue));
            //console.log('OK');
        } else {
            $(this).parent().addClass('d-none');
        }
   });
   } else {
    allTopicToChoise.parent().removeClass('d-none');
   }
});

$('button#btnjsInputReset').on('click',function(){
    var allTopicToChoise = $('#single-selection-list-topics-icon-to-choice div[id^="single-selection-list-topics-icon"]');
    allTopicToChoise.each(function(){ $(this).parent().removeClass('d-none') });
    $('input#jsInputSearch').val('');
    $(this).toggleClass('d-none');
});

JS
, View::POS_READY );
                        ?>
                    </div>
                </div>



                <?php
                $formId = 'disabled-topic-choices-form-id';
                $choiceId = 'disabled-topic-id';

                $this->registerJs(
                    <<<JS

$("#{$choiceId}").change(function(event) {

    $.get("/preferenceuser/preference/modal-preference-setting?id=" + $(this).val() + "&form={$formId}&addTopic=true" , function( data ) {
        $("#modal-preference-setting-area-id" ).html( data );

        $("a[id='modal-disattiva-preferenze-anchor-id']").click(function(event) {
            event.preventDefault();
            
            $('#modal-preference-setting-id').modal('hide');
            $('#sure-preference-removing-id').modal('show');
            // var modalConfirm = function(callback){
            //     $("#sure-preference-removing-conferma").on("click", function(){
            //         callback(true);
            //         $('#sure-preference-removing-id').modal('hide');
            //     });
                
            //     $("#sure-preference-removing-annulla").on("click", function(){
            //         callback(false);
            //         $('#sure-preference-removing-id').modal('hide');
            //     });
            // };

            // modalConfirm(function(confirm){
            //     if(confirm){
            //         $("#{$formId}").submit();
            //     }
            // });

        });

        $('#modal-preference-setting-id').modal('show');
    });
});

JS
, View::POS_READY );

                $form = ActiveForm::begin([
                    'options' => [
                        'id' => $formId,
                        'data-fid' => 0,
                        'data-field' => '',
                        'data-entity' => '',
                        'enctype' => 'multipart/form-data',
                    ],
                ]);
                ?>

                <?= SingleSelectionListTopicIcon::widget([
                    'choices' => $allTopicsChoices,
                    'attribute' => $nameTopicSelected,
                    'classContainer' => 'col-lg-4 col-md-6',
                    'choicesToRemove' => $allUserSelectedTopics,
                    'id' => $choiceId,
                    'isActionDisabled' => !$isTargetActive,
                    'baseIconsUrl' => '/img/icone-tematiche/',
                    'isActive' => $isTargetActive,
                ]); ?>

                <?php ActiveForm::end(); ?>

            </div>
            <!-- END MAIN -->
        </div>
    </div>
</div>