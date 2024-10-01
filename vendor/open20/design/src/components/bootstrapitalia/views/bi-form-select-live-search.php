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
      <div class="bootstrap-select-wrapper">
        <label><?= Module::t('amosdesign', 'Comune')?></label>
        <div class="invalid-feedback"><?= Module::t('amosdesign', 'Non validato')?></div>
        <select title=<?= Module::t('amosdesign', 'Scegli una opzione')?> data-live-search="true" data-live-search-placeholder="Cerca opzioni">
          <option value="1">Milano</option>
          <option value="2">Mantova</option>
          <option value="3">Sermide</option>
          <option value="4">Ferrara</option>
          <option value="5">Roma</option>
        </select>
      </div>
    </div>