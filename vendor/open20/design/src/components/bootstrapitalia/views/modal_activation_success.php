<?php
use open20\design\components\bootstrapitalia\CheckBoxListTopicsIcon;
use open20\design\Module;
?>


<div class="modal fade modale-validazione modal-custom" id="preferenceActivationSuccessModal" tabindex="-1" role="dialog" aria-labelledby="preferenceActivationSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h3 class="modal-title" id="preferenceActivationSuccessModalLabel"><?= Module::t('amosdesign', 'Preferenza attivata con successo')?></h3>
        <button aria-label="chiudi la modale" type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-4 text-center text-md-left">
        <div class="row pb-4">
          <div class="col-md-5">
            <img class="img-fluid" src="/img/success.svg" alt="icona spunta vede di successo">
          </div>
            <div class="col-md-7">
              <p class="p-2"><?= Module::t('amosdesign', 'La tematica è stata aggiunta alle tue preferenze, riceverai comunicazioni sui canali da te indicati')?></p>
            </div>
        </div>
        <!-- TODO cosa far fare al bottone quando si clicca ok -->
        <button type="button" class="btn btn-primary w-75 d-flex justify-content-center m-auto"><?= Module::t('amosdesign', 'Ok grazie')?></button> 
      </div>
    </div>
  </div>
</div>