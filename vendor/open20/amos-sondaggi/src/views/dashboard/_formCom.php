<?php

use open20\amos\sondaggi\AmosSondaggi;
//use open20\amos\sondaggi\assets\WizardEventAsset;
use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
use open20\amos\core\icons\AmosIcons;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\core\forms\ActiveForm;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use yii\helpers\Html;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\sondaggi\models\SondaggiComunication;
use open20\amos\core\forms\TextEditorWidget;

//$wizardAsset = WizardEventAsset::register($this);
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

/** @var \open20\amos\events\models\search\EventTypeSearch $eventTypeSearchModel */
$this->registerCss("
#errore-alert-common {display:none;}
");

$js   = <<<JS
    $('#switch-settings').on('switchChange.bootstrapSwitch', function(){
        if($(this).is(':checked')){
            $('#settings-div').show();
        }
        else {
           $('#settings-div').hide();
        }
    });

    function realoadSearch(container, plus){
           $.pjax.reload({
                container: container,
                timeout: 20000,
               replace: false,
                url: '/sondaggi/dashboard-invitations/render-search-ajax',
                data: {
                    'data': $('#form-search').serialize(),
                    'plus': plus,
                    },
                method: 'post'
            }).done(function() {
                $('#filter-value').change();
            });
    }

    $(document).on('click','#btn-add-rule',function(e){
        e.preventDefault();
        realoadSearch('#search-container', 1);
    });

    $(document).on('click', '#btn-remove-rule',function(e){
        e.preventDefault();
        var value = $(this).attr('data');
        $('#row-search-'+value).remove();
        realoadSearch('#search-container', 0);
    });

    // function search
     $('#btn-search-users').click(function(e){
         e.preventDefault();
         $('.loading').show();
           $.ajax({
               url: '/sondaggi/dashboard-invitations/search-invited',
               type: 'post',
               data: {
                    'data': $('#form-search').serialize()
               },
               success: function (data) {
                   $('.loading').hide();
                  $('#result-search-container').html(data);
               }
           });
     });

     $('input[name="SondaggiInvitations[type]"]').click(function(){
          var value = $("input[name='SondaggiInvitations[type]']:checked").val();
          if(value == 1){
              $('#search-tags').show();
          }
          else {
              $('#search-tags').hide();
          }
     });

     $('input[name="SondaggiInvitations[filter_type]"]').click(function(){
           var value = $("input[name='SondaggiInvitations[filter_type]']:checked").val();
           if(value == 0){
               $('#filter-groups').show();
               $('#filter-tags').hide();
           }
           else {
               $('#filter-groups').hide();
               $('#filter-tags').show();
           }
      });

     $(document).on('click', '#form-result button[type="submit"]', function(){
         $("#form-result").yiiActiveForm("validate");
     });

JS;
$this->registerJs($js);
//            pr($internalList->getErrors());
//            pr($model->getErrors());
//$modelSearch->event_id = $model->id
?>

<!-- SELECT 2 PLACEHOLDER FIX -->
<style>
    .select2-container .select2-search--inline {
        width: 100%;
    }
    .select2-container .select2-search--inline > input {
        min-width: 100% !important;
    }
</style>

<div class="dimmable position-fixed loader loading" style="display:none">
    <div class="dimmer d-flex align-items-center" id="dimmer1">
        <div class="dimmer-inner">
            <div class="dimmer-icon">
                <div class="progress-spinner progress-spinner-active loading m-auto">
                    <span class="sr-only">Caricamento...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="utenti">
    <?php
    $form = ActiveForm::begin([
            'options' => ['id' => 'form-search']
    ]);
    ?>
    <div>

        <div class="p-t-20 p-b-20 d-flex title-substeps affix-top">
            <h5 class="font-weight-bold ">
            <!-- <span class="m-r-10">< ?= Amosicons::show('address-card', [], 'dash') ?></span> -->
                <?= AmosSondaggi::t('AmosSondaggi', "Seleziona il filtro") ?>
            </h5>
        </div>


        <div class="row variable-gutters">
            <div class="col-md-12">
                <?=
                $form->field($model, 'type')->radioList([
                    SondaggiComunication::TUTTI_GLI_ENTI_INVITO_SPEDITO => AmosSondaggi::t('amossondaggi',
                        '#all_organizations_poll_sent'),
                    SondaggiComunication::TUTTI_GLI_ENTI_COMPILATO => AmosSondaggi::t('amossondaggi',
                        '#organizations_poll_compiled'),
                    SondaggiComunication::TUTTI_GLI_ENTI_INVITATI_NON_COMPILATO => AmosSondaggi::t('amossondaggi',
                        '#organizations_poll_not_compiled')
                ])->label(false);
                ?>

            </div>
        </div>
    </div>

    <div id="result-search-container">

    </div>

    <div>
        <?=
        $form->field($model, 'name')->textInput(['placeholder' => AmosSondaggi::t('AmosSondaggi',
                'Assegna un titolo alla tua ricerca')])->label(AmosSondaggi::t('AmosSondaggi', 'Titolo della ricerca'))
        ?>
    </div>
    <hr/>
    <div>
        <?=
        $form->field($model, 'subject')->textInput(['placeholder' => AmosSondaggi::t('AmosSondaggi',
                'Soggetto della mail')])->label(AmosSondaggi::t('AmosSondaggi', 'Soggetto della mail'))
        ?>
    </div>
    <div>
        <?=
        $form->field($model, 'message')->widget(TextEditorWidget::className(),
            [
            'clientOptions' => [
                'placeholder' => AmosSondaggi::t('amossondaggi', '#insert_text'),
                'lang' => substr(Yii::$app->language, 0, 2)
            ]
        ])->label(AmosSondaggi::t('amossondaggi', 'Testo della mail'))
        ?>
    </div>
    <hr/>

    <div>
        <?=
        $form->field($model, 'email_test')->textInput(['placeholder' => AmosSondaggi::t('AmosSondaggi', 'Email di test')])->label(AmosSondaggi::t('amossondaggi',
                'Email di test'))
        ?>
    </div>

    <div id="form-actions" class="bk-btnFormContainer">
        <?=
        CloseSaveButtonWidget::widget([
            'model' => $model,
            'buttonNewSaveLabel' => $model->isNewRecord ? AmosSondaggi::t('amossondaggi', 'Inserisci') : AmosSondaggi::t('amossondaggi',
                    'Salva'),
            'closeButtonLabel' => AmosSondaggi::t('amossondaggi', 'Chiudi'),
        ]);
        ?>
    </div>

    <?= $form->field($model, 'sondaggi_id')->hiddenInput()->label(false) ?>
    <?php ActiveForm::end(); ?>

</div>
