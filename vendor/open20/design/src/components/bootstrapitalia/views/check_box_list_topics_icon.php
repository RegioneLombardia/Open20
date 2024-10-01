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
use yii\web\View;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$this->registerJs(<<<JS

    $("input[id^='card-input-checkbox-']").click(function() {
        $("#card-preference-checkbox-id-" + $(this).val()).toggleClass("active");
   });

JS
, View::POS_READY);

?>

<div class="row">
    <?php
    foreach ($choices as $topic) :
        $topicId = $topic->getId();
        $checked = (in_array($topicId, $model->$attribute)) ? 'checked="checked"' : '';
    ?>

        <div class="<?= $classCardContainer ?>">
            <div class="card-wrapper card-preference card-preference-checkbox pb-3 <?= (!empty($checked))? 'active': '' ?>" id='card-preference-checkbox-id-<?= $topicId ?>'>
                <div class="card rounded bg-card-preference-bg">
                <div class="pretty p-icon p-toggle p-plain" id="card-input-toggle-<?= $topicId ?>">
                                <input type="checkbox" id="card-input-checkbox-<?= $topicId ?>" name="<?= $inputName ?>[]" value="<?= $topicId ?>" <?= $checked ?> />
                                <div class="state p-on">
                                    <svg class="icon icon-sm icon-primary" role="img" aria-label="Icona per attivare una preferenza">
                                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#heart"></use>
                                    </svg>
                                    <label for="card-input-checkbox-<?= $topicId ?>" class="sr-only">Attiva preferenza</label>
                                </div>

                                <div class="state p-off">
                                    <svg class="icon icon-sm icon-primary" role="img" aria-label="Icona per attivare una preferenza">
                                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#heart-outline"></use>
                                    </svg>
                                    <label for="card-input-checkbox-<?= $topicId ?>" class="sr-only"><?= Module::t('amosdesign', 'Attiva preferenza') ?></label>
                                </div>
                            </div>
                    <div class="card-body">
                        <div class="categoryicon-top">
                            <img src="<?= $baseIconsUrl . $topic->getIcon() ?>" alt="icona categoria">
                            

                        </div>
                        <span class="h6 mb-0 mt-3"><?= $topic->getLabel() ?></span>


                    </div>
                </div>
            </div>
        </div>
    <?php
    endforeach;
    ?>
</div>