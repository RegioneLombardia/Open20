<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */
use open20\design\Module;

?>

<div class="form-group">
  <div class="it-datepicker-wrapper">
    <div class="form-group mb-0">
      <input class="form-control it-date-datepicker it-datepicker-narrow" id="date" type="text" required>
      <label for="date"><?= Module::t('amosdesign', 'Date label') ?></label>
      <div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci la data') ?></div>
    </div>
  </div>
</div>