<?php

use open20\amos\core\helpers\Html;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\Input;
use open20\design\components\bootstrapitalia\ActiveForm;

use preference\userprofile\utility\UserInterestTagUtility;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\View;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$idTagHtml = isset($htmlId) ? $htmlId : '';

$this->registerJs(
  <<<JS
  $("#modal-target-phone-annulla{$idTagHtml}").click(function(event) {
    $('#modal-target-phone-id{$idTagHtml}').modal('hide');
    });

JS
,
  View::POS_READY
);


?>
<div class="d-flex my-3 align-items-baseline">
  <div class="form-group form-rounded mr-2 mb-0 w-75 pl-4">
    <input class="form-control-plaintext form-control" type="text" id="input-text-read-only-phone-<?= $idTagHtml ?>" placeholder="<?= $targetAttributes->phone ?>" disabled>
    <label for="input-text-read-only-phone-<?= $idTagHtml ?>" class="w-auto d-flex align-items-center label-icon">
      
    <?php
      if (!empty($targetAttributes->phone)) :
      ?>
        <div id="validate-phone-icon-id<?= $idTagHtml ?>">
        <?= ($targetAttributes->validated_phone_flag) ? 
         '<span class="mdi mdi-check-circle design-theme-color-success md-24" data-toggle="tooltip" title="Cellulare validato con successo"><span class="sr-only">Validato</span></span>' :
         '<span class="mdi mdi-alert-circle design-theme-color-danger md-24" data-toggle="tooltip" title="Cellulare non validato"><span class="sr-only">Non validato</span></span>'; ?>
      </div>
      <?php
      else :
      ?>
        <div id="validate-mail-icon-id">
          <span class="mdi mdi-cellphone-iphone md-24"><span class="sr-only"></span></span>
        </div>
      <?php
      endif;
      ?>
      <div class="ml-2 mb-2">Cellulare</div>
    </label>
  </div>
  <a href="#" data-toggle="modal" data-target="#modal-target-phone-id<?= $idTagHtml ?>" class="tertiary-color text-decoration-none d-flex align-items-center">
    <svg class="icon icon-xs icon-tertiary mr-1">
      <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-pencil"></use>
    </svg><small class="text-decoration-underline"><u>Modifica</u></small>
  </a>
</div>




<?php
$currentTargetCode = $currentTargetTag->codice;
$phoneNumber = $targetAttributes->phone;

