<?php

use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


/**
 * @param string $inputId
 * @param Model $model
 */

use yii\helpers\Html;
use yii\web\View;


$thisId = $inputId;

$inputPwdClear = <<< JS
//hide server-side error when strength-meter enable after server error response
$('#{$thisId}').keyup(function(){
  var isFormInError = $(this).parent().hasClass('has-error');
  var isStrengthMeterEnabled = $(this).hasClass('input-password-strength-meter');
  if(isFormInError && isStrengthMeterEnabled){
    var serverSideError=$(this).parent().children('.invalid-feedback.server-side-error');
    for (i=0; i<serverSideError.length; i++){
        serverSideError[i].style.visibility = "hidden";
        serverSideError[i].style.height=0;
        serverSideError[i].style.margin=0;
    }
  }

});

JS;
$this->registerJs($inputPwdClear,View::POS_READY);

$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';

$placeholder = (isset($options['placeholder'])) ? 'placeholder="' . $options['placeholder'] . '"' : false;
$ariaDescribedBy = (isset($options['aria-describedby'])) ? 'aria-describedby="describedBy' . $inputId . '"' : false;
$helperTooltip = (isset($options['helperTooltip'])) ? $options['helperTooltip'] : false;
$strengthMeterClass = (isset($options['enableStrengthMeter'])) ? 'input-password-strength-meter' : false;

$strengthMeterDesc = (isset($options['data-enter-pass'])) ? 'data-enter-pass="' . $options['data-enter-pass'] . '"' : false;
$strengthMeterShort = (isset($options['data-short-pass'])) ? 'data-short-pass="' . $options['data-short-pass'] . '"' : false;
$strengthMeterBad = (isset($options['data-bad-pass'])) ? 'data-bad-pass="' . $options['data-bad-pass'] . '"' : false;
$strengthMeterGood = (isset($options['data-good-pass'])) ? 'data-good-pass="' . $options['data-good-pass'] . '"' : false;
$strengthMeterStrong = (isset($options['data-strong-pass'])) ? 'data-strong-pass="' . $options['data-strong-pass'] . '"' : false;
$strengthMeterMinLen = (isset($options['data-minimum-length'])) ? 'data-minimum-length="' . $options['data-minimum-length'] . '"' : false;

if (isset($options['enableStrengthMeter'])) {
  $confStrengthMeter = $strengthMeterDesc . ' ' . $strengthMeterShort . ' ' . $strengthMeterBad . ' ' . $strengthMeterGood . ' ' . $strengthMeterStrong . ' ' . $strengthMeterMinLen;
}

?>
<?php if ($helperTooltip) : ?>
  <a href="javascript::void(0)" class="bi-form-field-tooltip-helper" data-toggle="tooltip" data-html="true" title="<?= $helperTooltip ?>">
    <span class="icon">
      <svg class="icon icon-xs">
        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-help-circle"></use>
      </svg>
    </span>
    <span class="sr-only">Informazioni per la corretta compilazione</span>
  </a>
<?php endif ?>
<input type="<?= $type ?>" name="<?= $name ?>" class="form-control input-password <?= $strengthMeterClass ?>" <?= $placeholder ?> <?= $ariaDescribedBy ?> <?= $confStrengthMeter ?> id="<?= $inputId ?>" value="<?= $value ?>" <?= $required ?>>
<span class="password-icon" aria-hidden="true">
  <svg class="password-icon-visible icon icon-sm" role="img" aria-label="Immagine per indicare la password visibile">
    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-password-visible"></use>
  </svg>
  <svg class="password-icon-invisible icon icon-sm d-none" role="img" aria-label="Immagine per indicare la password non visibile">
    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-password-invisible"></use>
  </svg>
</span>
<?php if ($ariaDescribedBy) : ?>
  <small id="describedBy<?= $inputId ?>" class="form-text text-muted"><?= $options['aria-describedby'] ?></small>
<?php endif ?>
<label for="<?= $inputId ?>">
  <?= $label ?>
  <?= ($required) ? Html::tag('span', '*', ['class' => 'required-asterisk']) : '' ?>
</label>
<div class="invalid-feedback">Per favore inserisci <?= $label ?>.</div>