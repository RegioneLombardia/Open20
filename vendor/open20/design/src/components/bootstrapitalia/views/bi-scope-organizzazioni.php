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
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$espositoreTitle = (isset($espositoreTitle)) ? $espositoreTitle :  'Espositore';
?>
<div class="espositori-container row py-2">
    <?php if ($espositoreImage) { ?>
        <div class="title-container col-12">
            <h1 class="h3 mb-3"><?= $espositoreTitle ?></h1>
        </div>
        <div class="image-container mb-2 px-2 mb-md-0 col-xl-4">

            <img alt="immagine espositore" class="img-fluid " src="<?= $espositoreImage ?>">

        </div>

        <div class="information-container d-flex flex-column px-2 col-xl-8 ">
            <div class="other-info  d-flex flex-column flex-grow-1">
                <div class="row flex-grow-1">
                    <div class="col-md-6  my-2">
                        <?php if ($espositoreSito) { ?>
                            <p class="mb-1">
                                <span class="font-weight-bold mb-0 pb-0"><?= Module::t('amosdesign', 'Sito web') . ': ' ?> </span>
                                <a href="<?= $espositoreSito ?>" class="font-weight-normal text-truncate"> <?= $espositoreSito ?></a>
                            </p>
                        <?php } ?>

                        <?php if ($espositorePIva) { ?>
                            <p class="mb-1">
                                <span class="font-weight-bold"><?= Module::t('amosdesign', 'Partita IVA') . ': ' ?></span>
                                <span ><?= $espositorePIva ?></span>
                            </p>
                        <?php } ?>

                        <?php if ($espositoreReferente) { ?>
                            <p class="mb-1">
                                <span class="font-weight-bold"><?= Module::t('amosdesign', 'Referente operativo') . ': ' ?></span>
                                <span><?= $espositoreReferente ?></span>
                            </p>
                        <?php } ?>

                    </div>
                    <div class="col-md-6  my-2 flex-column">
                        <?php if ($espositoreIndirizzo) { ?>
                            <div class="pb-1">
                                <p class="pl-0 pb-0 mb-0">
                                    <svg class="icon icon-sm " role="img">
                                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-maps"></use>
                                    </svg>
                                    <?= $espositoreIndirizzo ?>
                                </p>
                            </div>
                        <?php } ?>
                        <?php if ($espositoreEmail) { ?>
                            <div class="pb-1">
                                <svg class="icon icon-sm " role="img">
                                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#email"></use>
                                </svg>
                                <a class="text-decoration-underline pb-0 mb-0" href="mailto:<?= $espositoreEmail ?>">

                                    <?= $espositoreEmail ?>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($espositoreTelefono) { ?>
                            <div class="pb-1">
                                <p class="pb-0 mb-0">
                                    <svg class="icon icon-sm" role="img">
                                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#phone"></use>
                                    </svg>
                                    <?= $espositoreTelefono ?>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div>
                <a href="#" type="button" class="btn btn-primary btn-sm"><?= Module::t('amosdesign', 'Associa') ?></a>

            </div>
        </div>

    <?php } else { ?>
        <div class="information-container px-2">
            <div class="title-container">
                <h3 class="h3"><?= $espositoreTitle ?></h3>
            </div>
            <div class="other-info-container">
                <div class="row">
                    <div class="col-md-6 my-2">
                        <?php if ($espositoreSito) { ?>
                            <div class="d-flex flex-column flex-sm-row pb-1">
                                <p class=" font-weight-bold mb-0 pb-0"><?= Module::t('amosdesign', 'Sito web') . ': ' ?> </p>
                                <a href="<?= $espositoreSito ?>" class="font-weight-normal text-truncate p-0 mb-0 ml-1"> <?= $espositoreSito ?></a>
                            </div>
                        <?php } ?>

                        <?php if ($espositorePIva) { ?>
                            <div class="d-flex flex-column flex-sm-row pb-1">
                                <p class="font-weight-bold  pb-0 mb-0"><?= Module::t('amosdesign', 'Partita IVA') . ': ' ?></p>
                                <p class="pb-0 mb-0 ml-1"><?= $espositorePIva ?></p>
                            </div>
                        <?php } ?>

                        <?php if ($espositoreReferente) { ?>
                            <div class="d-flex flex-column flex-sm-row pb-1">
                                <p class="font-weight-bold pb-0 mb-0"><?= Module::t('amosdesign', 'Referente operativo') . ': ' ?></p>
                                <p class="pb-0 mb-0 ml-1"><?= $espositoreReferente ?></p>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-md-6 my-2 flex-column">
                        <?php if ($espositoreIndirizzo) { ?>
                            <div class="pb-1">
                                <p class="pl-0 pb-0 mb-0">
                                    <svg class="icon icon-sm " role="img">
                                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#google-maps"></use>
                                    </svg>
                                    <?= $espositoreIndirizzo ?>
                                </p>
                            </div>
                        <?php } ?>
                        <?php if ($espositoreEmail) { ?>
                            <div class="pb-1">
                                <svg class="icon icon-sm " role="img">
                                    <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#email"></use>
                                </svg>
                                <a class="pb-0 mb-0" mailto="<?= $espositoreEmail ?>">

                                    <?= $espositoreEmail ?>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($espositoreTelefono) { ?>
                            <div class="pb-1">
                                <p class="pb-0 mb-0">
                                    <svg class="icon icon-sm" role="img">
                                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#phone"></use>
                                    </svg>
                                    <?= $espositoreTelefono ?>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <a class="btn btn-outline-secondary btn-icon my-1 rounded-0">
                    <svg class="icon icon-primary icon-sm p-1" role="img">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/sprite/material-sprite.svg#star-outline"></use>
                    </svg>
                    <?= Module::t('amosdesign', 'Associa') ?>
                </a>
            </div>
        </div>
    <?php } ?>
</div>