if (!$targetAttributes->validated_phone_flag) :
?>
  <?php
  if (!empty($targetAttributes->phone)) :
  ?>
    <button href="#" class="btn btn-xs btn-primary float-right mt-3" data-toggle="modal" data-target="#modal-target-validate-phone-id<?= $idTagHtml ?>" id="button-validate-phone-id<?= $idTagHtml ?>">
      Valida telefono
    </button>
  <?php
  endif
  ?>

  <?php
  $this->registerJs(
    <<<JS

    $("#modal-target-phone-action-validate-id{$idTagHtml}").click(function(event) {
      $.post({
        url: '/preferenceuser/preference/validate-phone-ajax',
        type: 'post',
        data: {
                token:  $("#phone-token-id{$idTagHtml}").val(),
              },
        success: function (data) {
            if(data == 'true'){                           
                $('#modal-target-validate-phone-id{$idTagHtml}').modal('hide');
                $('#messages-attributes-id').html(' \
                  <div class="alert alert-success alert-dismissible fade show" role="alert"> \
                    Numero di telefono validato correttamente \
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                      <span aria-hidden="true">&times;</span> \
                  </button> \
                  </div> \
                ');
                $('#button-validate-phone-id{$idTagHtml}').hide();
                $('#validate-phone-icon-id{$idTagHtml}').html('<span class="mdi mdi-check-circle primary-color md-24"><span class="sr-only">Validato</span></span>');
            }
            if(data == 'false'){                           
                $('#modal-target-validate-phone-id{$idTagHtml}').modal('hide');
                $('#messages-attributes-id').html(' \
                  <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                  Il codice inserito per la validazione non è corretto \
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                      <span aria-hidden="true">&times;</span> \
                  </button> \
                  </div> \
                ');
            }
        }
        
      });
    });


    $("#send-phone-validation-token-id{$idTagHtml}").click(function(event) {
      event.preventDefault();
      $.post({
        url: '/preferenceuser/preference/send-validation-token-phone-ajax',
        type: 'post',
        data: {
                target_code: '{$currentTargetCode}',
              },
        success: function (data) {
            if(data == 'true'){                           
                $('#modal-target-validate-phone-id{$idTagHtml}').modal('hide');
                $('#messages-attributes-id').html(' \
                  <div class="alert alert-success alert-dismissible fade show" role="alert"> \
                  SMS inviato al numero di telefono {$phoneNumber} correttamente \
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                      <span aria-hidden="true">&times;</span> \
                  </button> \
                  </div> \
                ');
            }
            if(data == 'false'){                           
                $('#modal-target-validate-phone-id{$idTagHtml}').modal('hide');
                $('#messages-attributes-id').html(' \
                  <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                  Errore nell\'invio del messagio SMS \
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                      <span aria-hidden="true">&times;</span> \
                  </button> \
                  </div> \
                ');
            }
        }
        
      });
    });

JS
,
    View::POS_READY
  );
  ?>

  <div class="modal fade modale-conferma modal-border-radius" id="modal-target-validate-phone-id<?= $idTagHtml ?>" tabindex="-1" role="dialog" aria-labelledby="confermaTelefonoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content ">
        <div class="modal-header">
          <h3 class="modal-title" id="confermaTelefonoModalLabel">Modifica cellulare di contatto</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body pb-4">
          <div class="row pb-4 text-center text-md-left">
            <div class="col-md-4">
              <img alt="icona validazione telefono" class="mr-3" src="/img/validazione-tel.svg">
            </div>
            <div class="col-md-8">
              <p class="p-2">Verifica il numero di cellulare inserendo qui il codice di 6 cifre che hai ricevuto via sms</p>
            </div>
          </div>
          <div class="row pb-4 form-rounded">
            <div class="col-md-8">
              <div class="form-group">
                <?php
                echo Input::widget([
                  'name' => 'phone-token',
                  'label' => 'Inserisci codice',
                  'options' => [
                    'id' => 'phone-token-id' . $idTagHtml,
                  ]
                ]);
                ?>
              </div>
            </div>
            <div class="col-md-4">
              <button type="button" class="btn btn-sm btn-primary mt-1" id="modal-target-phone-action-validate-id<?= $idTagHtml ?>">Valida</button>
            </div>
          </div>





        </div>
        <div class="modal-footer justify-content-between lightgrey-bg-c1 border-top p-4">
          <p class="text-muted mb-0">Non hai ricevuto alcun SMS?</p>
          <?php
          echo Html::a('<strong>Invia di nuovo SMS</strong>', '#', ['id' => 'send-phone-validation-token-id' . $idTagHtml])
          ?>
        </div>
      </div>

    </div>
  </div>


<?php
endif
?>

<div class="modal fade modale-conferma modal-border-radius" id="modal-target-phone-id<?= $idTagHtml ?>" tabindex="-1" role="dialog" aria-labelledby="modificaTelefonoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h3 class="modal-title" id="modificaTelefonoModalLabel">Modifica telefono di contatto</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php
      // FORM phone
      $form = ActiveForm::begin([
        'options' => [
          'id' => 'phone-target-form-id' . $idTagHtml,
          'data-fid' => 0,
          'data-field' => '',
          'data-entity' => '',
          'enctype' => 'multipart/form-data',
          'class' => 'form-rounded',
        ],
      ]);
      ?>

      <div class="modal-body pb-4">
        <div class="row pb-4 text-center text-md-left align-items-center">
          <div class="col-md-4">
            <img alt="icona telefono" src="/img/validazione-tel.svg">
          </div>
          <div class="col-md-8">
            <p class="p-2">La modifica comporta l'invio di un SMS per la verifica del numero di cellulare</p>
          </div>

        </div>

        <div class="pt-2">
          <!-- <div class="p-2 w-50"> -->
          <input type="hidden" name="target_code_to_modify" value="<?= $currentTargetTag->codice ?>" />
          <?php
          echo $form->field($targetAttributes, 'phone')->textInput([
            'id' => 'phone-target-attribute-id' . $idTagHtml
          ]);
          ?>
          <!-- </div> -->
        </div>


        <div class="input-button-container d-flex flex-wrap flex-sm-nowrap justify-content-center pb-4">
          <button type="button" class="btn btn-outline-secondary w-100 w-sm-50 m-2" id="modal-target-phone-annulla<?= $idTagHtml ?>">Annulla</button>
          <button type="submit" class="btn btn-primary w-100 w-sm-50 m-2" id="modal-target-phone-conferma<?= $idTagHtml ?>">Salva</button>
        </div>
      </div>
    </div>
    <?php ActiveForm::end(); ?>

  </div>
</div>