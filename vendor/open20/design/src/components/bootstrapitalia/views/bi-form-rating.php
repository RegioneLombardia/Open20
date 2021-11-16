<?php

/**
 * @param string $inputId
 * @param Model $model
 * @param int $starsNumber
 * @param string $attribute
 */

use yii\helpers\Html;
$bootstrapItaliaAsset = \open20\design\assets\BootstrapItaliaDesignAsset::register($this);
$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';
$value = empty($value)? 0: $value;
?>

<div class="form-rating">
    <fieldset class="rating <?= ($readonly)? 'rating-read-only': 'rating-label' ?>">
        <legend><span class="sr-only">Valutazione</span> <span><?= $value ?> stelle</span> <span class="sr-only">su <?= $starsNumber ?></span></legend>

        <?php
        for ($i = $starsNumber; $i>=1; $i--):
            $htmlId = $name . '-star' . $i . '-id';
            ?>
            <input type="radio" id="<?= $htmlId ?>" name="<?= $name ?>" value="<?= $i ?>" <?= ($value == $i)? 'checked': '' ?> <?= ($readonly)? 'disabled': '' ?>/>
            <label class = "full" for="<?= $htmlId ?>">
                <svg class="icon icon-sm"><use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-star-full"></use></svg>
                <span class="sr-only">Valuta <?= $i ?> stelle su <?= $starsNumber ?></span>
            </label>
            <?php
        endfor;
        ?>
    </fieldset>

    <!-- <div class="invalid-feedback">Error message...</div> -->
</div>