<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\assets\BootstrapItaliaDesignAsset;
use yii\helpers\VarDumper;
use yii\web\View;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$tagWrapperSize = (isset($tagWrapperSize)) ? $tagWrapperSize : 'md';
if ($tagWrapperSize == 'sm') {
    $tagClassSize = 'size-sm';
    $tagTextSize = (isset($tagTextSize)) ? $tagTextSize : 'small';
    $tagIconSize = 'xs';
  }  else {
    $tagClassSize = 'size-md';
    $tagIconSize = 'md';
  }

$this->registerJs(
    <<<JS

    $("input[id^='card-input-checkbox-']").click(function() {
        $("#tags-list-checkbox-id-" + $(this).val()).toggleClass("active");
   });

JS
,    View::POS_READY
);

?>



    <?php
    foreach ($choices as $topic) :
        $topicId = $topic->id;
        $arrayIds = [];
        foreach ($model->$attribute as $tag) {
            $arrayIds[] = $tag->id;
        }
        $checked = (in_array($topicId, $arrayIds)) ? 'checked="checked"' : '';
    ?>

        <div class="tag-checkbox-container <?= $tagClassSize ?>">
            <div class="card-wrapper tags-list tags-list-checkbox pb-2 <?= (!empty($checked)) ? 'active' : '' ?>" id='tags-list-checkbox-id-<?= $topicId ?>'>
                <?php if ($showIcon=='true') : ?>
                    <div class="card rounded d-flex flex-row-reverse align-items-center justify-content-center">
                    <div class="align-self-start pretty d-flex align-items-center p-icon p-toggle p-plain" id="card-input-toggle-<?= $topicId ?>">
                    <?php else : ?>
                        <div class="card badge-pill d-flex flex-row-reverse align-items-center justify-content-center">
                        <div class="pretty d-flex align-items-center p-icon p-toggle p-plain" id="card-input-toggle-<?= $topicId ?>">
                    <?php endif ?>   
                        <input type="checkbox" id="card-input-checkbox-<?= $topicId ?>" name="<?= $inputName ?>[]" value="<?= $topicId ?>" <?= $checked ?> />
                    
                        <div class="state p-on ">
                            <svg class="icon icon-<?= $tagIconSize ?> icon-primary" role="img" aria-label="Icona per attivare una preferenza">
                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#heart"></use>
                            </svg>
                            <label for="card-input-checkbox-<?= $topicId ?>" class="sr-only"><?= Module::t('amosdesign', 'Attiva preferenza') ?></label>
                        </div>

                        <div class="state p-off ">
                            <svg class="icon icon-<?= $tagIconSize ?> icon-primary" role="img" aria-label="Icona per attivare una preferenza">
                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#heart-outline"></use>
                            </svg>
                            <label for="card-input-checkbox-<?= $topicId ?>" class="sr-only"><?= Module::t('amosdesign', 'Attiva preferenza') ?></label>
                        </div>
                    </div>
                    <div class="card-body p-0 title-one-line">
                        <?php if ($showIcon=='true') : ?>
                            <div class="categoryicon-top">
                                <img src="<?= $baseIconsUrl . $topic->icon ?>" alt="icona categoria">
                            </div>
                        <?php endif ?>
                        <span class="h6 mb-0 mt-0 <?= $tagTextSize ?>" title="<?= $topic->nome ?>"><?= $topic->nome ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>
