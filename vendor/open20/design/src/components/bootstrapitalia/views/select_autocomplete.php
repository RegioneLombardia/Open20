<?php
/**
 * @param string $inputId
 * @param Model $model
 */
?>


<div class="form-group">
    <div class="bootstrap-select-wrapper">
        <input type="search" class="autocomplete" placeholder="<?= $placeholder ?>"
               id="<?= $inputId ?>"
               name="<?= $name ?>"
               data-autocomplete='<?= $dataautocomplete ?>'>
        <label for="autocomplete-regioni" class="sr-only">Cerca nel sito</label>
    </div>
</div>