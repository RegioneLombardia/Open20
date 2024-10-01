<?php

use \yii\helpers\Html;
use open20\design\Module;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);


$faqId = isset($faqId) ? $faqId : '';
$faqQuestion = isset($faqQuestion) ? $faqQuestion : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit?';
$faqAnswer = isset($faqAnswer) ? $faqAnswer : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

$faqCatId = isset($faqCatId) ? $faqCatId : '';

$faqIconSize = isset($faqIconSize) ? $faqIconSize : 'lg';

$hideIcon = isset($hideIcon) ? $hideIcon : false;
$showMoreHelp = isset($showMoreHelp) ? $showMoreHelp : true;

$labelCtaMoreHelp = isset($labelCtaMoreHelp) ? $labelCtaMoreHelp : Module::t('amosdesign', 'Clicca qui');
$titleCtaMoreHelp = isset($titleCtaMoreHelp) ? $titleCtaMoreHelp : Module::t('amosdesign', 'Clicca qui per aprire un nuovo ticket e ricevere supporto!');
$urlCtaMoreHelp = isset($urlCtaMoreHelp) ? $urlCtaMoreHelp : '/ticket/ticket/create?categoriaId=' . $faqCatId;
$targetCtaMoreHelp = isset($targetCtaMoreHelp) ? $targetCtaMoreHelp : '_blank';

$linkCtaMoreHelp = Html::a(
    $labelCtaMoreHelp,
    $urlCtaMoreHelp,
    [
        'title' => $titleCtaMoreHelp,
        'target' => $targetCtaMoreHelp
    ]
);

$textMoreHelp1 = isset($textMoreHelp1) ? $textMoreHelp1 : Module::t('amosdesign', '<strong>Hai bisogno di ulteriore assistenza?</strong>');
$textMoreHelp2 = isset($textMoreHelp2) ? $textMoreHelp2 : Module::t('amosdesign', '{cta} per aprire un nuovo ticket e ricevere supporto!', ['cta' => $linkCtaMoreHelp]);

$classBgActive = (isset($activeBgActive) && $activeBgActive) ? 'collapse-background-active' : '';
$classBgHover = (isset($activeBgHover) && $activeBgHover) ? 'collapse-background-hover' : '';

?>



<div id="collapseListFaqCat<?= $faqCatId ?>" class="bi-faq col-12 collapse-div <?= $classBgActive ?> <?= $classBgHover ?>" role="tablist">
    <div class="collapse-header" id="headingCat<?= $faqCatId ?>Faq<?= $faqId ?>">
        <button class="<?= (!$hideIcon) ? '' : '' ?>" data-toggle="collapse" data-target="#collapseCat<?= $faqCatId ?>Faq<?= $faqId ?>" aria-expanded="false" aria-controls="collapseCat<?= $faqCatId ?>Faq<?= $faqId ?>">
            <div class="d-flex align-items-center">
                <svg class="icon icon-<?= $faqIconSize ?> mr-2">
                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-help-circle"></use>
                </svg>
                <span><?= $faqQuestion ?></span>
            </div>

        </button>
    </div>
    <div id="collapseCat<?= $faqCatId ?>Faq<?= $faqId ?>" class="collapse" role="tabpanel" aria-labelledby="headingCat<?= $faqCatId ?>Faq<?= $faqId ?>">
        <div class="collapse-body">
            <?= $faqAnswer ?>
            <?php if ($showMoreHelp) : ?>
                <p class="mt-3 p-2 lightgrey-bg-a1">
                    <?= $textMoreHelp1 . ' ' . $textMoreHelp2 ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>