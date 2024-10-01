<?php

/**
 * @param string $inputId
 * @param Model $model
 */
use yii\helpers\Html;
use open20\design\Module;


$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';

$placeholder = (isset($options['placeholder'])) ? 'placeholder="' . $options['placeholder'] . '"' : false;
$ariaDescribedBy = (isset($options['aria-describedby'])) ? 'aria-describedby="describedBy' . $inputId . '"' : false;

?>
<input type="<?= $type ?>" name="<?= $name ?>" class="form-control" <?=$placeholder?> <?=$ariaDescribedBy?> id="<?= $inputId ?>" value="<?= $value ?>" <?= $required ?>>
<?php if ($ariaDescribedBy) : ?>
<small id="describedBy<?= $inputId ?>" class="form-text text-muted"><?= $options['aria-describedby'] ?></small>
<?php endif ?>
<label for="<?= $inputId ?>"><?= $label ?><?= ($required) ? Html::tag('span','*',['class'=>'required-asterisk']) : ''?></label>
<div class="invalid-feedback"><?= Module::t('amosdesign', 'Per favore inserisci') ?> <?= $label ?>.</div>
