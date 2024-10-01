<?php

/**
 * @param string $inputId
 * @param Model $model
 */

use yii\helpers\Html;

$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';
$checked = (isset($model) && $model->$attribute) || (isset($value) && !empty($value)) ? 'checked': '';

?>

<div class="form-check">

    <?php if (empty($items) || !is_array($items)) { ?>
        <?php echo Html::input('checkbox', $name, $value, $options) ?>
        <label class="form-check-label" for="<?= $inputId ?>"><?= $label ?><?= ($required) ? Html::tag('span', '*', ['class' => 'required-asterisk']) : '' ?></label>
        <?php
    } else {
        foreach ($items as $key => $value) {
            $idcheck = $inputId . substr(uniqid(), -3);
            ?>

            <input class="form-check-input" type="checkbox" id="<?= $idcheck ?>" value="<?= $key ?>" name="<?= $name ?>" <?= $required ?>>
            <label class="form-check-label" for="<?= $idcheck ?>"><?= $value ?></label>

            <?php
        }
    }
    ?>
    <!-- <div class="invalid-feedback">Error message...</div> -->
</div>