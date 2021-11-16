<?php

use open20\design\components\bootstrapitalia\ActiveForm;
use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\web\View;

/**
 * @var \preference\userprofile\models\PreferenceUserTargetAttribute $targetAttributes
 * @var View $this
 */

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$idTagHtml = isset($htmlId) ? $htmlId : '';
$currentTargetCode = $currentTargetTag->codice;

$this->registerJs(
  <<<JS
  $("#modal-target-mail-annulla{$idTagHtml}").click(function(event) {
    $('#modal-target-mail-id{$idTagHtml}').modal('hide');
 });

JS
,
  View::POS_READY
);


?>
<div class="d-flex my-3 align-items-baseline">
  <div class="form-group form-rounded mr-2 mb-0 w-75 pl-4">
    <input class="form-control-plaintext form-control" type="email" id="input-text-read-only-<?= $idTagHtml ?>" placeholder="<?= $targetAttributes->email ?>" disabled>

    <label for="input-text-read-only-2" class="w-auto d-flex align-items-center label-icon">
      <?php
      if (!empty($targetAttributes->email)) :
      ?>
        <div id="validate-mail-icon-id">
          <?= ($targetAttributes->validated_email_flag) ?
            '<span class="mdi mdi-check-circle design-theme-color-success md-24" data-toggle="tooltip" title="Email validata con successo"><span class="sr-only">Validato</span></span>' :
            '<span class="mdi mdi-alert-circle design-theme-color-danger md-24" data-toggle="tooltip" title="Email non validata"><span class="sr-only">Non validato</span></span>'; ?>
        </div>
      <?php
      else :
      ?>
        <div id="validate-mail-icon-id">
          <span class="mdi mdi-email md-24"><span class="sr-only"></span></span>
        </div>
      <?php
      endif
      ?>
      <div class="ml-2 mb-2">Email</div>

    </label>

  </div>

  <a href="#" data-toggle="modal" data-target="#modal-target-mail-id<?= $idTagHtml ?>" class="tertiary-color text-decoration-none d-flex align-items-center">
    <svg class="icon icon-xs icon-tertiary mr-1">
      <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-pencil"></use>
    </svg><small class="text-decoration-underline"><u>Modifica</u></small>
  </a>
</div>
<div class="modal fade modale-conferma modal-border-radius" id="modal-target-mail-id<?= $idTagHtml ?>" tabindex="-1" role="dialog" aria-labelledby="confermaMailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h3 class="modal-title" id="confermaMailModalLabel">Modifica email di contatto</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>



      <?php
      // FORM MAIL
      $form = ActiveForm::begin([
        'options' => [
          'id' => 'email-target-form-id' . $idTagHtml,
          'data-fid' => 0,
          'data-field' => '',
          'data-entity' => '',
          'class' => 'form-rounded',
          'enctype' => 'multipart/form-data',
        ],
      ]);
      ?>

      <div class="modal-body pb-4">
        <div class="row pb-4 text-center text-md-left align-items-center">
          <div class="col-md-4">
            <img alt="icona validazione" src="/img/validazione-tel.svg">
          </div>
          <div class="col-md-8">
            <p class="p-2">La modifica comporta l'invio di una mail per la verifica</p>
          </div>

        </div>
        <div class="pt-2">

          <input type="hidden" name="target_code_to_modify" value="<?= $currentTargetTag->codice ?>" />
          <?php
          echo $form->field($targetAttributes, 'email')->textInput([
            'id' => 'mail-target-attribute-id' . $idTagHtml
          ]);
          ?>

        </div>
        <div class="input-button-container d-flex flex-wrap flex-sm-nowrap justify-content-center pb-4">
          <button type="button" class="btn btn-outline-secondary w-100 w-sm-50 m-2" id="modal-target-mail-annulla<?= $idTagHtml ?>">Annulla</button>
          <button type="submit" class="btn btn-primary w-100 w-sm-50 m-2" id="modal-target-mail-conferma<?= $idTagHtml ?>">Salva</button>
        </div>
      </div>
      <?php ActiveForm::end(); ?>

        <?php
        $email = $targetAttributes->getOldAttribute('email');
        $validatedEmail = $targetAttributes->getOldAttribute('validated_email_flag');
        if (!empty($email) && ($validatedEmail != 1)):
            ?>
            <div class="modal-footer justify-content-between lightgrey-bg-c1 border-top p-4">
                <p class="text-muted mb-0"><?= $email ?></p>
                <?php
                echo \open20\amos\core\helpers\Html::a('<strong>Invia di nuovo email</strong>', '#', ['id' => 'send-email-validation-token-id' . $idTagHtml])
                ?>
            </div>
            <?php
            $this->registerJs(
                    <<<JS
$("#send-email-validation-token-id{$idTagHtml}").click(function(event) {
      event.preventDefault();
      $.post({
        url: '/preferenceuser/preference/send-validation-token-email-ajax',
        type: 'post',
        data: {
                target_code: '{$currentTargetCode}',
              },
        success: function (data) {
            if(data == 'true'){                           
                $('#modal-target-mail-id{$idTagHtml}').modal('hide');
                $('#messages-attributes-id').html(' \
                  <div class="alert alert-success alert-dismissible fade show" role="alert"> \
                  Email inviata a {$email} correttamente \
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"> \
                      <span aria-hidden="true">&times;</span> \
                  </button> \
                  </div> \
                ');
            }
            if(data == 'false'){                           
                $('#modal-target-mail-id{$idTagHtml}').modal('hide');
                $('#messages-attributes-id').html(' \
                  <div class="alert alert-danger alert-dismissible fade show" role="alert"> \
                  Errore nell\'invio del messagio email \
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
            );
        endif;
        ?>
    </div>
  </div>
</div>