<?php

use open20\amos\core\helpers\Html;
use open20\design\components\bootstrapitalia\ActiveForm;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>
<div class="uk-section- uk-visible@xl header-banner">
  <div style="background-image: url('/attachments/file/view?hash=a2b0b440e50048acf40f54006cb2d4f0&canCache=1');" class="uk-background-norepeat uk-background-cover uk-background-center-center uk-section">

    <div class="uk-container">
      <div class="uk-container">
        <div class="container">
          <div class="uk-width-1-1@m">
            <h1 class="py-5">Assistenza</h1>
          </div>
        </div>
      </div>
    </div>


  </div>
</div>
<div class="container pb-5">
  <div class="row variable-gutters">
    <div class="col-12 mt-5">
      <div class="pr-lg-5">


        <div class="h5">Assistenza tecnica</div>
        <p>Se vuoi saperne di più sulle modalità di accesso ai servizi regionali con i sistemi di identità digitale (ai sensi del Titolo III del DL 16 luglio 2020 n. 76 - DL Semplificazioni), ovvero SPID,
Carta Nazionale dei Servizi (CNS), Carta di Identità Elettronica (CIE) ed eIDAS, consulta il <a href="https://www.regione.lombardia.it/wps/portal/istituzionale/HP/DettaglioRedazionale/servizi-e-informazioni/cittadini/diritti-e-tutele/identita-digitale-accesso-servizi-online" target="_blank" title="Vai alla pagina informazioni">Portale di Regione Lombardia</a>
        </p>

        <?php
        if (!is_null($ok)) :
          if ($ok) :
        ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Gentile utente, la tua segnalazione è stata inviata. Ti risponderemo quanto prima
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
          else :
          ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              Errore nell'invio della comunicazione...
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        <?php
          endif;
        endif;
        ?>

        <?php
        // echo $ok;

        $form = ActiveForm::begin([
          'enableClientScript' => true,
          'options' => [
            'id' => 'personal-data-form',
            'class' => 'needs-validation form-rounded',
            'enctype' => 'multipart/form-data',
          ],
        ]);
        ?>
        <div class="row variable-gutters pt-5">
          <div class="col-md-6">
            <?= $form->field($model, 'nome')->textInput(
              [
                'placeholder' => 'inserisci il nome'
              ]
            ) ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($model, 'cognome')->textInput(
              [
                'placeholder' => 'inserisci il cognome'
              ]
            ) ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(
              [
                'placeholder' => 'inserisci il tuo indirizzo email'
              ]
            ) ?>
          </div>
          <div class="col-md-6">
            <?= $form->field($model, 'confermaEmail')->textInput(
              [
                'placeholder' => 'conferma il tuo indirizzo email'
              ]
            ) ?>
          </div>
          
          <!-- <div class="col-12">
            < ?= $form->field($model, 'messaggio')->textInput() ?>
          </div> -->
          <div class="col-12">
            <?= $form->field($model, 'messaggio')->textarea(
              [
                'placeholder' => 'Scrivi qui il tuo messaggio',
                'rows' => 10
              ]
            ) ?>
          </div>
          <div class="col-12">
            <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className())->label('') ?>
          </div>
          <div class="col-12">
            <?= $form->field($model, 'privacy')->checkBox(
              [
                'label' => 'Ho preso visione dell\'' . Html::a('informativa privacy',null,['href'=>\Yii::$app->params['linkConfigurations']['privacyPolicyLinkCommon'],'title'=>'Leggi l\'informativa privacy','target'=>'_blank'])
              ]
            ) ?>
          </div>          
          
         
        </div>
        <div>
          <?php
          echo Html::submitButton(
            'Invia',
            ['class' => 'btn btn-primary px-5', 'name' => 'submit-action', 'value' => 'forward']
          );
          ?>
        </div>


        <?php ActiveForm::end(); ?>

      </div>
    </div>
    

  </div>
</div>