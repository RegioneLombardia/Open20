<?php

use open20\cms\dashboard\Module;
use frontend\modules\activities\widget\ActivitiesWidget;

$this->title = Module::txt('Dashboard');

?>

<style>
    .container-favourites.favorite-from-cms {
        display: none !important;
    }
</style>

<div class="dashboard-redattore">
    <div class="pt-4">

        <div class="container-padding-xxl">

            <div class="widget-dashboard widget-azioni-rapide">

                <div class="box-azione-rapida azione-crea-pagina">
                    <a href="/dashboards/d1/create-page" title="<?= Module::t('amoscmsdashboard', 'Crea pagina ed inserisci contenuti redazionali') ?>">
                        <div class="icon-azione">
                            <span class="mdi mdi-file-document-plus"></span>
                        </div>
                        <div class="text-azione">
                            <span><?= Module::t('amoscmsdashboard', 'Crea') ?></span>
                            <strong><?= Module::t('amoscmsdashboard', 'Pagina') ?></strong>
                            <small class="text-muted"><?= Module::t('amoscmsdashboard', 'Componi una nuova pagina utilizzando l’interfaccia drag&drop, e i componenti redazionali') ?></small>
                        </div>
                    </a>

                </div>
                <div class="box-azione-rapida azione-crea-modello">
                    <a href="/dashboards/d1/crea-modello" title="<?= Module::t('amosdashboards', 'Crea modello di pagina') ?>" >
                        <div class="icon-azione">
                            <span class="mdi mdi-shape-plus"></span>
                        </div>
                        <div class="text-azione">
                            <span><?= Module::t('amoscmsdashboard', 'Crea') ?></span>
                            <strong><?= Module::t('amoscmsdashboard', 'Modello<br>di pagina') ?></strong>
                            <small class="text-muted"><?= Module::t('amosdashboards', 'Componi un layout da salvare e riutilizzare per creare nuove pagine') ?></small>

                        </div>
                    </a>
                </div>





                <div class="box-azione-rapida azione-crea-immagini">
                    <a href="/attachments/attach-gallery-image/create?id=1" title="<?= Module::t('amoscmsdashboard', 'Carica immagini') ?>" >
                        <div class="icon-azione">
                            <span class="mdi mdi-image-plus"></span>
                        </div>
                        <div class="text-azione">
                            <span><?= Module::t('amoscmsdashboard', 'Carica') ?></span>
                            <strong><?= Module::t('amoscmsdashboard', 'Immagini') ?></strong>
                            <small class="text-muted"><?= Module::t('amoscmsdashboard', 'Aggiungi un asset immagine caricandolo dal computer o attingendo a banche dati online') ?></small>
                        </div>
                    </a>

                </div>

                <div class="box-azione-rapida azione-crea-allegati">
                    <a href="/attachments/attach-databank-file/create" title="<?= Module::t('amoscmsdashboard', 'Carica documenti') ?>">
                        <div class="icon-azione">
                            <span class="mdi mdi-paperclip-plus"></span>
                        </div>
                        <div class="text-azione">
                            <span><?= Module::t('amoscmsdashboard', 'Carica') ?></span>
                            <strong><?= Module::t('amoscmsdashboard', 'Allegati') ?></strong>
                            <small class="text-muted"><?= Module::t('amoscmsdashboard', 'Aggiungi un asset allegato caricandolo dal computer e aggregalo ai progetti utilizzando i tag') ?></small>
                        </div>
                    </a>

                </div>
                <!-- <div class="box-azione-rapida azione-crea-news">
                <a href="/news/news/create" title="< ?= Module::t('amoscmsdashboard', 'Aggiungi notizie sul sito ed utilizza questo asset redazionale nella composizione delle pagine.') ?>" data-toggle="tooltip" data-placement="bottom">
                    <div class="icon-azione">
                        <span class="mdi mdi-newspaper-plus"></span>
                    </div>
                    <div class="text-azione">
                        <span>< ?= Module::t('amoscmsdashboard', 'Inserisci') ?></span>
                        <strong>< ?= Module::t('amoscmsdashboard', 'Notizia') ?></strong>
                    </div>
                </a>

            </div> -->

            </div>

        </div>
        <div class="container-padding-xxl pt-4 mb-4">
            <!-- START CONTENUTI DA VALIDARE -->
            <div class="widget-dashboard widget-task widget-contenuti-da-validare">
                <div class="title-widget d-flex">
                    <p class="h6 text-uppercase text-danger">
                        <?= ($isCapoRedattore) ? Module::t('amoscmsdashboard', 'Contenuti da validare') : Module::t('amoscmsdashboard', 'Contenuti inviati a validazione'); ?>
                    </p>
                </div>
                <?php
                if (!empty($contents['validationRequests'])) {
                    foreach ($contents['validationRequests'] as $validationRequest) {
                        echo $this->render('parts/_itemDashboardValidazione', [
                            'model' => $validationRequest
                        ]);
                    }
                } else {
                    echo ($isCapoRedattore) ? Module::t('amoscmsdashboard', 'Nessun contenuto da validare') : Module::t('amoscmsdashboard', 'Nessun contenuto inviato a validazione');
                }
                ?>
            </div>
        </div>
        <?php
        $module = Yii::$app->getModule('activities');
        if (!is_null($module)){
            ?>
            <div class="container-padding-xxl py-4">
                <!-- START ATTIVITA' ASSEGNATE -->
                <div class="widget-dashboard widget-task">
                    <div class="title-widget d-flex">
                        <p class="h6 text-uppercase"><?= Module::t('amoscmsdashboard', 'Attività assegnate a me') ?></p>
                    </div>
                    <?php echo ActivitiesWidget::widget(['assignedToUserId' => Yii::$app->user->id]) ?>
                </div>
            </div>
            <?php
        }
        ?>
        <!-- END ATTIVITA' ASSEGNATE -->
        <div class="widget-tabs container-padding-xxl pb-5">
            <ul class="nav nav-tabs nav-tabs-icon-text" id="myTab3" role="tablist">
                
                <li class="nav-item">
                    <a class="nav-link active" id="tab2b-tab" data-toggle="tab" href="#tab2b" role="tab" aria-controls="tab2b" aria-selected="true" title="<?= Module::t('amoscmsdashboard', 'Ultimi contenuti modificati') ?>">
                        <span class="h6 text-uppercase title-tab"><?= Module::t('amoscmsdashboard', 'Ultimi contenuti modificati') ?></span>
                        <span class="icon-tab mdi mdi-progress-pencil"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3b-tab" data-toggle="tab" href="#tab3b" role="tab" aria-controls="tab3b" aria-selected="false" title="<?= Module::t('amoscmsdashboard', 'Ultimi contenuti pubblicati') ?>">
                        <span class="h6 text-uppercase title-tab"><?= Module::t('amoscmsdashboard', 'Ultimi contenuti pubblicati') ?></span>
                        <span class="icon-tab mdi mdi-cloud"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab4b-tab" data-toggle="tab" href="#tab4b" role="tab" aria-controls="tab3b" aria-selected="false" aria-disabled="true" tabindex="-1" title="<?= Module::t('amoscmsdashboard', 'Prossimi alla pubblicazione') ?>">
                        <span class="h6 text-uppercase title-tab"><?= Module::t('amoscmsdashboard', 'Prossimi alla pubblicazione') ?></span>
                        <span class="icon-tab mdi mdi-cloud-clock-outline"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab1c-tab" data-toggle="tab" href="#tab1b" role="tab" aria-controls="tab1b" aria-selected="false" title="I miei segnalibri">
                        <span class="h6 text-uppercase title-tab"><?= Module::t('amoscmsdashboard', 'I miei segnalibri') ?></span>
                        <span class="icon-tab mdi mdi-bookmark"></span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTab3Content">
                <div class="tab-pane p-3 fade" id="tab1b" role="tabpanel" aria-labelledby="tab1c-tab">
                    <?php
                    echo \open20\amos\favorites\widgets\ListFavoriteUrlsWidget::widget(['enableTable' => true]);

                    ?>
                </div>
                <div class="tab-pane p-4 fade show active" id="tab2b" role="tabpanel" aria-labelledby="tab2b-tab">
                    <?php
                    if (!empty($contents['last10Updated'])) {
                        foreach ($contents['last10Updated'] as $k => $lastUpdatedContent) {
                            echo $this->render('parts/_itemDashboardTabLastModified', [
                                'model' => $lastUpdatedContent,
                                'key' => $k
                            ]);
                        }
                    } else {
                        echo Module::txt('Nessun contenuto trovato');
                    }
                    ?>
                </div>
                <div class="tab-pane p-4 fade" id="tab3b" role="tabpanel" aria-labelledby="tab3b-tab">
                    <?php
                    if (!empty($contents['last10Published'])) {
                        foreach ($contents['last10Published'] as $lastPublishedContent) {
                            echo $this->render('parts/_itemDashboardTabLastPublished', [
                                'model' => $lastPublishedContent
                            ]);
                        }
                    } else {
                        echo Module::txt('Nessun contenuto trovato');
                    }
                    ?>
                </div>
                <div class="tab-pane p-4 fade" id="tab4b" role="tabpanel" aria-labelledby="tab4b-tab">
                    <?php
                    if (!empty($contents['upcoming'])) {
                        foreach ($contents['upcoming'] as $upcomingContent) {
                            echo $this->render('parts/_itemDashboardTabNextPublish', [
                                'model' => $upcomingContent
                            ]);
                        }
                    } else {
                        echo 'Nessun contenuto trovato';
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>