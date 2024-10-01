<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\prodottiservizi/views
 */

use open20\prodottiservizi\models\ProdottiDestinatari;
use open20\prodottiservizi\models\ProdottiSoluzioni;
use open20\prodottiservizi\models\ProdottiFinalita;
use open20\prodottiservizi\Module;
use open20\prodottiservizi\utility\ProdottiServiziUtility;

/**
 * @var yii\web\View $this
 */

$checked = ['', '', '', ''];
$checked[$scope] = 'checked';

?>

<div id="filter-PS" class="prodotti-servizi-search">
    <div class="row">
        <div class="col-sm-9 col-xs-8">
            <p class="h5 m-b-0 text-uppercase"><strong><?= Module::t('prodottiservizi', '#TITLEPSSEARCH') ?></strong></p>
        </div>
        <div class="col-sm-3 col-xs-4">
            <a class="btn-expand-search pull-right" role="button" data-toggle="collapse" href="#collapseSearch" aria-expanded="true" aria-controls="collapseSearch">
                <span class="label-show"><?= Module::t('prodottiservizi', 'mostra ') ?><span class="mdi mdi-chevron-down"></span></span>
                <span class="label-hidden"><?= Module::t('prodottiservizi', 'nascondi') ?><span class="mdi mdi-chevron-up"></span></span>
            </a>
        </div>
    </div>
    <div class="collapse in m-t-20" id="collapseSearch">
        <div class="riga-tipo">
            <div class="row">
                <div class="col-md-3">
                    <strong><?= Module::t('prodottiservizi', '#TITLEDESTINATARISEARCH') ?></strong>
                </div>
                <div id="destinatari_buttons" class="col-md-9">
                    <?php foreach ($destinatari as $destinatario) : ?>
                        <button type="button" id="dest-<?= $destinatario->id ?>" class="chip JqueryButtonFilter">
                            <span class="mdi mdi-<?= $destinatario->icon ?> icon-chip"></span>
                            <span class="chip-label"><?= $destinatario->name ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        <div class="riga-tipo">
            <div class="row">
                <div class="col-md-3">
                    <strong><?= Module::t('prodottiservizi', '#TITLESOLUZIONISEARCH') ?></strong>
                </div>
                <div class="col-md-9">
                    <?php foreach ($soluzioni as $soluzione) : ?>
                        <button type="button" id="solu-<?= $soluzione->id ?>" class="chip JqueryButtonFilter">
                            <span class="mdi mdi-<?= $soluzione->icon ?> icon-chip"></span>
                            <span class="chip-label"><?= $soluzione->name ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        <div class="riga-tipo">
            <div class="row">
                <div class="col-md-3">
                    <strong><?= Module::t('prodottiservizi', '#TITLEFINALITASEARCH') ?></strong>
                </div>
                <div class="col-md-9">
                    <?php foreach ($finalita as $fin) : ?>
                        <button type="button" id="fina-<?= $fin->id ?>" class="chip JqueryButtonFilter">
                            <span class="chip-label"><?= $fin->name ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <!--
        <div class="riga-tipo">
            <div id="scope-change">
                <div class="row">
                    <div class="col-md-3">
                        <strong>< ?= Module::t('prodottiservizi', 'Scope') ?></strong>
                    </div>
                    <div class="col-md-9">
                        <div class="list-status flex-radio">
                            <div class="radio">
                                <input type="radio" id="all-scope" class="JqueryRadioFilter" name="scope" value="0" <?= $checked[0] ?>>
                                <label for="all-scope">< ?= Module::t('prodottiservizi', 'Tutti') ?></label>
                            </div>
                            <div class="radio">
                                <input type="radio" id="imprese" class="JqueryRadioFilter" name="scope" value="1" <?= $checked[1] ?>>
                                <label for="imprese">< ?= ProdottiServiziUtility::getAmbitoLabel(1) ?></label>
                            </div>
                            <div class="radio">
                                <input type="radio" id="PA" class="JqueryRadioFilter" name="scope" value="2" <?= $checked[2] ?>>
                                <label for="PA">< ?= ProdottiServiziUtility::getAmbitoLabel(2) ?></label>
                            </div>
                            <div class="radio">
                                <input type="radio" id="professionisti" class="JqueryRadioFilter" name="scope" value="3" <?= $checked[3] ?>>
                                <label for="professionisti">< ?= ProdottiServiziUtility::getAmbitoLabel(3) ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
        <!--
        <div class="riga-tipo">
            <div class="row">
                <div class="col-md-3">
                    <label for="field_title">< ?= Module::t('prodottiservizi', 'Vuoi specificare il titolo?') ?></label>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <input type="text" class="form-control" style="height:100%; border: 2px solid #d2d2d2" name="title" id="field_title">
                        <span class="input-group-btn">
                            <button class="btn btn-primary JquerySendFilter">
                                < ?= Module::t('prodottiservizi', 'Cerca titolo') ?>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        -->
    </div>
</div>
</div>

<div id="status-change" class="filter-status">
    <div class="row flexi">
        <div class="col-md-3">
            <strong><?= Module::t('prodottiservizi', 'Stato') ?></strong>
        </div>
        <div class="col-md-9">
            <div class="list-status">
                <div class="radio">
                    <input type="radio" id="all-status" class="JqueryRadioFilter" name="stat" value="0" checked>
                    <label for="all-status"><?= Module::t('prodottiservizi', 'Tutti') ?></label>
                </div>
                <div class="radio">
                    <input type="radio" id="aperto" class="JqueryRadioFilter" name="stat" value="1">
                    <label for="aperto"><?= ProdottiServiziUtility::getStatoLabel(1) ?></label>
                </div>
                <div class="radio">
                    <input type="radio" id="chiuso" class="JqueryRadioFilter" name="stat" value="2">
                    <label for="chiuso"><?= ProdottiServiziUtility::getStatoLabel(2) ?></label>
                </div>
                <div class="radio">
                    <input type="radio" id="archiviato" class="JqueryRadioFilter" name="stat" value="3">
                    <label for="archiviato"><?= ProdottiServiziUtility::getStatoLabel(3) ?></label>
                </div>
                <button class="btn btn-danger btn-reset-filter JqueryResetFilter">
                    <span class="mdi mdi-minus-circle-outline m-r-5"></span>
                    <?= Module::t('prodottiservizi', 'Azzera Filtri') ?>
                </button>
            </div>
        </div>
    </div>
</div>