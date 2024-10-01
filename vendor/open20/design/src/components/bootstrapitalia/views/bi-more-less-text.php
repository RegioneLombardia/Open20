<?php

use yii\web\View;
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\core\utilities\StringUtils;
use open20\design\Module;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$jsReadMore = <<< JS

$("#moreTextJs .changeContentJs > .actionChangeContentJs").click(function(){
    $("#moreTextJs .changeContentJs").toggle();
    $('html, body').animate({scrollTop: $('#moreTextJs').offset().top - 120},1000);
});
JS;
$this->registerJs($jsReadMore, View::POS_READY);
?>

<?php
$limit = (isset($limit))? $limit : 0;
$moreContentTextLink = (isset($moreContentTextLink))? $moreContentTextLink :Module::t('amosdesign', 'espandi descrizione');
$moreContentTitleLink = (isset($moreContentTitleLink))? $moreContentTitleLink :Module::t('amosdesign', 'Leggi il contenuto completo');

$lessContentTextLink = (isset($lessContentTextLink))? $lessContentTextLink :Module::t('amosdesign', 'riduci descrizione');
$lessContentTitleLink = (isset($lessContentTitleLink))? $lessContentTitleLink :Module::t('amosdesign', 'Riduci contenuto');
?>

<div id="moreTextJs">
    <div class="changeContentJs partialContent">
        <?= StringUtils::truncateHTML($content, $limit) ?>
        <a class="read-more actionChangeContentJs" href="javascript:void(0)" title="<?= $moreContentTitleLink ?>">
            <span class="text"><?= $moreContentTextLink ?></span>
            <svg class="icon">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-down"></use>
            </svg>
        </a>
    </div>
    <div class="changeContentJs totalContent" style="display:none">
        <?= $content ?>
        <a class="read-more actionChangeContentJs" href="javascript:void(0)" title="<?= $lessContentTitleLink ?>">
            <span class="text"><?= $lessContentTextLink ?></span>
            <svg class="icon">
                <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-up"></use>
            </svg>
        </a>
    </div>
</div>