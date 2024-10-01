<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use open20\design\Module;

\open20\design\assets\SelectDesignAsset::register($this);

$required    = (isset($model) && $model->isAttributeRequired($attribute)) ? 'required' : '';
?>

<?php
$enableGroup = false;
$tmp         = reset($items);
if ($tmp) {
    if (is_array($tmp)) {
        $enableGroup = true;
    }
}
?>

<div class="position-absolute z-index-1">
    <div class="progress-spinner progress-spinner-double size-sm progress-spinner-active z-index-1 position-relative"
         id="spinner-<?= $inputId ?>" style="display: none;">
        <div class="progress-spinner-inner"></div>
        <div class="progress-spinner-inner"></div>
        <span class="sr-only"><?= Module::t('amosdesign', 'Caricamento').'...' ?></span>
    </div>
</div>

<?php
$attrMultiple = '';
if ($multiple) {
    $attrMultiple = 'multiple="true"';
}
?>

<div class="bootstrap-select-wrapper" id="<?= $inputId ?>-wrapper">
    <label for="<?=$inputId ?>"><?= $label ?><?= ($required) ? Html::tag('span', '*', ['class' => 'required-asterisk']) : '' ?></label>
    <select <?= $attrMultiple ?> title="<?= $placeholder ?>" data-live-search="true" class="<?=
    $relatedId ? 'rellable-drop' : '';
    ?>" data-releted="<?= $relatedId; ?>" data-valued="<?= $value; ?>" data-action="<?= $dataAction; ?>" data-live-search-placeholder="Cerca" name="<?= $name ?>" id="<?= $inputId ?>">
    <!--            <option value="" title="Scegli una opzione" data-content="Annulla <span class='reset-label'></span>"></option>-->

        <?php if ($enableGroup) { ?>
            <?php foreach ($items as $labelGroup => $itemsValue) { ?>
                <optgroup label="<?= $labelGroup ?>">
                    <?php foreach ($itemsValue as $key => $optionLabel) {
                        ?>
                        <?php
                        $isSelected = (is_array($value)) ? in_array($key, $value) : ($value == $key);
                        ?>
                        <option value="<?= $key ?>" <?= $isSelected ? 'selected' : '' ?>><?= $optionLabel ?></option>
                    <?php } ?>
                </optgroup>
            <?php } ?>
        <?php } else { ?>
            <?php foreach ($items as $key => $optionLabel) { ?>
                <?php $isSelected = (is_array($value)) ? in_array($key, $value) : ($value == $key); ?>
                <option value="<?= $key ?>" <?= $isSelected ? 'selected' : '' ?>><?= $optionLabel ?></option>
            <?php } ?>
        <?php } ?>
    </select>
</div>

<?php
if (isset($dataAction)) {
    $csrParam = Yii::$app->request->csrfParam;
    $csrValue = Yii::$app->request->getCsrfToken();

    $this->registerJsVar('csrfParam', $csrParam);
    $this->registerJsVar('csrValue', $csrValue);
}
?>