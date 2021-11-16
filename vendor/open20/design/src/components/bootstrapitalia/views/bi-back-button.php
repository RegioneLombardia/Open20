<?php

use yii\web\View;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$jsHistory = <<< JS

    $(document).ready(function () {
        if(window.history.length <= 1) {
            var elements = document.getElementsByClassName("jsBackButton");
            for (var i = 0; i < elements.length; i++) {
                elements[i].classList.add("d-none");
            }
        }
    });

JS;
$this->registerJs($jsHistory, View::POS_READY);
?>


<div class="jsBackButton go-back">
    <a href="http://javascript:void(0)" title="<?= Module::t('amosdesign', 'Torna indietro') ?>">
        <svg class="icon icon-xs icon-primary">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#arrow-left"></use>
        </svg>
        <?= Module::t('amosdesign', 'Torna indietro') ?>
    </a>
</div>