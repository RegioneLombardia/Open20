<?php

use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\assets\PrismJSAsset;
use open20\design\components\bootstrapitalia\CheckBoxListTopicsIcon;
use open20\design\components\bootstrapitalia\CheckBoxListTags;
use open20\design\Module;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
$PrismJSAsset         = PrismJSAsset::register($this);
?>

<style>
    #jsMenu {
        position: sticky !important;
        top: 5rem;
        height: calc(100vh - 5rem);
        overflow-y: auto;
    }

    h2[id]:before {
        content: "";
        display: block;
        height: 120px;
        margin: -120px 0 0;
    }

    h3[id]:before {
        content: "";
        display: block;
        height: 120px;
        margin: -120px 0 0;
    }

    section>h3 {
        margin-top: 20px;
    }

    .square {
        width: 100px;
        height: 100px;
    }

    .bd-example {
        padding: 1.5rem;
        margin-top: 1rem;
        border: solid #f3f3f5;
        border-width: 4px;
        border-radius: 4px;
    }

    .bd-example-btn .btn {
        margin: 4px;
    }
</style>

<?php
$date                 = new DateTime();
$date                 = $date->format('php:d/m/Y');
?>


<?php
echo $this->render('parts/devs-tools');
?>

<div class="mt-3 container-fluid">
    <div class="row">
        <div id="jsMenu" class="col-12 col-lg-3">
            <nav class="navbar it-navscroll-wrapper navbar-expand-lg it-bottom-navscroll it-right-side py-3 py-lg-0">
                <button class="custom-navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" data-target="#navbarNav">
                    <span class="it-list"></span>Apri menu
                </button>
                <div class="navbar-collapsable" id="navbarNav" style="display: none;">
                    <div class="overlay" style="display: none;"></div>
                    <div class="close-div sr-only">
                        <button class="btn close-menu" type="button">
                            <span class="it-close"></span>close
                        </button>
                    </div>
                    <a class="it-back-button" href="#" style="display: none;">
                        <svg class="icon icon-sm icon-primary align-top">
                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-chevron-left"></use>
                        </svg>
                        <span>Indietro</span>
                    </a>
                    <div class="menu-wrapper">
                        <div class="link-list-wrapper">
                            <ul class="link-list">

                                <li>
                                    <a class="list-item large medium" href="#introduzione"><span>Introduzione</span></a>
                                </li>
                                <li>
                                    <a class="list-item large medium" href="#colori"><span>Colori</span></a>
                                </li>
                                <li><a class="list-item large medium right-icon" href="#collapseOne" data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne"><span>Componenti</span><svg class="icon icon-xs icon-primary right">
                                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                        </svg></a>
                                    <ul class="link-sublist collapse " id="collapseOne">
                                        <!--allegati lista-->
                                        <li>
                                            <a class="list-item" href="#list-allegati"><span>Allegati lista</span></a>
                                        </li>
                                        <!--avatar card-->
                                        <li>
                                            <a class="list-item" href="#avatar"><span>Avatar</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#bottoni"><span>Bottoni</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#card-preference"><span>Cards Preference</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#carousel"><span>Carousel</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#carousel-news"><span>Carousel news</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#photogallery"><span>Gallery immagini</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#calendar-carousel"><span>Carousel calendario</span></a>
                                        </li>

                                        <li>
                                            <a class="list-item" href="#card-checkbox"><span>Checkbox Cards Preference</span></a>
                                        </li>




                                        <!--checkbox link privacy-->
                                        <li>
                                            <a class="list-item" href="#Checkbox-link"><span>Checkbox con link alla privacy</span></a>
                                        </li>
                                        <!--checkbox collapse-->
                                        <li>
                                            <a class="list-item" href="#checkbox-collapse"><span>Checkbox collapse</span></a>
                                        </li>
                                        <!--collapse-->
                                        <li>
                                            <a class="list-item" href="#collapse"><span>Collapse</span></a>
                                        </li>

                                        <!--community lista-->
                                        <li>
                                            <a class="list-item" href="#Community-card"><span>Community card</span></a>
                                        </li>
                                        <!--discussioni card-->
                                        <li>
                                            <a class="list-item" href="#discussioni-card"><span>Discussioni card</span></a>
                                        </li>
                                        <!--discussioni lista-->
                                        <li>
                                            <a class="list-item" href="#discussioni"><span>Discussioni lista</span></a>
                                        </li>
                                        <!--documenti card-->
                                        <li>
                                            <a class="list-item" href="#documents-card"><span>Documenti card</span></a>
                                        </li>
                                        <!--documenti lista-->
                                        <li>
                                            <a class="list-item" href="#document-list"><span>Documenti lista</span></a>
                                        </li>
                                        <!--eventi card-->
                                        <li>
                                            <a class="list-item" href="#events"><span>Eventi card</span></a>
                                        </li>
                                        <!--eventi lista-->
                                        <li>
                                            <a class="list-item" href="#events-list"><span>Eventi lista</span></a>
                                        </li>
                                        <!--eventi scope-->
                                        <li>
                                            <a class="list-item" href="#events-scope"><span>Eventi scope</span></a>
                                        </li>
                                        <!--faq lista-->
                                        <li>
                                            <a class="list-item" href="#faq-list"><span>Faq lista</span></a>
                                        </li>
                                        <!--modali-->
                                        <li>
                                            <a class="list-item" href="#modal"><span>Modali</span></a>
                                        </li>
                                        <!--news-->
                                        <li>
                                            <a class="list-item" href="#news"><span>News</span></a>
                                        </li>
                                        <!--news card-->
                                        <li>
                                            <a class="list-item" href="#news-list"><span>News card</span></a>
                                        </li>

                                        <!--news lista-->
                                        <li>
                                            <a class="list-item" href="#news-list-item"><span>News lista</span></a>
                                        </li>
                                        <!--organizzazioni card-->
                                        <li>
                                            <a class="list-item" href="#organization"><span>Organizzazioni card</span></a>
                                        </li>
                                        <!--organizzazioni lista-->
                                        <li>
                                            <a class="list-item" href="#organization-list"><span>Organizzazioni lista</span></a>
                                        </li>
                                        <!--organizzazioni scope-->
                                        <li>
                                            <a class="list-item" href="#scope-organizzazioni"><span>Organizzazioni scope</span></a>
                                        </li>
                                        <!--podcast lista-->
                                        <li>
                                            <a class="list-item" href="#podcast-list"><span>Podcast lista</span></a>
                                        </li>
                                        <!--podcast puntate-->
                                        <li>
                                            <a class="list-item" href="#podcast-puntate-list"><span>Podcast card</span></a>
                                        </li>
                                        <!--proposte di collaborazione-->
                                        <li>
                                            <a class="list-item" href="#proposte-collaborazione"><span>Proposte di collaborazione</span></a>
                                        </li>
                                        <!--proposte di collaborazione een-->
                                        <li>
                                            <a class="list-item" href="#proposte-collaborazione-een"><span>Proposte di collaborazione EEN</span></a>
                                        </li>
                                        <!--risultati ricerca-->
                                        <li>
                                            <a class="list-item" href="#search-results-item"><span>Risultati della Ricerca lista</span></a>
                                        </li>
                                        <!--sondaggi card-->
                                        <li>
                                            <a class="list-item" href="#sondaggi-card"><span>Sondaggi card</span></a>
                                        </li>
                                        <!--sondaggi lista-->
                                        <li>
                                            <a class="list-item" href="#sondaggi-lista"><span>Sondaggi lista</span></a>
                                        </li>
                                        <!--tag-->
                                        <li>
                                            <a class="list-item" href="#tag"><span>Tag</span></a>
                                        </li>
                                        <li>
                                            <a class="list-item" href="#tag-manage"><span>Tag manage</span></a>
                                        </li>
                                        <!--torna indietro-->
                                        <li>
                                            <a class="list-item" href="#back-button"><span>Torna indietro</span></a>
                                        </li>
                                        <!--utenti lista-->
                                        <li>
                                            <a class="list-item" href="#view-list-avatar"><span>Utenti lista</span></a>
                                        </li>
                                        <!--wizard-->
                                        <li>
                                            <a class="list-item" href="#wizard"><span>Wizard Steps</span></a>
                                        </li>
                                        <!--wizard-->
                                        <li>
                                            <a class="list-item" href="#widget-mio-profilo"><span>Widget Mio profilo</span></a>
                                        </li>


                                    </ul>
                                </li>

                                <li><a class="list-item large medium right-icon" href="#collapseTwo" data-toggle="collapse" aria-expanded="false" aria-controls="collapseTwo"><span>Form</span><svg class="icon icon-xs icon-primary right">
                                            <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-expand"></use>
                                        </svg></a>
                                    <ul class="link-sublist collapse" id="collapseTwo">

                                        <li><a class="list-item" href="#formExample"><span>Singoli componenti</span></a>
                                        </li>
                                        <li><a class="list-item" href="#vari-input"><span>Vari input</span></a>
                                        </li>
                                        <li><a class="list-item" href="#form-rounded"><span>Stile rounded</span></a>
                                        </li>
                                        <li><a class="list-item" href="#toggle"><span>Toggles</span></a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a class="list-item large medium right-icon" href="#icone"><span>Icone</span></a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="jsMain" class="col-12 col-lg-9 it-page-sections-container">

            <h1 class="no_toc it-page-section" id="introduzione">Componenti Bootstrap Italia Custom</h1>
            <p class="lead mb-0">Componenti personalizzati per plugin Design</p>
            <div class="mt-3">
                <a class="btn btn-primary btn-icon" href="https://italia.github.io/bootstrap-italia/docs/come-iniziare/introduzione/" target="_blank">
                    <span class="pr-1">Sito Bootstrap Italia</span>
                    <svg class="icon icon-white">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-external-link"></use>
                    </svg>
                </a>
            </div>
            <div class="mt-3">
                <a class="btn btn-primary btn-icon" href="<?= Yii::$app->params['platform']['frontendUrl'] ?>/design/design-base/configurations" target="_blank">
                    <span class="pr-1">Configurazioni</span>
                    <svg class="icon icon-white">
                        <use xlink:href="<?= $bootstrapItaliaAsset->baseUrl ?>/node_modules/bootstrap-italia/dist/svg/sprite.svg#it-external-link"></use>
                    </svg>
                </a>
            </div>
            <section class="py-5 border-bottom">
                <h2>Standard immagini</h2>
                <p>
                <ul>
                    <li>formato: 16:9</li>
                    <li>larghezza: 1920px (min. 1300px)</li>
                    <li>dpi: 72 (minimo)</li>
                </ul>
                </p>
            </section>
            <!--colori-->
            <section class="py-5 border-bottom">
                <h2 class="it-page-section" id="colori">Colori</h2>
                <p>Colori usati nella piattaforma</p>
                <p><strong class="h3">Brandbook colors:</strong></p>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-primary">#2a7a38</div>
                        <span>$brandbook-primary</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-secondary">#003354</div>
                        <span>$brandboook-secondary</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-neutral-1">#e6e9f2</div>
                        <span>$brandbook-neutral-1</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 border brandbook-theme-bg-brandbook-neutral-2">#fcfcff</div>
                        <span>$brandbook-neutral-2</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-neutral-3">#003354</div>
                        <span>$brandbook-neutral-3</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-triadic-2">#008758</div>
                        <span>$brandbook-triadic-2</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-triadic-1">#a66300</div>
                        <span>$brandbook-triadic-1</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-triadic-3">#d92828</div>
                        <span>$brandbook-triadic-3</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-btn-primary">#2a7a38</div>
                        <span>$brandbook-btn-primary</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square border p-3 brandbook-theme-bg-brandbook-btn-secondary">#ffffff</div>
                        <span>$brandbook-btn-secondary</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 brandbook-theme-bg-brandbook-btn-tertiary">#5E7887</div>
                        <span>$brandbook-btn-tertiary</span>
                    </div>
                </div>
                <hr>
                <p><strong class="h3">Theme colors:</strong></p>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-primary">#2a7a38</div>
                        <span>$primary</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-secondary">#5E7887</div>
                        <span>$secondary</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-tertiary">#003354</div>
                        <span>$tertiary</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-success">#008758</div>
                        <span>$green-accessible</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-warning">#a66300</div>
                        <span>$orange-accessible</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-danger">#d92828</div>
                        <span>$red-accessible</span>
                    </div>
                </div>
                <hr>
                <div class="bd-example my-5">
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <p>Tinte e ombre</p>
                            <div class="c-line primary-bg-a1">a1</div>
                            <div class="c-line primary-bg-a2">a2</div>
                            <div class="c-line primary-bg-a3">a3</div>
                            <div class="c-line primary-bg-a4">a4</div>
                            <div class="c-line white-color primary-bg-a5">a5</div>
                            <div class="c-line white-color primary-bg-a6">a6</div>
                            <div class="c-line white-color primary-bg-a7">a7</div>
                            <div class="c-line white-color primary-bg-a8">a8</div>
                            <div class="c-line white-color primary-bg-a9">a9</div>
                            <div class="c-line white-color primary-bg-a10">a10</div>
                            <div class="c-line white-color primary-bg-a11">a11</div>
                            <div class="c-line white-color primary-bg-a12">a12</div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <p>Toni</p>
                            <div class="c-line primary-bg-b1 white-color">b1</div>
                            <div class="c-line primary-bg-b2 white-color">b2</div>
                            <div class="c-line primary-bg-b3 white-color">b3</div>
                            <div class="c-line primary-bg-b4 white-color">b4</div>
                            <div class="c-line primary-bg-b5 white-color">b5</div>
                            <div class="c-line primary-bg-b6 white-color">b6</div>
                            <div class="c-line primary-bg-b7 white-color">b7</div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <p>Variazione di saturazione</p>
                            <div class="c-line primary-bg-c1">c1</div>
                            <div class="c-line primary-bg-c2">c2</div>
                            <div class="c-line primary-bg-c3">c3</div>
                            <div class="c-line primary-bg-c4">c4</div>
                            <div class="c-line primary-bg-c5">c5</div>
                            <div class="c-line primary-bg-c6">c6</div>
                            <div class="c-line primary-bg-c7">c7</div>
                            <div class="c-line primary-bg-c8">c8</div>
                            <div class="c-line primary-bg-c9">c9</div>
                            <div class="c-line white-color primary-bg-c10">c10</div>
                            <div class="c-line white-color primary-bg-c11">c11</div>
                            <div class="c-line white-color primary-bg-c12">c12</div>
                        </div>
                    </div>
                </div>
                <p><strong class="h3">Social colors:</strong></p>
                <div class="row mb-4">
                    <div class="col-md-2 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-linkedin">#0177b5</div>
                        <span>$linkedin</span>
                    </div>
                    <div class="col-md-2 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-facebook">#4867aa</div>
                        <span>$facebook</span>
                    </div>
                    <div class="col-md-2 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-google">#ea4335</div>
                        <span>$google</span>
                    </div>
                    <div class="col-md-2 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-twitter">#32ccfe</div>
                        <span>$twitter</span>
                    </div>
                    <div class="col-md-2 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-youtube">#be1b1e</div>
                        <span>$youtube</span>
                    </div>
                    <div class="col-md-2 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-spid">#0066cc</div>
                        <span>$spid</span>
                    </div>
                </div>
                <hr>
                <p><strong class="h3">Components colors:</strong></p>
                <div class="row mb-4">
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-card-preference-bg">#eaf1eb</div>
                        <span>$card-preference-bg</span>
                    </div>
                    <div class="col-md-3 mt-4 mt-md-0 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-tooltip-bg">#5e7887</div>
                        <span>$tooltip-bg</span>
                    </div>
                </div>
                <hr>
                <p><strong class="h3">Text colors:</strong></p>
                <div class="row mb-4">
                    <div class="col-md-3 d-flex flex-column align-items-md-start align-items-center">
                        <div class="square p-3 design-theme-bg-text-muted">#435960</div>
                        <span>$text-muted-custom</span>
                    </div>
                </div>
            </section>


            <!-- allegati lista-->
            <section class="py-5">
                <h2 class="it-page-section" id="list-allegati">Allegati Lista</h2>
                <div class="bd-example">


                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-attachments-item',
                        [

                            'type' => 'xls',
                            'size' => '0',
                            'fileUrl' => 'url/del/documento',
                            'fileName' => 'nome_del_documento',

                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-attachments-item',
                        [

                            'type' => 'pdf',
                            'size' => '3502',
                            'fileUrl' => 'url/del/documento',
                            'fileName' => 'nome_del_documento',
                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-attachments-item',
                        [

                            'type' => 'doc',
                            'size' => '350343432',
                            'fileUrl' => 'url/del/documento',
                            'fileName' => 'nome_del_documento',
                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-attachments-item',
                        [

                            'type' => 'xxx',
                            'size' => '350',
                            'fileUrl' => 'url/del/documento',
                            'fileName' => 'aaaaaaaaasjaksjksjaksjadksjdksdjdkfjdkfjdfkdjfkdjfkdjfkdjfkdjfkdjfdkfjdkfjdkfjdkfjdkfjdkfjdfkjdfkdjfkdjfdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdfkdjfkdjfkdjfkdjfkdjfdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkfjdkjfdkfjdkfjdkfjdkfjdkfjdkjf',
                        ]
                    );
                    ?>
                </div>
            </section>

            <!--avatar card-->
            <section class="py-5 ">
                <h2 class="it-page-section" class="mb-3" id="avatar">Avatar</h2>
                <h3>Avatar size</h3>
                <p>Per scegliere la dimensione bisogna impostare la variabile <mark><code>avatarWrapperSize</code></mark></p>
                <p>Size: <mark><code>xs</code></mark>, <mark><code>sm</code></mark>, <mark><code>md</code></mark>, <mark><code>lg</code></mark> e <mark><code>xl</code></mark></p>
                <div class="bd-example">

                    <div class="row">
                        <div class="col-md-4 mb-4">

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                    'nameSurname' => 'Paolo Bianchi',
                                    'avatarWrapperSize' => 'xl',
                                ]
                            );
                            ?>
                        </div>
                        <div class="col-md-4 mb-4">

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                    'nameSurname' => 'Paolo Bianchi',
                                    'avatarWrapperSize' => 'lg'
                                ]
                            );
                            ?>
                        </div>
                        <div class="col-md-4 mb-4">

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                    'nameSurname' => 'Paolo Bianchi',
                                    'avatarWrapperSize' => 'md'
                                ]
                            );
                            ?>
                        </div>
                        <div class="col-md-4 mb-4">

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                    'nameSurname' => 'Paolo Bianchi',
                                    'avatarWrapperSize' => 'sm'
                                ]
                            );
                            ?>
                        </div>
                        <div class="col-md-4 mb-4">

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                                [
                                    'nameSurname' => 'Paolo Bianchi',
                                    'avatarWrapperSize' => 'xs'
                                ]
                            );
                            ?>
                        </div>
                    </div>
                </div>

                <!--avatar lista-->

                <h3>Avatar card</h3>
                <p>Le card avatar possono mostrare diverse informazioni: </p>
                <p><strong class="text-uppercase">Stato del profilo utente</strong> <small>(visibili solo per i size da <mark><code>md</code></mark> in su)</small>:<br><strong>Validato</strong> (di default è nascosto), <strong>Non validato</strong>, <strong>Modifica in corso</strong> e <strong>Da validare</strong> </p>
                <p><strong class="text-uppercase">Tipo di utente</strong> <small>(visibili solo per il size <mark><code>xl</code></mark>)</small>:<br><strong>Facilitatore</strong> e/o <strong>Community manager</strong>.</p>
                <div class="bd-example">
                    <div class="users-list-container d-flex justify-content-start flex-wrap ">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                            [
                                'nameSurname' => 'Mario Rossi',
                                'avatarWrapperSize' => 'xl',
                                'additionalClass' => 'border-bottom py-4',
                                'singleElementsWidthFullsize' => 'col-md-4',
                                'singleElementsWidthMobile' => 'col-xs-6',
                                'statoProfilo' => 'Validato',
                                'avatarPresence' => 'active',
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                            [
                                'nameSurname' => 'Mario Rossi',
                                'avatarWrapperSize' => 'xl',
                                'additionalClass' => 'border-bottom py-4',
                                'singleElementsWidthFullsize' => 'col-md-4',
                                'singleElementsWidthMobile' => 'col-xs-6',
                                'additionalInfo' => 'Consulente Marketing',
                                'statoProfilo' => 'Non validato',
                                'isFacilitator' => true
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                            [
                                'nameSurname' => 'Mario Rossi',
                                'avatarWrapperSize' => 'xl',
                                'additionalClass' => 'border-bottom py-4',
                                'singleElementsWidthFullsize' => 'col-md-4',
                                'singleElementsWidthMobile' => 'col-xs-6',
                                'additionalInfo' => 'Consulente Marketing',
                                'showCtaJoin' => 'true',
                                'ctaJoinMsg' => 'Invia messaggio',
                                'statoProfilo' => 'Modifica in corso',
                                'isFacilitator' => true,
                                'isCommunityManager' => true,
                                'avatarPresence' => 'active',
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar',
                            [
                                'nameSurname' => 'Massimiliano Giovannini',
                                'avatarWrapperSize' => 'xl',
                                'additionalClass' => 'border-bottom py-4',
                                'singleElementsWidthFullsize' => 'col-md-4',
                                'singleElementsWidthMobile' => 'col-xs-6',
                                'additionalInfo' => 'Consulente Marketing',
                                'showCtaMsg' => 'true',
                                'ctaMsg' => 'Collegati',
                                'statoProfilo' => 'Da validare'
                            ]
                        );
                        ?>

                    </div>
                </div>

                <!--avatar sovrapposti-->

                <h3>Avatar sovrapposti</h3>
                <p>Il listato può subire variazioni quali:</p>
                <p><strong class="text-uppercase">Status dell'utente</strong>in piattaforma settando <strong>showAvatarPresence</strong></p>
                <p><strong class="text-uppercase">Grandezza dell'avatar</strong> settando <strong>avatarWrapperSize</strong> a <mark><code>xs</code></mark>, <mark><code>sm</code></mark>, <mark><code>md</code></mark>, <mark><code>lg</code></mark> e <mark><code>xl</code></mark></p>
                <div class="bd-example">
                    <div class="row">
                        <div class="col-md-4">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-group-stacked',
                                [
                                    'numberExpose' => 4,
                                    'usersNumber' => 6,
                                    'userProfiles' => $userProfiles,
                                    'tooltipAdditionalInfoAvatarsGroup' => isset($tooltipAdditionalInfoAvatarsGroup) ?: Module::t(
                                        'amosdesign',
                                        'Partecipante'
                                    ),
                                    'removeLink' => false,
                                    'showAvatarPresence' => true,
                                ]
                            );
                            ?>
                        </div>

                        <div class="col-md-4">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-group-stacked',
                                [
                                    'numberExpose' => 4,
                                    'usersNumber' => 6,
                                    'userProfiles' => $userProfiles,
                                    'tooltipAdditionalInfoAvatarsGroup' => isset($tooltipAdditionalInfoAvatarsGroup) ?: Module::t(
                                        'amosdesign',
                                        'Partecipante'
                                    ),
                                    'removeLink' => false,
                                    'avatarWrapperSize' => 'md',
                                ]
                            );
                            ?>
                        </div>

                    </div>
                </div>
            </section>
            <?php /*?>
            <?php */ ?>
            <!--bottoni-->
            <section class="py-5">
                <div>
                    <!-- BUTTONS -->
                    <h2 class="it-page-section" id="bottoni" class="pb-2">Bottoni</h2>
                    <div class="bd-example bd-example-btn">
                        <button type="button" class="btn btn-primary">Primary</button>
                        <button type="button" class="btn btn-outline-primary">Primary outline</button>
                        <button type="button" class="btn btn-secondary">Secondary</button>
                        <button type="button" class="btn btn-outline-secondary">Secondary outline</button>
                        <button type="button" class="btn btn-tertiary">Tertiary</button>
                        <button type="button" class="btn btn-outline-tertiary">Tertiary outline</button>
                        <button type="button" class="btn btn-primary disabled">Button disabled</button>
                        <button type="button" class="btn btn-primary disabled disabled-with-pointer-events">Button disabled with pointer events</button>
                        <button type="button" class="btn btn-link">Link</button>
                    </div>

                </div>
            </section>

            <!--carousel-->
            <section class="py-5">
                <div>
                    <h2 class="it-page-section" id="carousel" class="pb-2">Carousel</h2>
                </div>
                <div class="bd-example">
                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/carousel'
                    );
                    ?>
                </div>
            </section>

            <!--carousel calendario-->
            <section class="py-5">
                <h2 class="it-page-section" id="calendar-carousel" class="mb-5">Carousel Calendario</h2>
                <div class="bd-example">
                    <!-- 2 variabili: ognuna per dire quale è la label attivo/disattivo -->
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-calendar-carousel',
                        [
                            //array con i filtri che voglio impostare
                            'filterList' => ['Tutto', 'Consigli-comunali', 'Eventi', 'Scadenze'],
                        ]
                    );
                    ?>
                </div>
            </section>
            <!--checkbox collapse-->
            <section class="py-5">
                <div>

                    <h2 id="checkbox-collapse">Checkbox collapse</h2>
                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/form-checkbox-collapse-preference'
                    );
                    ?>
                </div>
            </section>

            <!--checkbox link privacy-->
            <section class="py-5 border-bottom">
                <h2 id="Checkbox-link">Checkbox con link privacy</h2>
                <?=
                $this->render(
                    '@vendor/open20/design/src/components/bootstrapitalia/views/check-with-link',
                    [
                        'externalLink' => false,
                        'linkText' => 'link esterno alla privacy',
                        'privacyLink' => '/preferenceuser/contacts/contacts',
                        'target' => '_blank',
                    ]
                );
                ?>
            </section>

            <!--collapse-->
            <section class="py-5">
                <div>

                    <h2 class="it-page-section" id="collapse" class="pb-2">Collapse</h2>
                    <div class="bd-example">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/collapse-preference'
                        );
                        ?>

                    </div>
                </div>
            </section>

            <!--news-->
            <?php if (\Yii::$app->getModule('news')) : ?>
                <section class="py-5 border-bottom">
                    <h2 id="news">News</h2>

                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                        [
                            'category' => 'ULTIME DALLA P.A.',
                            'image' => 'https://picsum.photos/1600/900?random=6',
                            'nameSurname' => 'Paolo Bianchi',
                            'showAvatar' => true,
                            'newPubblication' => true,
                            'avatarWrapperSize' => 'lg',
                            'categorySize' => 'lead',
                            'titleSize' => 'h3',
                            'descriptionSize' => 'lead',
                            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit…',
                            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'date' => '2020-12-12'
                        ]
                    );
                    ?>
                </section>
            <?php endif; ?>



            <?php if (\Yii::$app->getModule('news')) : ?>
                <section class="py-5 border-bottom">
                    <h2 id="news-list-item">Listato News</h2>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
                        [
                            'category' => 'ULTIME DALLA P.A.',
                            'image' => 'https://picsum.photos/1600/900?random=21',
                            'nameSurname' => 'Paolo Bianchi',
                            'showSignature' => true,
                            'newPubblication' => true,
                            'titleSize' => 'h5',
                            'title' => 'Lorem ipsum dolor sit amet',
                            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'date' => '2020-12-12'

                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
                        [
                            'category' => 'ULTIME DALLA P.A.',
                            'image' => 'https://picsum.photos/1600/900?random=22',
                            'nameSurname' => 'Paolo Bianchi',
                            'showSignature' => true,
                            'titleSize' => 'h5',
                            'title' => 'Lorem ipsum dolor sit amet',
                            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'date' => '2020-12-12'

                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
                        [
                            'category' => 'ULTIME DALLA P.A.',
                            'image' => 'https://picsum.photos/1600/900?random=2',
                            'nameSurname' => 'Paolo Bianchi',
                            'showSignature' => true,
                            'newPubblication' => true,
                            'titleSize' => 'h5',
                            'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                            'description' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare or incididunt ut laboree',
                            'date' => '2020-12-12'

                        ]
                    );
                    ?>
                </section>
            <?php endif; ?>


            <!--Community card-->
            <?php if (\Yii::$app->getModule('community')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="Community-card">Community Card</h2>
                    <p>Le card community mostrano diverse informazioni: </p>
                    <p><strong class="text-uppercase">Tipo di community</strong></strong> <small>(visibile per tutte le size)</small>:<br><strong>Aperta</strong> (isOpenCommunity=>true), <strong>Privata </strong>(isClosedCommunity=>true), <strong>Riservata</strong>(isPrivateCommunity=>true)</p>
                    <p><strong class="text-uppercase">Data di iscrizione</strong> <small>(visibile solo per gli utenti iscritti alla community)</small></p>
                    <div class="bd-example">
                        <div class="row">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-community',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=' . $i,
                                    'isClosedCommunity' => true,
                                    'dateSigned' => '2020-01-01',
                                    'title' => 'Nome community',
                                    'isSigned' => true,
                                    'widthColumn' => 'col-lg-4' . ' col-md-6' . ' col-sm-12'
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-community',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=' . $i,
                                    'isOpenCommunity' => true,
                                    'dateSigned' => '2020-02-28',
                                    'title' => 'Nome community lungo lunghisssimissimo lunghisssimissimo lunghisssimissimo lunghisssimissimo ',
                                    'isSigned' => true,
                                    'widthColumn' => 'col-lg-4' . ' col-md-6' . ' col-sm-12'

                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-community',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=' . $i,
                                    'isPrivateCommunity' => true,
                                    'title' => 'Nome community',


                                    'isSigned' => false,
                                    'widthColumn' => 'col-lg-4' . ' col-md-6' . ' col-sm-12'

                                ]
                            );
                            ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!--discussioni card-->
            <?php if (\Yii::$app->getModule('discussioni')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="discussioni-card">Discussioni card</h2>
                    <p>Le card delle discussioni mostrano diverse informazioni:</p>
                    <p><strong class="text-uppercase">Data</strong>: attraverso la variabile <mark><code>$date</code></mark></p>
                    <p><strong class="text-uppercase">Descrizione</strong>: attraverso la variabile <mark><code>$description</code></mark></p>
                    <p><strong class="text-uppercase">Community di riferimento</strong>: attraverso la variabile <mark><code>$communityTitle</code></mark></p>

                    <div class="bd-example">
                        <div class="row variable-gutters">
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions-card',
                                    [
                                        'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                        'communityTitle' => 'Planner 2.0',
                                        'date' => '2020-12-12',
                                        'url' => '#',
                                        'numbersOfAnswer' => '3',
                                        'numbersOfVisits' => '4',
                                        'updatedAt' => '2012-12-12',
                                        'image' => 'https://picsum.photos/1600/900?random=5',
                                        'nameSurname' => 'Paolo Bianchi',
                                        'newPubblication' => true,
                                        'avatarWrapperSize' => 'md',
                                        'description' => 'La perdita di biodiversità e il commercio delle specie selvatiche sono le cause della pandemia.',
                                        'nameSurnameLastComment' => 'Marco Giusti',
                                        'lastCommentDiscussion' => 'Ciao, mi sembra un ottimo spunto, in Commissione stiamo già lavorando da qualche mese ad una proposta per le imprese green, vi terremo aggiornati.',
                                    ]
                                );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions-card',
                                    [
                                        'title' => 'Discussione Planner',
                                        'date' => '2020-12-12',
                                        'url' => '#',
                                        'numbersOfAnswer' => '3',
                                        'numbersOfVisits' => '4',
                                        'updatedAt' => '2012-12-12',
                                        'image' => 'https://picsum.photos/1600/900?random=4',
                                        'nameSurname' => 'Paolo Bianchi',
                                        'newPubblication' => true,
                                        'avatarWrapperSize' => 'md',
                                        'description' => 'La perdita di biodiversità e il commercio delle specie selvatiche sono le cause della pandemia.
                                            Il rischio che le malattie siano trasmesse all’uomo è in crescita come risultato dell’avanzamento della distruzione di habitat prima intatti e della crescita del commercio (spesso illegale) di specie selvatiche.
                                            Quali lezioni impariamo dalla crisi avviata dal Coronavirus per una nuova strategia a favore della biodiversità e per la riduzione del diffondersi delle epidemie?'
                                    ]
                                );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions-card',
                                    [
                                        'title' => 'Discussione con nome un pò più lungo e complesso',
                                        'communityTitle' => 'Planner 2.0 ma con nome più lungo',
                                        'date' => '2020-12-12',
                                        'url' => '#',
                                        'numbersOfAnswer' => '3',
                                        'numbersOfVisits' => '4',
                                        'updatedAt' => '2012-12-12',
                                        'image' => 'https://picsum.photos/1600/900?random=3',
                                        'nameSurname' => 'Paolo Bianchi',
                                        'avatarWrapperSize' => 'md',
                                        'nameSurnameLastComment' => 'Giorgio Mariano',
                                        'lastCommentDiscussion' => 'Wow! Mi sembra un ottimo spunto, in Commissione stiamo già lavorando da qualche mese ad una proposta per le imprese green, vi terremo aggiornati.',
                                        'description' => 'La perdita di biodiversità e il commercio delle specie selvatiche sono le cause della pandemia.
                                            Il rischio che le malattie siano trasmesse all’uomo è in crescita come risultato dell’avanzamento della distruzione di habitat prima intatti e della crescita del commercio (spesso illegale) di specie selvatiche.
                                            Quali lezioni impariamo dalla crisi avviata dal Coronavirus per una nuova strategia a favore della biodiversità e per la riduzione del diffondersi delle epidemie?'
                                    ]
                                );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions-card',
                                    [
                                        'title' => 'Discussione Planner',
                                        'communityTitle' => 'Planner 2.0 ma con nome più lungo',
                                        'date' => '2020-12-12',
                                        'url' => '#',
                                        'numbersOfAnswer' => '3',
                                        'numbersOfVisits' => '4',
                                        'updatedAt' => '2012-12-12',
                                        'image' => 'https://picsum.photos/1600/900?random=2',
                                        'nameSurname' => 'Paolo Bianchi',
                                        'newPubblication' => true,
                                        'avatarWrapperSize' => 'md',
                                        'nameSurnameLastComment' => 'Luisa Recca',
                                        'lastCommentDiscussion' => 'Grande! Mi sembra un ottimo spunto, in Commissione stiamo già lavorando da qualche mese ad una proposta per le imprese green, vi terremo aggiornati.',
                                        'description' => 'Sono una descrizione... obbligatoria?'
                                    ]
                                );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions-card',
                                    [
                                        'title' => 'Discussione Planner',
                                        'communityTitle' => 'Planner 2.0',
                                        'date' => '2020-12-12',
                                        'url' => '#',
                                        'numbersOfAnswer' => '3',
                                        'numbersOfVisits' => '4',
                                        'updatedAt' => '2012-12-12',
                                        'image' => 'https://picsum.photos/1600/900?random=1',
                                        'nameSurname' => 'Paolo Bianchi',
                                        'newPubblication' => true,
                                        'avatarWrapperSize' => 'md',
                                        'nameSurnameLastComment' => 'Mario Franchi',
                                        'lastCommentDiscussion' => 'Esatto! Mi sembra un ottimo spunto, in Commissione stiamo già lavorando da qualche mese ad una proposta per le imprese green, vi terremo aggiornati.',
                                        'description' => 'La perdita di biodiversità e il commercio delle specie selvatiche sono le cause della pandemia.
                    Il rischio che le malattie siano trasmesse all’uomo è in crescita come risultato dell’avanzamento della distruzione di habitat prima intatti e della crescita del commercio (spesso illegale) di specie selvatiche.
                    Quali lezioni impariamo dalla crisi avviata dal Coronavirus per una nuova strategia a favore della biodiversità e per la riduzione del diffondersi delle epidemie?'
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!--discussioni lista-->
            <?php if (\Yii::$app->getModule('discussioni')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="discussioni">Discussioni lista</h2>
                    <p>Gli elementi della lista discussioni mostrano diverse informazioni (oltre a quelle delle card):</p>
                    <p><strong class="text-uppercase">Numero di risposte alla discussione</strong>: attraverso la variabile <mark><code>$numbersOfAnswer</code></mark></p>
                    <p><strong class="text-uppercase">Numero di visite alla discussione</strong>: attraverso la variabile <mark><code>$numbersOfVisits</code></mark></p>
                    <p><strong class="text-uppercase">Data di ultimo aggiornamento</strong>: attraverso la variabile <mark><code>$updatedAt</code></mark></p>

                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions',
                            [
                                'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'date' => '2020-12-12',
                                'url' => '#',
                                'numbersOfAnswer' => '3',
                                'numbersOfVisits' => '4',
                                'updatedAt' => '2012-12-12',
                                'newPubblication' => true,
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions',
                            [
                                'title' => 'Discussione Planner',
                                'communityTitle' => 'Planner 2.0 ma con nome più lungo',
                                'date' => '2020-12-12',
                                'url' => '#',
                                'numbersOfAnswer' => '3',
                                'numbersOfVisits' => '4',
                                'updatedAt' => '2012-12-12',
                                'newPubblication' => true,
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-discussions',
                            [
                                'title' => 'Discussione Planner',
                                'communityTitle' => 'Planner 2.0 ma con nome più lungo',
                                'date' => '2020-12-12',
                                'url' => '#',
                                'numbersOfAnswer' => '3',
                                'numbersOfVisits' => '4',
                                'updatedAt' => '2012-12-12',
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--documenti card-->
            <?php if (\Yii::$app->getModule('documenti')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="documents-card">Documenti card</h2>
                    <p>Le card dei documenti mostrano diverse informazioni:</p>
                    <p><strong class="text-uppercase">Estensione del file</strong>: attraverso la variabile <mark><code>$type</code></mark>(che può definire anche una cartella)</p>
                    <p><strong class="text-uppercase">Peso del file</strong>: attraverso la variabile <mark><code>$size</code></mark></p>
                    <p><strong class="text-uppercase">Informazioni aggiuntive</strong>: racchiuse in un tooltip e gestiste attraverso le variabili <mark><code>$nameSurname</code></mark>, <mark><code>$nameFile</code></mark>, <mark><code>$category</code></mark>, <mark><code>$community</code></mark></p>

                    <div class="bd-example">
                        <div class="row variable-gutters">


                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',
                                [
                                    'title' => '2020 Verso la Strategia Regionale per lo Sviluppo Sostenibile',
                                    'fileName' => 'nome_del_file.doc',
                                    'fileUrl' => 'url/di/esempio',
                                    'type' => 'doc',
                                    'description' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                    'nameSurname' => 'Mario Rossi',
                                    'nameFile' => 'nome_esempio_file',
                                    'size' => '300',
                                    'category' => 'Presentazioni e materiale illustrativo',
                                    'community' => 'Gruppo 6 UniBS Crescini',
                                    'dateSyncDrive' => '2012-12-27 12:30:00',
                                    'newPubblication' => true,
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',
                                [
                                    'title' => 'ARIA S.p.A., genesi e orizzonti',
                                    'fileName' => 'nome_del_file.zip',
                                    'fileUrl' => 'url/di/esempio',
                                    'type' => 'zip',
                                    'description' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                    'nameSurname' => 'Mario Rossi',
                                    'nameFile' => 'nome_esempio_file',
                                    'size' => '300',
                                    'category' => 'Presentazioni e materiale illustrativo',
                                    'community' => 'Gruppo 6 UniBS Crescini',
                                    'versionFile' => '500',
                                    'newPubblication' => true,
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',
                                [
                                    'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                    'fileName' => 'nome_del_file.jpg',
                                    'fileUrl' => 'url/di/esempio',
                                    'type' => 'jpg',
                                    'description' => 'io sono una stringa',
                                    'nameSurname' => 'Mario Rossi',
                                    'nameFile' => 'nome_esempio_file',
                                    'size' => '300',
                                    'category' => 'Presentazioni e materiale illustrativo',
                                    'community' => 'Gruppo 6 UniBS Crescini',
                                    'versionFile' => '1',
                                    'allegatiNum' => '2',
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',
                                [
                                    'title' => 'Documentazione (Rapporto Preliminare VAS, Documento di Orientamento del PPSSC, altro materiale)',
                                    'fileUrl' => 'url/di/esempio',
                                    'typeFolder' => 'folder',
                                    'nameSurname' => 'Mario Rossi',
                                    'nameFile' => 'nome_esempio_file',
                                    'community' => 'Gruppo 6 UniBS Crescini',
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',
                                [
                                    'title' => 'Verbale seduta di insediamento',
                                    'fileName' => 'nome_del_file.xls',
                                    'fileUrl' => 'url/di/esempio',
                                    'type' => 'xls',
                                    'description' => 'io sono una stringa',
                                    'nameSurname' => 'Mario Rossi',
                                    'nameFile' => 'nome_esempio_file',
                                    'size' => '300',
                                    'category' => 'Presentazioni e materiale illustrativo',
                                    'community' => 'Gruppo 6 UniBS Crescini',
                                    // 'numberAttachments' => '4'
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',
                                [
                                    'title' => 'Guida per i partecipanti - Community Primo Forum pubblico procedura di VAS',
                                    'fileUrl' => 'url/di/esempio',
                                    'description' => 'io sono una stringa',
                                    'nameSurname' => 'Mario Rossi',
                                    'category' => 'Presentazioni e materiale',
                                    'community' => 'Gruppo 6 UniBS Crescini',
                                    'nameFile' => 'nome_esempio_file',
                                ]
                            );
                            ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!--documenti lista-->
            <?php if (\Yii::$app->getModule('documenti')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="document-list">Documenti lista</h2>

                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
                            [
                                'date' => '10-01-2021',
                                'description' => 'Lorem ipsum dolor sit amet ipsum dolor sit amet ipsum dolor sit amet',
                                'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'category' => 'Categoria di test',
                                'image' => 'https://picsum.photos/1600/900?random=25',
                                'type' => 'xls',
                                'size' => '350',
                                'nameSurname' => 'Mario Rossi',
                                'community' => 'Community Common',
                                'url' => 'url/del/dettaglio',
                                'fileUrl' => 'url/del/documento',
                                'fileName' => 'nome_del_documento.pdf',
                                'newPubblication' => true,

                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
                            [
                                'date' => '12-12-2020',
                                'description' => 'Lorem ipsum dolor sit amet ipsum dolor sit amet ipsum dolor sit amet',
                                'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'category' => 'Categoria di test',
                                'image' => 'https://picsum.photos/1600/900?random=25',
                                'type' => 'pdf',
                                'size' => '350',
                                'nameSurname' => 'Mario Rossi',
                                'community' => 'Community Common',
                                'url' => 'url/del/dettaglio',
                                'fileUrl' => 'url/del/documento',
                                'fileName' => 'nome_del_documento.pdf',
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-item',
                            [
                                'date' => '12-12-2020',
                                'description' => 'Lorem ipsum dolor sit amet ipsum dolor sit amet ipsum dolor sit amet',
                                'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'category' => 'Categoria di test',
                                'image' => 'https://picsum.photos/1600/900?random=25',
                                'type' => 'pdf',
                                'size' => '350',
                                'nameSurname' => 'Mario Rossi',
                                'community' => 'Community Common',
                                'url' => 'url/del/dettaglio',
                                'fileUrl' => 'url/del/documento',
                                'fileName' => 'nome_del_documento.pdf',
                                'dateSyncDrive' => '2012-12-27 12:30:00',
                            ]
                        );

                        ?>
                    </div>
                </section>
            <?php endif; ?>


            <!-- eventi card-->
            <?php if (\Yii::$app->getModule('events')) : ?>
                <section class="pt-5 ">
                    <h2 class="it-page-section" id="events-list">Eventi Card</h2>
                    <p>Le card dei documenti mostrano diverse informazioni:</p>
                    <p><strong class="text-uppercase">Data inizio</strong>: attraverso la variabile <mark><code>$dateHourStart</code></mark></p>
                    <p><strong class="text-uppercase">Tipo evento</strong>: attraverso la variabile <mark><code>$type</code></mark></p>

                    <div class="bd-example">
                        <div class="row">
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event',
                                    [
                                        'dateHourStart' => '2012-12-27 12:30:00',
                                        'title' => 'Titolo dell\'evento',
                                        'summary' => 'con Mario Rossi, Sandro Verdi e Nicoletta Bianchi',
                                        'url' => '/url/di/test',
                                        'urlCta' => '/url/di/event-signup',
                                        'type' => 'Evento typeDefault',
                                        'isPaid' => false,
                                        'availableSeats' => '48',
                                        'newPubblication' => true,
                                        'dateSigned' => '2020-01-01',

                                    ]
                                );
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event',
                                    [
                                        'dateHourStart' => '2012-12-27 12:30:00',
                                        'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                        'summary' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
                                        'url' => '/url/di/test',
                                        'urlCta' => '/url/di/view',
                                        'type' => 'Evento typeDefault',
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- eventi lista-->
            <?php if (\Yii::$app->getModule('events')) : ?>
                <section class="pt-5">
                    <h2 class="it-page-section" id="events-list">Eventi Lista</h2>
                    <div class="bd-example">
                        <div class="event-list-container">
                            <div class=" row flex-wrap">
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event-item',
                                    [
                                        'dateHourStart' => '2012-12-12 12:00:00',
                                        'dateHourEnd' => '2012-12-13 15:00:00',
                                        'title' => 'La comunicazione green ai tempi del covid',
                                        'type' => 'Tipo evento',
                                        'newPubblication' => true,
                                    ]
                                );
                                ?>
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event-item',
                                    [
                                        'dateHourStart' => '2012-12-12 12:00:00',
                                        'dateHourEnd' => '2012-12-12 15:00:00',
                                        'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                        'type' => 'Tipo evento',
                                        'isPaid' => false,
                                        'availableSeats' => '48',
                                        'newPubblication' => true,
                                    ]
                                );
                                ?>
                                <?=
                                $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-event-item',
                                    [
                                        'dateHourStart' => '2012-12-12 12:00:00',
                                        'title' => 'io sono una stringa da pochi caratteri',
                                        'type' => 'Tipo evento',
                                        'isPaid' => true,
                                        'availableSeats' => '48',
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- eventi scope-->
            <?php if (\Yii::$app->getModule('events')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="events-scope">Eventi scope</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-scope-eventi',
                            [
                                'dateHourStart' => '2012-12-27 12:30:00',
                                'dateHourEnd' => '2012-12-27 12:30:00',
                                'title' => 'Tavola rotonda sull\'inquinamento nei mari',
                                'summary' => 'con Mario Rossi, Sandro Verdi e Nicoletta Bianchi',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a tempor magna. Aliquam consectetur tellus et posuere vestibulum. Suspendisse euismod eros est, id rutrum felis cursus at. Duis turpis urna, laoreet quis malesuada rutrum, lobortis nec diam. Phasellus ut nisl enim.',
                                'url' => '/url/di/test',
                                'urlCta' => '/url/di/view',
                                'type' => 'Evento typeDefault',
                                'isPaid' => true,
                                'availableSeats' => '48',
                                'location' => 'Palazzo Roverella',
                                'address' => 'via della pioppa, 30',
                                'city' => 'Milano',
                                'province' => 'MI',
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (\Yii::$app->getModule('ticket')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="faq-list">FAQs list</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-faq-item-list',
                            [
                                'faqId' => 1,
                                'faqCatId' => 2,
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--modali-->
            <section class="py-5">
                <div>
                    <h2 class="it-page-section" id="modal" class="pb-2">Modali</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        Bottone per modale generica
                    </button>

                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/modal_validation'
                    );
                    ?>
                </div>
            </section>
            <!--news-->
            <?php if (\Yii::$app->getModule('news')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="news">News</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                            [
                                'category' => 'ULTIME DALLA P.A.',
                                'image' => 'https://picsum.photos/1600/900?random=6',
                                'nameSurname' => 'Paolo Bianchi',
                                'showAvatar' => true,
                                'newPubblication' => true,
                                'avatarWrapperSize' => 'lg',
                                'categorySize' => 'lead',
                                'titleSize' => 'h3',
                                'descriptionSize' => 'lead',
                                'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit…',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'date' => '2020-12-12'
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--news card-->
            <?php if (\Yii::$app->getModule('news')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="news-list">News card</h2>
                    <div class="bd-example">
                        <div class="list-news-container row variable-gutters">

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                                [
                                    'category' => 'ULTIME DALLA P.A.',
                                    'image' => 'https://picsum.photos/1600/900?random=2',
                                    'nameSurname' => 'Paolo Bianchi',
                                    'newPubblication' => true,
                                    'showAvatar' => true,
                                    'widthColumn' => 'col-lg-4 col-md-6 col-sm-12',
                                    //classi per la dimensione font/avatar
                                    'avatarWrapperSize' => 'md',

                                    'descriptionSize' => 'small',
                                    'title' => 'Lorem ipsum dolor sit amet',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'date' => '2020-12-12'

                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                                [
                                    'category' => 'ULTIME DALLA P.A.',
                                    'image' => 'https://picsum.photos/1600/900?random=3',
                                    'nameSurname' => 'Paolo Bianchi',

                                    'showAvatar' => true,
                                    'widthColumn' => 'col-lg-4 col-md-6 col-sm-12',
                                    'avatarWrapperSize' => 'md',
                                    'descriptionSize' => 'small',
                                    'title' => 'Lorem ipsum dolor sit amet',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'date' => '2020-12-12'
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                                [
                                    'category' => 'ULTIME DALLA P.A.',
                                    'image' => 'https://picsum.photos/1600/900?random=4',
                                    'nameSurname' => 'Paolo Bianchi',
                                    'newPubblication' => true,
                                    'showAvatar' => true,
                                    'widthColumn' => 'col-lg-4 col-md-6 col-sm-12',
                                    'avatarWrapperSize' => 'md',
                                    'descriptionSize' => 'small',
                                    'title' => 'Lorem ipsum dolor sit amet',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'date' => '2020-12-12'
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                                [
                                    'category' => 'ULTIME DALLA P.A.',
                                    'image' => 'https://picsum.photos/1600/900?random=44',
                                    'nameSurname' => 'Paolo Bianchi',

                                    'showAvatar' => true,
                                    'widthColumn' => 'col-lg-4 col-md-6 col-sm-12',
                                    //classi per la dimensione font/avatar
                                    'avatarWrapperSize' => 'md',
                                    'descriptionSize' => 'small',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'title' => 'Lorem ipsum dolor sit amet',
                                    'date' => '2020-12-12'
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
                                [
                                    'category' => 'ULTIME DALLA P.A.',
                                    'image' => 'https://picsum.photos/1600/900?random=55',
                                    'nameSurname' => 'Paolo Bianchi',

                                    'showAvatar' => true,
                                    'widthColumn' => 'col-lg-4 col-md-6 col-sm-12',
                                    'avatarWrapperSize' => 'md',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                    'descriptionSize' => 'small',
                                    'title' => 'Lorem ipsum dolor sit amet',
                                    'date' => '2020-12-12'
                                ]
                            );
                            ?>
                        </div>
                </section>
            <?php endif; ?>

            <!--News Carousel-->
            <?php if (\Yii::$app->getModule('news')) : ?>
                <section class="py-5">
                    <div>
                        <h2 class="it-page-section" id="carousel-news" class="pb-2">News Carousel</h2>
                    </div>
                    <div class="bd-example">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-carousel-news'
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--news lista-->
            <?php if (\Yii::$app->getModule('news')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="news-list-item">News lista</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
                            [
                                'category' => 'ULTIME DALLA P.A.',
                                'image' => 'https://picsum.photos/1600/900?random=21',
                                'nameSurname' => 'Paolo Bianchi',
                                'showSignature' => true,
                                'newPubblication' => true,
                                'titleSize' => 'h5',
                                'title' => 'Lorem ipsum dolor sit amet',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'date' => '2020-12-12'
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
                            [
                                'category' => 'ULTIME DALLA P.A.',
                                'image' => 'https://picsum.photos/1600/900?random=22',
                                'nameSurname' => 'Paolo Bianchi',
                                'showSignature' => true,
                                'titleSize' => 'h5',
                                'title' => 'Lorem ipsum dolor sit amet',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'date' => '2020-12-12'
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news-item-list',
                            [
                                'category' => 'ULTIME DALLA P.A.',
                                'image' => 'https://picsum.photos/1600/900?random=2',
                                'nameSurname' => 'Paolo Bianchi',
                                'showSignature' => true,
                                'newPubblication' => true,
                                'titleSize' => 'h5',
                                'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'description' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare or incididunt ut laboree',
                                'date' => '2020-12-12'
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--Organizzazioni card-->
            <?php if (\Yii::$app->getModule('organizzazioni')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="organization">Organizzazioni card</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
                            [
                                'image' => 'https://picsum.photos/1600/900?random=4',
                                'title' => 'Pesce Azzurro Cefalù',
                                'removeLink' => true,
                                'showCta' => true,
                                'showIcon' => true,
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--organizzazioni lista-->
            <?php if (\Yii::$app->getModule('organizzazioni')) : ?>
                <section class="py-5 ">
                    <h2 class="it-page-section" id="organization-list">Organizzazioni lista</h2>
                    <div class="bd-example">
                        <div class="row variable-gutters users-list-container justify-content-start">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=1',
                                    'title' => 'Fidagel',
                                ]
                            );
                            ?>

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=2',
                                    'title' => 'Pesce Azzurro Cefalù',
                                ]
                            );
                            ?>

                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-organizzazioni',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=3',
                                    'title' => 'Ittica Taddeo Lucia & Co',
                                ]
                            );
                            ?>
                        </div>

                    </div>
                </section>
            <?php endif; ?>

            <!--Organizzazioni Scope-->
            <?php if (\Yii::$app->getModule('organizzazioni')) : ?>
                <section class="py-5 ">
                    <h2 class="it-page-section" id="scope-organizzazioni">Organizzazioni scope</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-scope-organizzazioni',
                            [
                                'espositoreImage' => 'https://picsum.photos/1600/900?random=10',
                                'espositoreTitle' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'espositoreSito' => 'www.dragoconserve.it',
                                'espositorePIva' => '012345678912',
                                'espositoreReferente' => 'Giuliano Bianco',
                                'espositoreIndirizzo' => 'Via Stentiello 10/a Siracusa',
                                'espositoreEmail' => 'info@dragoconserve.it',
                                'espositoreTelefono' => '090 1234567',
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--Podcast lista-->
            <?php if (\Yii::$app->getModule('podcast')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="podcast-list">Podcast lista</h2>
                    <div class="bd-example">
                        <div class="d-flex">
                            <div class="podcast-list-container col-12">
                                <div class=" row flex-wrap">
                                    <?=
                                    $this->render(
                                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-podcast',
                                        [
                                            'lastEpisodeDate' => '25 Settembre 2020',
                                            'podcastDescription' => 'Lorem ipsum dolor sit amet',
                                            'podcastName' => 'La comunicazione green ',
                                            'podcastCategory' => 'Categoria',
                                            'podcastCover' => 'https://picsum.photos/1600/900?random=25',

                                        ]
                                    );
                                    ?>
                                    <?=
                                    $this->render(
                                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-podcast',
                                        [
                                            'lastEpisodeDate' => '25 Settembre 2020',

                                            'podcastName' => 'io sono una stringa cortina ',
                                            'podcastCategory' => 'informatica',
                                            'podcastCover' => 'https://picsum.photos/1600/900?random=55',

                                        ]
                                    );
                                    ?>
                                    <?=
                                    $this->render(
                                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-podcast',
                                        [
                                            'lastEpisodeDate' => '25 Luglio 2020',
                                            'podcastDescription' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dui eros, feugiat at scelerisque sed, ultrices eget ex.  ',
                                            'podcastName' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                            'podcastCategory' => 'innovazione',
                                            'podcastCover' => 'https://picsum.photos/1600/900?random=15',

                                        ]
                                    );
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!--podcast card-->
            <?php if (\Yii::$app->getModule('podcast')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="podcast-puntate-list">Podcast card </h2>
                    <div class="bd-example">
                        <div class="d-flex">
                            <div class="event-list-container">
                                <div class=" row flex-wrap">



                                    <?=
                                    $this->render(
                                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-podcast-puntata',
                                        [
                                            'podcastCover' => 'https://picsum.photos/1600/900?random=45',
                                            'podcastName' => 'io sono una stringa cortina ',
                                            'podcastDescription' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum dui eros, feugiat at scelerisque sed, ultrices eget ex. Nunc a tortor et eros iaculis tincidunt at hendrerit dolor. ',
                                            'episodeDate' => '25 Settembre 2020',
                                            'episodeDuration' => '35',
                                        ]
                                    );
                                    ?>

                                    <?=
                                    $this->render(
                                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-podcast-puntata',
                                        [
                                            'podcastCover' => 'https://picsum.photos/1600/900?random=46',
                                            'podcastName' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                            'podcastDescription' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                            'episodeDate' => '25 Settembre 2020',

                                        ]
                                    );
                                    ?>


                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>


            <!--proposte di collaborazione-->
            <?php if (\Yii::$app->getModule('partnershipprofiles')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="proposte-collaborazione">Proposte di collaborazione</h2>
                    <div class="bd-example">
                        finchè Michele non toglie il model, commento la sezione sennò la pagina è irraggiungibile
                        <!-- < ?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-proposte-collaborazione',
                            [
                                'model' => \open20\amos\partnershipprofiles\models\PartnershipProfiles::findOne(293),
                                'url'=> '#',
                                'title' => 'io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'shortTitle' => 'io sono una stringa cortina',
                                'expireDate' => '10/04/2022',
                                'publishedAt' => '09/06/2021',
                                'pubblicationRule' => 'Tutti',
                                'status' => 'Attivo',

                            ]
                        );
                        ?> -->


                    </div>
                </section>
            <?php endif; ?>

            <!--proposte di collaborazione een-->
            <?php if (\Yii::$app->getModule('een')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="proposte-collaborazione-een">Proposte di collaborazione EEN</h2>
                    <div class="bd-example">

                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-proposte-collaborazione-een',
                            [
                                'dateEnd' => '12-12-2021',
                                'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'newPubblication' => true,
                                'description' => 'La comunicazione può essere strumento di intervento per attenuare o eliminare lo stress in ambito lavorativo. La comunicazione può avere un impatto positivo prima, durante e dopo che il rischio stress si verifichi.',
                                'type' => 'Technology Request',
                                'country' => 'Italia',
                                'dateUpdate' => '12-02-2021',
                                'dateSubmit' => '12-02-2021',
                                'idProposta' => 'TRCZ20200806001'
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-proposte-collaborazione-een',
                            [
                                'dateEnd' => '12-12-2021',
                                'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'newPubblication' => true,
                                'description' => 'La comunicazione può essere strumento di intervento per attenuare o eliminare lo stress in ambito lavorativo. La comunicazione può avere un impatto positivo prima, durante e dopo che il rischio stress si verifichi.',
                                'type' => 'Technology Request',
                                'country' => 'Czech',
                                'dateUpdate' => '12-02-2021',
                                'dateSubmit' => '12-02-2021',
                                'idProposta' => 'TRCZ20200806001'
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-proposte-collaborazione-een',
                            [
                                'dateEnd' => '12-12-2021',
                                'title' => 'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
                                'description' => 'La comunicazione può essere strumento di intervento per attenuare o eliminare lo stress in ambito lavorativo. La comunicazione può avere un impatto positivo prima, durante e dopo che il rischio stress si verifichi.',
                                'type' => 'Technology Request',
                                'country' => 'Poland',
                                'dateUpdate' => '12-02-2021',
                                'dateSubmit' => '12-02-2021',
                                'idProposta' => 'TRCZ20200806001'
                            ]
                        );
                        ?>

                    </div>
                </section>
            <?php endif; ?>



            <!--progetti-->
            <?php /*
            <?php if(\Yii::$app->getModule('news')): ?>
            */ ?>
            <section class="py-5">
                <h2 class="it-page-section" id="news">Progetti</h2>
                <div class="bd-example">
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-project',
                        [
                            'category' => 'ULTIME DALLA P.A.',
                            'actionModify' => '#',
                            'actionDelete' => '#',

                            'newPublication' => true,

                            'categorySize' => 'lead',
                            'titleSize' => 'h3',
                            'descriptionSize' => 'lead',
                            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit…',
                            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',

                        ]
                    );
                    ?>
                </div>
            </section>
            <?php /*
            <?php endif; ?>
            */ ?>




            <!--risultati di ricerca lista-->
            <section class="py-5">

                <h2 class="it-page-section" id="search-results-item">Risultati Ricerca lista</h2>
                <div class="bd-example">
                    <div class="it-list-wrapper list-search">
                        <ul class="it-list">
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-search-results-item',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=2',
                                    'type' => 'news',
                                    'title' => 'Lorem ipsum dolor sit amet, consectetur ricerca 1',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-search-results-item',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=22',
                                    'type' => 'type',
                                    'title' => 'Lorem ipsum dolor sit amet, consectetur ricerca 2',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                ]
                            );
                            ?>
                            <?=
                            $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-search-results-item',
                                [
                                    'image' => 'https://picsum.photos/1600/900?random=6',
                                    'type' => 'contenuto',
                                    'title' => 'Ricerca 3 elit Lorem ipsum dolor sit amet elit',
                                    'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                ]
                            );
                            ?>
                        </ul>
                    </div>
                </div>
            </section>

            <!--sondaggi card-->
            <?php if (\Yii::$app->getModule('sondaggi')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="sondaggi-card">Sondaggi card</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-sondaggi-card',
                            [
                                'image' => 'https://picsum.photos/1600/900?random=21',
                                'newPubblication' => true,
                                'titleSize' => 'h5',
                                'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elikadbf anfiownf inwolnwklf inwocnwnv lnwlcnwov lwnlwnvwsnvownclsnvoiwnvwevoun vv',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'dateStart' => '2020-09-12',
                                'dateEnd' => '2020-12-12',
                                'pollState' => '2',
                                'visibility' => 'Pubblico per attività'
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>


            <!-- sondaggi lista-->
            <?php if (\Yii::$app->getModule('sondaggi')) : ?>
                <section class="py-5">
                    <h2 class="it-page-section" id="sondaggi-lista">Sondaggi lista</h2>
                    <div class="bd-example">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-sondaggi',
                            [
                                'image' => 'https://picsum.photos/1600/900?random=2',
                                'newPubblication' => true,
                                'titleSize' => 'h5',
                                'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'dateStart' => '2020-09-12',
                                'dateEnd' => '2020-12-12',
                                'pollState' => '1'
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-sondaggi',
                            [
                                'image' => 'https://picsum.photos/1600/900?random=22',
                                'newPubblication' => true,
                                'titleSize' => 'h5',
                                'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'dateStart' => '2020-09-12',
                                'dateEnd' => '2020-12-12',
                                'pollState' => '2'
                            ]
                        );
                        ?>
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-sondaggi',
                            [
                                'image' => 'https://picsum.photos/1600/900?random=6',
                                'newPubblication' => true,
                                'titleSize' => 'h5',
                                'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                                'dateStart' => '2020-09-12',
                                'dateEnd' => '2020-12-12',
                                'pollState' => '3'
                            ]
                        );
                        ?>
                    </div>
                </section>
            <?php endif; ?>

            <!--torna indietro-->
            <section class="py-5">
                <h2 class="it-page-section" id="back-button">Torna Indietro</h2>
                <div class="bd-example">
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-back-button'
                    );
                    ?>
                </div>
            </section>
            <!--tag-->
            <?php if (\Yii::$app->getModule('tag')) : ?>
                <section class="py-5">
                    <div>
                        <h2 class="it-page-section" id="tag-manage" class="pb-2">Tag </h2>
                    </div>


                    <div class="bd-example">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-tag-manage',
                            [
                                'targetTagTitle' => 'Gli interessi su OPEN 2',
                                'source' => [
                                    '1' => [
                                        'id' => 'checkbox-1',
                                        'name' => 'Nome a caso',
                                    ],
                                    '2' => [
                                        'id' => 'checkbox-2',
                                        'name' => 'Elemento 2',
                                    ],
                                    '3' => [
                                        'id' => 'checkbox-3',
                                        'name' => 'checkbox',
                                    ],

                                ],
                                'target' => [
                                    '4' => [
                                        'id' => 'checkbox-4',
                                        'name' => 'Elemento 4',
                                    ],
                                    '5' => [
                                        'id' => 'checkbox-5',
                                        'name' => 'Elemento 5',
                                    ],
                                    '6' => [
                                        'id' => 'checkbox-6',
                                        'name' => 'Elemento 6',
                                    ],
                                    '7' => [
                                        'id' => 'checkbox-7',
                                        'name' => 'Elemento 7',
                                    ],
                                    '8' => [
                                        'id' => 'checkbox-8',
                                        'name' => 'Elemento 8',
                                    ],
                                    '9' => [
                                        'id' => 'checkbox-9',
                                        'name' => 'Elemento 9',
                                    ],

                                ],
                                'manageLinkLabel' => 'Vai alla gestione completa dei tag nel tuo profilo',
                                'manageLinkTitle' => 'Vai alla gestione completa dei tag nel tuo profilo',
                                'manageLinkCssClass' => 'font-weight-semibold',
                                'manageLinkHref' => '#',
                                'submitButtonLabel' => 'Salva',
                                'infoTooltip' => 'Informazioni per la corretta compilazione',

                            ]
                        );
                        ?>
                    </div>

                </section>
            <?php endif; ?>

            <!--tag-element-->
            <?php if (\Yii::$app->getModule('tag')) : ?>
                <section class="py-5">
                    <div>
                        <h2 class="it-page-section" id="tag" class="pb-2">Tag</h2>
                    </div>

                    <div class="bd-example">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-tag',
                            [
                                'label' => 'Design',
                            ]
                        );
                        ?>
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-tag',
                            [
                                'label' => 'SISTEMI PRODUTTIVI PER LA SOSTENIBILITÀ DELLE BIORISORSE',
                                'depth' => '2',
                                'treeDepthLabels' => ['Tematiche Strategiche Regionali', 'AGROALIMENTARE']
                            ]
                        );
                        ?>

                    </div>

                </section>
            <?php endif; ?>


            <!--Utenti-->
            <section class="py-5 ">
                <h2 class="it-page-section" id="view-list-avatar">Utenti lista</h2>
                <div class="bd-example">

                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-user',
                        [
                            'nameSurname' => 'Paolo Bianchi',
                            'avatarWrapperSize' => 'xl',
                            'url' => '#',
                            'userEmail' => 'prova@gmail.com',
                            'additionalInfo' => 'Programmatore',
                            'isNewUser' => true,
                            'descrizionrBreve' => 'Lorem ipsum',
                            'collaborazionePrevalente' => 'Unità di Ricerca INSTM presso Dipartimento di Scienza dei Materiali - Univ- Milano Bicocca',
                            'facilitatore' => 'Redazione Open Innovation',
                            'statoProfilo' => 'Validato',
                            'showCtaMsg' => 'true',
                            'ctaMsg' => 'Collegati',
                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-user',
                        [
                            'nameSurname' => 'Michelito La Fresca',
                            'avatarWrapperSize' => 'xl',
                            'isNewUser' => true,
                            'url' => '#',
                            'userEmail' => 'prova@gmail.com',
                            'additionalInfo' => 'Web Designer',
                            'descrizionrBreve' => 'Lorem ipsum',
                            'collaborazionePrevalente' => 'Elite Division srl',
                            'facilitatore' => 'Marco Bianchi',
                            'statoProfilo' => 'Modifica in corso',
                            'showCtaMsg' => 'true',
                            'ctaMsg' => 'Collegati',
                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-user',
                        [
                            'nameSurname' => 'Luca Lodiato',
                            'avatarWrapperSize' => 'xl',
                            'isNewUser' => true,
                            'url' => '#',
                            'userEmail' => 'prova@gmail.com',
                            'descrizionrBreve' => 'Lorem ipsum',
                            'additionalInfo' => 'Presidentissimo',
                            'collaborazionePrevalente' => 'Mads srl',
                            'facilitatore' => 'Angelo Rocchi',
                            'statoProfilo' => 'Non validato',
                            'showCtaJoin' => 'true',
                            'ctaJoinMsg' => 'Invia messaggio',
                        ]
                    );
                    ?>
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-avatar-user',
                        [
                            'nameSurname' => 'Marcello Aiello',
                            'avatarWrapperSize' => 'xl',
                            'url' => '#',
                            'userEmail' => 'prova@gmail.com',
                            'descrizionrBreve' => 'Lorem ipsum',
                            'additionalInfo' => 'maestro',
                            'collaborazionePrevalente' => 'Divita snc',
                            'facilitatore' => 'Andrea Fiori',
                            'statoProfilo' => 'Da validare',
                            'showCtaJoin' => 'true',
                            'ctaJoinMsg' => 'Invia messaggio',
                        ]
                    );
                    ?>

                </div>
            </section>
            <!--wizard-->
            <section class="py-5">
                <div>
                    <h2 class="it-page-section" id="wizard" class="pb-2">Wizard steps </h2>
                </div>

                <div class="bd-example">


                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/wizard_steps'
                    );
                    ?>


                </div>
            </section>

            <!--widget il mio profilo-->
            <section class="py-5">
                <div>
                    <h2 class="it-page-section" id="widget-mio-profilo" class="pb-2">Widget Il mio profilo</h2>
                </div>
                <div class="bd-example">


                    <?php
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-widget-myprofile',
                        [
                            'numberNewContent' => '1',
                            'avatarUrl' => 'https://picsum.photos/1600/900?random=2',
                            'prevalentPartnershipName' => 'Collaborazione prevalente',
                            'nameSurname' => 'Paolo Guestesi',
                            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut laborea',
                            'isFacilitator' => true,
                            'isCommunityManager' => true,
                            'tags' => [
                                1 => [
                                    'label' => 'Design',
                                    'depth' => '1',

                                ],
                                2 => [
                                    'label' => 'SISTEMI PRODUTTIVI PER LA SOSTENIBILITÀ DELLE BIORISORSE',
                                    'depth' => '2',

                                ]
                            ],
                            'totCounter' => 4,
                            'counters' => [
                                1 => [
                                    'url' => '#',
                                    'name' => 'Notizie',
                                    'counter' => '2',
                                ],
                                2 => [
                                    'url' => '#',
                                    'name' => 'Discussioni',
                                    'counter' => '10',
                                ],
                                3 => [
                                    'url' => '#',
                                    'name' => 'Proposte di collaborazione',
                                    'counter' => '1',
                                ],
                                4 => [
                                    'url' => '#',
                                    'name' => 'Proposte dal mondo',
                                    'counter' => '2',
                                ]
                            ],
                        ]
                    );
                    ?>



                </div>

            </section>


            <!--form-->
            <section class="py-5 border-bottom border-top">
                <div>
                    <h1 id="formExample" class="no_toc">Form</h1>

                    <h2 class="it-page-section" class="mb-2">Singoli componenti</h2>
                    <p> Elenco dei singoli componenti che possono essere inseriti in un form.
                        Ogni volta che si istanzia uno di questi elementi è possibile passare al componente renderizzato i parametri di cui si hanno bisogno
                        (per esempio il valore del placeholder, il valore dell'id dell'input..).
                    </p>
                    <p>Per fare questo passaggio viene passato un array chiave valore mentre viene chiamato il render del componente, nel seguente modo:
                    </p>
                    <pre>
                    <code class="language-php">
                    echo $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/nome-vista-componente',
                        [ //array chiave-valore con i parametri
                        'nome-variabile' => 'valore variabile',
                        ...
                        ]
                    );
                    </code>
                    </pre>
                    <p class="mb-4">In seguito poi nella vista verranno visualizzati i valori delle variabili passate.
                    </p>
                    <p class="mb-4">Elenco options custom (da implementare nei componenti che ancora non le prevedono)
                    <ul>
                        <li>ariaDescribedBy: testo aggiuntivo di supporto alla compilazione del campo (nominato anche hint)</li>
                        <li>helperTooltip: testo aggiuntivo di supporto alla label per la comprensione del campo</li>
                        <li>infoTooltip: testo aggiuntivo di supporto per la compilazione corretta del campo (dipende dalla presenza di ariaDescribedBy)</li>
                    </ul>
                    <div class="my-3">
                        <h2 class="it-page-section" class="mb-2">Checkbox</h2>
                        <!--checkbox-->
                        <div>
                            <div class="form-group">


                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-checkbox',
                                    [
                                        'label' => 'Checkbox',
                                        'inputId' => 'EsempioCheckbox'
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                        <!--radio button -->
                        <div>
                            <h2 class="it-page-section" class="mt-4">Radio button</h2>
                            <div class="form-group">
                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-radio-button',
                                    [
                                        'label' => 'Checkbox',
                                        'inputId' => 'EsempioRadioButton'
                                    ]
                                );
                                ?>
                            </div>
                        </div>
                        <!--datepicker-->
                        <h2 class="it-page-section" class="my-5">Date picker</h2>
                        <div class="form-group">
                            <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-datepicker',
                                [
                                    'label' => 'Checkbox',
                                    'inputId' => 'EsempioCalendar'
                                ]
                            );
                            ?>
                        </div>
                        <!--select-->
                        <h2 class="it-page-section" class="my-5">Select con campo cerca</h2>
                        <div class="form-group">
                            <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-select-live-search',
                                [
                                    'label' => 'Input campo select',
                                    'inputId' => 'EsempioSelect'
                                ]
                            );
                            ?>
                        </div>

                        <!--input textarea-->
                        <h2 class="it-page-section" class="my-5">Text area</h2>
                        <div class="form-group">
                            <?php
                            echo $this->render(
                                '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-textarea',
                                [
                                    'label' => 'Input campo textarea',
                                    'inputId' => 'EsempioTextArea'
                                ]
                            );
                            ?>
                        </div>

                        <!--input email e pwd-->
                        <h2 class="it-page-section" id="vari-input" class="my-5">Vari input</h2>
                        <div>
                            <div class="form-group">
                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-email',
                                    [
                                        'label' => 'Input campo Email',
                                        'inputId' => 'EsempioEmail',
                                        'type' => 'email'
                                    ]
                                );
                                ?>
                            </div>
                            <div class="form-group">
                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-password',
                                    [
                                        'label' => 'Input campo Password',
                                        'inputId' => 'EsempioPassword',
                                        'type' => 'password'
                                    ]
                                );
                                ?>
                            </div>
                            <div class="form-group">
                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-text',
                                    [
                                        'label' => 'Input campo testo generico',
                                        'inputId' => 'EsempioTestoGenerico',
                                        'type' => 'text',
                                        'options' => [
                                            'aria-describedby' => 'aria-describedby options',
                                            'infoTooltip' => 'tooltip info text'
                                        ]
                                    ]
                                );
                                ?>
                            </div>
                            <div class="form-group">
                                <?php
                                echo $this->render(
                                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-search',
                                    [
                                        'label' => 'Input campo ricerca',
                                        'inputId' => 'EsempioInputRicerca',
                                        'type' => 'search'
                                    ]
                                );
                                ?>
                            </div>
                        </div>

                    </div>

                    <h2 class="it-page-section" id="form-rounded" class="mb-2">Form rounded</h2>
                    <p>Classi tag form:
                    <p>
                    <ul>
                        <li>needs-validation: per i campi obbligatori del form richiede l'inserimento dei dati tramite avviso in caso di non avvenuta compilazione; </li>
                        <li>form-rounded: classe che inserisce bordo agli input del form, sovrascrivendo gli input senza bordi di Bootstrap Italia. Nell'esempio sotto il form contiene la classe form-rounded.</li>
                    </ul>
                    <div class="my-4">
                        <?php
                        echo $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/form',
                            [
                                'label' => 'Checkbox',
                                'inputId' => 'EsempioCheckbox'
                            ]
                        );
                        ?>
                    </div>
            </section>



            <section class="py-5 border-bottom">
                <h2 class="it-page-section" id="toggle" class="mb-5">Toggles</h2>
                <div>

                    <!-- 2 variabili: ognuna per dire quale è la label attivo/disattivo -->
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-toggle',
                        [
                            'labelOn' => 'Attivo',
                            'labelOff' => 'Disattivo',
                        ]
                    );
                    ?>
                </div>
            </section>

            <!--icone-->
            <section class="py-5 border-bottom">
                <h2 class="it-page-section" id="icone">Icone</h2>
                <p class="lead">Informazioni e suggerimenti per l’utilizzo di icone con <a href="https://italia.github.io/bootstrap-italia/docs/utilities/icone/" target="_blank"><strong>Bootstrap Italia</strong></a> e <a href="https://materialdesignicons.com/" target="_blank"><strong>Material Design</strong></a>.</p>
                <p>Utility per la stampa delle icone di BI e Material:</p>
                <p><strong>BOOTSTRAP ITALIA:</strong> <mark><code> echo(DesignIcon::show('nome-icona', DesignIcon::ICON_BI))</code></mark></p>
                <p><strong>MATERIAL:</strong> <mark><code> echo(DesignIcon::show('nome-icona', DesignIcon::ICON_MD))</code></mark></p>
                <p>Di default viene stampata l'svg con la classe 'icon', per modificarla:<mark><code>echo (DesignIcon::show('nome-icona', DesignIcon::ICON_BI, 'classe'))</code></mark></p>

            </section>

            <section class="py-5 border-bottom">
                <h2 class="it-page-section" id="icone">Toolbar plugin</h2>
                <p class="lead">Attivabile con \Yii::$app->view->params['enablePluginToolbar'] = TRUE</p>
                <div>

                    <!-- 2 variabili: ognuna per dire quale è la label attivo/disattivo -->
                    <?=
                    $this->render(
                        '@vendor/open20/design/src/views/layouts/parts/bi-plugin-toolbar',
                        []
                    );
                    ?>
                </div>
            </section>

            <section class="py-5 border-bottom">
                <h2 class="it-page-section" id="moreLessText">More-Less Text</h2>
                <?=
                $this->render(
                    '@vendor/open20/design/src/components/bootstrapitalia/views/bi-more-less-text',
                    [
                        'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
                        'limit' => '100'
                    ]
                );
                ?>
            </section>

        </div>
    </div>
</div>