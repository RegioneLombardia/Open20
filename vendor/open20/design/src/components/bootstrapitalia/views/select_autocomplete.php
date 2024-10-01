<?php
/**
 * @param string $inputId
 * @param Model $model
 */
use open20\design\Module;
?>


<div class="form-group">
    <div class="bootstrap-select-wrapper">
        <input type="search" class="autocomplete" placeholder="<?= $placeholder ?>"
               id="<?= $inputId ?>"
               name="<?= $name ?>"
               data-autocomplete='<?= $dataautocomplete ?>'>
        <label for="autocomplete-regioni" class="sr-only"><?= Module::t('amosdesign', 'Cerca nel sito') . ' '?></label>
    </div>
</div>