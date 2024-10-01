<?php

/**
 * @param string $inputId
 * @param Model $model
 */

$generalListLabel = (isset($options['general-list-label'])) ? $options['general-list-label'] : false;

?>
<?php if ($generalListLabel) : ?>
    <div class="label-style">
        <p class="mb-0">
            <?= $generalListLabel ?>
        </p>
    </div>
<?php endif ?>
<div class="form-group">
    
        <?php if (empty($items) || !is_array($items)) { ?>

            <input class="form-check-input" type="radio" id="<?= $inputId ?>-id" value="<?= $model->$attribute ?>">
            <label class="form-check-label" for="<?= $inputId ?>-id"><?= $attribute ?></label>
            <?php
        } else {

            $i = 0;
            foreach ($items as $key => $value) {
                $idradio = $inputId . substr(uniqid(), -3);
            ?>
                <div class="form-check <?= $classInline ?>" id="<?= $inputId; ?>">
                <input name="<?= $name ?>" type="radio" id="<?= $idradio ?>" value="<?= $key ?>" <?= ($model->$attribute == $key) ? 'checked' : '' ?>>
                <label for="<?= $idradio ?>"><?= $value ?></label>
                </div>
        <?php
                $i++;
            }
        }
        ?>

   
</div>