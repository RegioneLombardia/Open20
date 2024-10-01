<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;


$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

/**
 * @param string $inputId
 * @param Model $model
 */

use yii\helpers\Html;


$required = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';

$placeholder = (isset($options['placeholder'])) ? 'placeholder="' . $options['placeholder'] . '"' : false;
$ariaDescribedBy = (isset($options['ariaDescribedBy'])) ? 'aria-describedby="describedBy' . $inputId . '"' : false;
$infoTooltip = (isset($options['infoTooltip'])) ? $options['infoTooltip'] . '"' : false;
$readOnlyText = ($readonly == true) ? 'readonly' : '';
?>

<input type="<?= $type ?>" name="<?= $name ?>" class="form-control" <?= $placeholder ?> <?= $ariaDescribedBy ?> id="<?= $inputId ?>" value="<?= $value ?>" <?= $required ?> <?= $readOnlyText ?> >
<?php if ($ariaDescribedBy) : ?>
    <small id="describedBy<?= $inputId ?>" class="form-text text-muted">
        <?= $options['aria-describedby'] ?>
        <?php if ($infoTooltip) : ?>
            <a href="javascript::void(0)" class="bi-form-field-tooltip-info" data-toggle="tooltip" data-html="true" title="<?= $infoTooltip ?>">
                <span class="icon">
                    <svg class="icon icon-xs">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-info-circle"></use>
                    </svg>
                </span>
                <span class="sr-only"><?=Module::t('amosdesign','Informazioni per la corretta compilazione')?></span>
            </a>
        <?php endif ?>
    </small>
<?php endif ?>
<label for="<?= $inputId ?>"><?= $label ?><?= ($required) ? Html::tag('span', '*', ['class' => 'required-asterisk']) : '' ?></label>
<div class="invalid-feedback"><?=Module::t('amosdesign','Per favore inserisci') . ' ' . $label . '.' ?></div>
