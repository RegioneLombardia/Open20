<?php
use open20\design\Module;
?>
<div class="it-modal header-search-modal">
    <div class="modal fade" tabindex="-1" role="dialog" id="searchModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <svg class="icon icon-lg">
                            <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-close"></use>
                            </svg>
                        </button>
                        <div class="modal-title h2"><?= Module::t('amosdesign','Cerca')?></div>
                    </div>


                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="h5"><?=Module::t('amosdesign','Inserisci una o più parole chiave per affinare ulteriormente la ricerca')?></div>
                        <div class="form-group autocomplete-wrapper-big mt-5">
                            <form class="hidden" data-toggle="validator" action="<?= $searchAction ?>" method="get">
                                <input type="search" name="query" id="searchNavbar" class="autocomplete" value="" placeholder="<?=Module::t('amosdesign','Testo da cercare')?>" required>
                <!--                            <input type="search" class="autocomplete" placeholder="Testo da cercare" id="autocomplete-two" name="autocomplete-two" data-autocomplete="[]">-->
                                <span class="autocomplete-icon" aria-hidden="true">
                                    <svg class="icon icon-sm">
                                    <use xlink:href="<?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-search"></use>
                                    </svg>
                                </span>
                                <!-- <ul class="autocomplete-list" id="testAutocomplete2">
                                    <li>
                                        <a href="#">
                                            <svg class="icon icon-sm">
                                                <use xlink:href="< ?= $currentAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-pa"></use>
                                            </svg>
                                            <span class="autocomplete-list-text">
                                                <span>Comune di <mark>Pi</mark>sa</span><em>Amministrazione</em>
                                            </span>
                                        </a>
                                    </li>
                                </ul> -->
                                <label for="autocomplete-two" class="sr-only"><?= Module::t('amosdesign','Cerca nella piattaforma {platformName}',['platformName' => \Yii::$app->name])?></label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>