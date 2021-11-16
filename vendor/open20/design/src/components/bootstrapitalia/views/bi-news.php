<?php

use open20\design\Module;
use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;
$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$direction = (isset($itemDirection)) ? 'flex-xl-' . $itemDirection : '';
$categorySize = (isset($categorySize)) ? $categorySize : 'h6';
$customCategoryClass = (isset($customCategoryClass)) ? $customCategoryClass : '';
$titleSize = (isset($titleSize)) ? $titleSize : 'h5';
$descriptionSize = (isset($descriptionSize)) ? $descriptionSize : '';
$showSignature     = (isset($showSignature)) ? $showSignature : false; //firma alla fine della card
$showAvatar        = (isset($showAvatar)) ? $showAvatar : true;
$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'md';
$partnership       = (isset($prevalentPartnership) ? $prevalentPartnership : '');
$buttons   = (isset($buttons) ? $buttons : []);
$model     = (isset($model) ? $model : null);
$actionModify      = (isset($actionModify) ? $actionModify : null);
$actionDelete      = (isset($actionDelete) ? $actionDelete : null);

//https://www.php.net/manual/en/datetime.format.php
$day   = DateUtility::getDate($date, 'php:d');
$month = DateUtility::getDate($date, 'php:M');
$year  = DateUtility::getDate($date, 'php:Y');
$date  = DateUtility::getDate($date);

$labelReadMore = (isset($labelReadMore)) ? $labelReadMore : 'Leggi';
$titleReadMore = (isset($titleReadMore)) ? $titleReadMore : 'Leggi la notizia' . ' ' . $title;
$widthColumn = (isset($widthColumn)) ? $widthColumn :  'col-lg-4 col-md-6';

if (strlen($description) > 150) {
   $description = \open20\amos\core\utilities\StringUtils::truncateHTML(
        $description,
        147
    );
}
?>

<div class="container-news <?= $widthColumn ?> <?= $carouselClass ?>">
    <div class="card-wrapper">
        <div class="card card-img <?= $direction ?>">
            <?php if ((isset($image))) : ?>
                <div class="img-responsive-wrapper position-relative">
                    <div class="image-wrapper position-relative h-100">
                        <a href="<?= $url ?>" title="Vai alla news <?= $title ?>">
                            <img src="<?= $image ?>" alt="<?= Module::t('amosdesign', 'immagine della notizia') ?>" class="news-image img-fluid w-100 mh-100" />
                        </a>
                        <div class="card-calendar d-flex flex-column justify-content-center position-absolute rounded-0 text-uppercase">
                            <span class="card-day font-weight-bold text-600 lead"><?= $day ?></span>
                            <span class="card-month font-weight-bold text-600"><?= $month ?></span>
                            <span class="card-year font-weight-light text-600 small"><?= $year ?></span>
                        </div>


                    </div>
                    <div class="other-info-item d-flex align-items-center pl-3">
                        <?php if (($newPubblication)) : ?>
                            <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-badge-new-pubblication'
                            );
                            ?>
                        <?php endif; ?>
                        <div class="ml-2">
                            <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                                [
                                    'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete)
                                ]
                            );
                            ?>
                        </div>

                    </div>

                </div>
            <?php endif; ?>
            <div class="card-body px-0 py-2">
                
                <?php if ($showAvatar && !$showSignature) : ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                        [
                            'nameSurname' => $nameSurname,
                            'imageAvatar' => $imageAvatar,
                            'url' => $urlAvatar,
                            'additionalInfo' => $additionalInfoAvatar,
                            // 'scopes' => $contentScopesAvatar,
                            'removeLink' => $removeLink,
                            'avatarWrapperSize' => $avatarWrapperSize,
                            'extraTextSize' => $extraTextSize
                        ]
                    );
                    ?>
                    <hr class="w-75 my-2 ml-0">
                <?php endif; ?>

                <?php if (!$hideCategory) : ?>
                   
                    <span class="card-category text-secondary text-uppercase badge font-weight-normal mb-0 <?= $customCategoryClass ?> text-uppercase rounded-0" <?php if ((!empty($colorBgCategory))) : ?> style="background-color: <?= $colorBgCategory ?> !important; padding: 4px; " <?php endif; ?>><strong <?php if ((!empty($colorTextCategory))) : ?> style="color: <?= $colorTextCategory ?> " <?php endif; ?>><?= $category ?></strong></span>
                <?php endif; ?>


                <h3 class="card-title mb-2 <?= $titleSize ?> font-weight-bold">
                    <a href="<?= $url ?>" class=" link-list-title d-inline" title="Vai alla news <?= $title ?>">
                        <?= $title ?>
                    </a>
                    <?php if (!empty($contentScopesAvatar)) : ?>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="<?= $contentScopesAvatar ?>">
                            <svg class="icon icon-sm icon-secondary" role="img" aria-label="News in community">
                                <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#account-supervisor-circle"></use>
                            </svg>
                            <span class="sr-only"><?= $contentScopesAvatar ?></span>
                        </a>
                    <?php endif; ?>
                </h3>





                <?php if ((!empty($abstract))) : ?>
                    <p class=" <?= $descriptionSize ?> card-description font-weight-light title-four-line">
                        <?= $abstract ?>
                    </p>
                <?php else : ?>
                    <div class=" <?= $descriptionSize ?> card-description font-weight-light mb-4">
                        <?= $description ?>
                    </div>
                <?php endif; ?>

                <?php if ($showSignature) : ?>
                    <span class="card-signature"><?= 'di' . ' ' . $nameSurname ?> </span>
                <?php endif; ?>

                
                <a class="read-more" href="<?= $url ?>" title="<?= $titleReadMore ?>">
                    <span class="text"><?= $labelReadMore ?></span>
                </a>
            </div>
        </div>
    </div>
</div>