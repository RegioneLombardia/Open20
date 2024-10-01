<?php

use open20\design\utility\DateUtility;
use open20\design\assets\BootstrapItaliaDesignAsset;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);

$direction = (isset($itemDirection)) ? 'flex-xl-' . $itemDirection : '';
$categorySize  = (isset($categorySize)) ? $categorySize  : 'h6';
$titleSize = (isset($titleSize)) ? $titleSize : 'h5';
$descriptionSize = (isset($descriptionSize)) ? $descriptionSize : '';
$showSignature = (isset($showSignature)) ? $showSignature : 'false';
$showAvatar = (isset($showAvatar)) ? $showAvatar : 'true';
$avatarWrapperSize = (isset($avatarWrapperSize)) ? $avatarWrapperSize : 'md';

//https://www.php.net/manual/en/datetime.format.php
$day = (isset($day)) ? $day : DateUtility::getDate($date, 'php:d');
$month = (isset($month)) ? $month : DateUtility::getDate($date, 'php:M');
$year = (isset($year)) ? $year : DateUtility::getDate($date, 'php:Y');
$date = (isset($date)) ? $date : DateUtility::getDate($date);

$titleForLink = str_replace('"', '\'\'', $title);


$labelReadMore = (isset($labelReadMore)) ? $labelReadMore : 'Leggi tutto';
$titleReadMore = (isset($titleReadMore)) ? $titleReadMore : 'Leggi la novità' . ' ' . $titleForLink;
$showContextMenu = (isset($showContextMenu)) ? $showContextMenu : true;
?>

<div class="container-news <?= $widthColumn ?> <?= $carouselClass ?> <?= $additionalCssExternalClass?>">
    <div class="card-wrapper shadow flex-grow-1 w-100">
        <div class="card card-img <?= $direction ?>">
            <?php if ((isset($image))) : ?>
                <div class="img-responsive-wrapper position-relative">
                    <div class="image-wrapper position-relative h-100">
                        <a href="<?= $url ?>" title="Vai alla news <?= $titleForLink ?>">
                            <img src="<?= $image ?>" alt="immagine evento" class="community-image img-fluid w-100 mh-100" />
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
                        <?php if (($showContextMenu)) : ?>
                        <div class="ml-2">
                            <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-context-menu-widget',
                                [
                                    'buttons' => \open20\amos\core\utilities\ButtonUtility::composeContextMenuButtons($model, $actionModify, $actionDelete),
                                    'model' => $model
                                ]
                            );
                            ?>
                        </div>
                        <?php endif; ?>

                    </div>

                </div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <?php if (!(isset($hideCategory)) && !(isset($image))) : ?>
                    <div class="category-top">
                        <a class="category" href="javascript:void(0)"><?= $category ?></a>
                        <span class="<?= ($category) ? "data" : "card-day" ?>"><?= $date ?></span>
                    </div>
                <?php endif; ?>

                



                <?php if ($showAvatar) : ?>
                    <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                            [
                                'nameSurname' => $nameSurname,
                                'imageAvatar' => $imageAvatar,
                                'url' => $urlAvatar,
                                'additionalInfo' => 'TODO',
                                'removeLink' => $removeLink,
                                'avatarWrapperSize' => $avatarWrapperSize,
                                'extraTextSize' => $extraTextSize

                            ]
                        );
                    ?>
                <?php endif; ?>
                
                <?php if (!$hideCategory) : ?>
                    <hr class="w-75 my-2 ml-0">
                    <p class="card-category text-secondary small font-weight-normal mb-0"><?= $category ?></p>
                <?php endif; ?>

                <?php if ($pubblicationDate) : ?>
                    <div class="d-flex align-items-center mb-2">
                        <svg class="icon icon-sm mr-2" role="img" >
                            <use xlink:href=" <?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#calendar-today"></use>
                        </svg>
                        <span class="small text-uppercase" style="font-weight:600;"><?= $pubblicationDate ?></span>
                    </div>
                <?php endif; ?>


                <a href="<?= $url ?>" class=" link-list-title " title="Vai alla news <?= $titleForLink ?>">
                    <p class="card-title mb-2 font-weight-bold <?= $titleSize ?>"><?= $title ?></p>
                </a>

                <div class=" <?= $descriptionSize ?> card-description font-weight-light">
                    <?= $description ?>
                </div>

                <?php if (!($showSignature)) : ?>
                    <span class="card-signature"><?= 'di' . ' ' . $nameSurname ?> </span>
                <?php endif; ?>

                <?php if($tags){?>
                    <div class="argomenti-sezione-elenco d-flex flex-wrap my-4">
                    <?php foreach($tags as $tag){ 
                        
                        $tagLink = strtolower (str_replace(' ', '-', $tag->nome));
                    ?>
                        <?php if($notLinkableTag){ ?>
                            <p class="chip chip-simple chip-primary text-decoration-none ml-0 mr-2">
                                <span class="chip-label"><?= $tag->nome ?></span>
                            </p>
                        <?php }else{ ?>
                            <a href="/it/<?= $tagLink ?>" class="chip chip-simple chip-primary text-decoration-none ml-0 mr-2">
                                <span class="chip-label"><?= $tag->nome ?></span>
                            </a>   
                        <?php } ?>
                            
                    <?php } ?>
                               
                       
                    
                    </div>
                <?php } ?>
                
                <a class="read-more" href="<?= $url ?>" title="<?= $titleReadMore ?>">
                    <span class="text"><?= $labelReadMore ?></span>
                    <svg class="icon">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-arrow-right"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>