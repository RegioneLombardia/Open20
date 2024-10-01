<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

BootstrapItaliaDesignAsset::register($this);


?>
<form class="needs-validation form-rounded" novalidate>
  <div class="form-row">
    <div class="form-group col-md-4">
      <input type="text" class="form-control" id="validationCustom01" required>
      <label for="validationCustom01"><?= Module::t('amosdesign', 'Nome') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci il nome') . '.'?></div>
    </div>
    <div class="form-group col-md-4">
      <input type="text" class="form-control" id="validationCustom02" required>
      <label for="validationCustom02"><?= Module::t('amosdesign', 'Cognome') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci il cognome') . '.'?></div>
    </div>
    <div class="form-group col-md-4">
      <input type="text" class="form-control" id="validationCustomUsername" required>
      <label for="validationCustomUsername"><?= Module::t('amosdesign', 'Cognome') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci un username') . '.'?></div>
    </div>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <input type="text" class="form-control" id="validationCustom03" required>
      <label for="validationCustom03"><?= Module::t('amosdesign', 'Città') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci un nome di città valido') . '.'?></div>
    </div>
    <div class="form-group col-md-3">
      <input type="text" class="form-control" id="validationCustom04" required>
      <label for="validationCustom04"><?= Module::t('amosdesign', 'Provincia') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci un nome di provincia valida') . '.'?></div>
    </div>
    <div class="form-group col-md-3">
      <input type="text" class="form-control" id="validationCustom05" required>
      <label for="validationCustom05"><?= Module::t('amosdesign', 'CAP') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci un CAP valido') . '.'?></div>
    </div>

  </div>
  <div class="form-row">
    <div class="form-group col-md-4">
      <div class="it-datepicker-wrapper">
        <div class="form-group mb-0">
          <input class="form-control it-date-datepicker it-datepicker-narrow" id="date1" type="text" required>
          <label for="date1"><?= Module::t('amosdesign', 'Date label')?></label>
          <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci la data') . '.'?></div>
        </div>
      </div>
    </div>
    <div class="form-group col-md-4">
      <div class="bootstrap-select-wrapper">
        <label><?= Module::t('amosdesign', 'Comune')?></label>
        <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci il comune') . '.'?></div>
        <select title="Scegli una opzione" data-live-search="true" data-live-search-placeholder="Cerca opzioni">
          <option value="1"><?= Module::t('amosdesign', 'Milano')?></option>
          <option value="2"><?= Module::t('amosdesign', 'Mantova')?></option>
          <option value="3"><?= Module::t('amosdesign', 'Sermide')?></option>
          <option value="4"><?= Module::t('amosdesign', 'Ferrara')?></option>
          <option value="5"><?= Module::t('amosdesign', 'Roma')?></option>
        </select>
      </div>
    </div>
    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
          <input name="gruppo2" type="radio" id="radio4" checked>
          <label for="radio4">Opzione 1</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="gruppo2" type="radio" id="radio5">
          <label for="radio5">Opzione 2</label>
        </div>
      
    </div>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
    <label class="form-check-label" for="invalidCheck2"><?= Module::t('amosdesign', 'Accetto i termini e le condizioni')?></label>
    <div class="invalid-feedback"><?= Module::t('amosdesign', 'Devi accettare i termini e le condizioni prima di inviare il modulo')?></div>
  </div>
  <button class="btn btn-primary mt-3" type="submit"><?= Module::t('amosdesign', 'Invia')?></button>
</form>

<script>
  (function() {
    'use strict';
    window.addEventListener('submit', function() {
      var forms = document.getElementsByClassName('needs-validation');
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
        }, false);
        form.classList.add('was-validated');
      });
    }, false);
  })();
</script>