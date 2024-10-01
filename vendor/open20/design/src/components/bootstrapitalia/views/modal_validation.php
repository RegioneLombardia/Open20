<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

use open20\design\components\bootstrapitalia\CheckBoxListTopicsIcon;
use open20\design\Module;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>





<!-- Modal generico -->
<div class="modal fade modale-validazione modal-custom" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Titolo modale </h3>
        <button aria-label="chiudi la modale" type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-4 text-center text-md-left">
        <div class="row pb-4">
          <div class="col-md-5">
            <img class="img-fluid" src="<?= $bootstrapItaliaAsset->baseUrl ?>/img/email.svg" alt="immagine">
          </div>
            <div class="col-md-7">
              <p class="p-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p>
            </div>
            
        </div>
        <button type="button" class="btn btn-primary w-75 d-flex justify-content-center m-auto"><?= Module::t('amosdesign', 'Ok grazie')?></button>
      </div>
      <div class="modal-footer py-3 border-top d-flex justify-content-between">
        <p class="m-0"><?= Module::t('amosdesign', 'Non hai ricevuto email').'?'?></p>
        <a href="#" class="font-weight-bold m-0" title="link per inviare di nuovo la mail"><?= Module::t('amosdesign', 'Invia di nuovo la mail')?></a>
        
      </div>
    </div>
  </div>
</div>


