<?php
use open20\gamification\Module;

?>
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Iscrizione di un utente')?>:</label> <?= $model->utente_iscrizione; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Inserimento foto profilo')?>:</label> <?= $model->utente_foto; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Collegamento con un altro utente')?>:</label> <?= $model->utente_colleg; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Creazione di una community')?>:</label> <?= $model->community_creazione; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Iscrizione a una community')?>:</label> <?= $model->community_iscrizione; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Creazione di una notizia')?>:</label> <?= $model->notizie_creazione; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Commento una notizia')?>:</label> <?= $model->notizie_commento; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Like a una notizia')?>:</label> <?= $model->notizie_like; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Creazione di un documento')?>:</label> <?= $model->documenti_creazione; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Creazione di una discussione')?>:</label> <?= $model->discussioni_creazione; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Partecipazione ad una discussione')?>:</label> <?= $model->discussioni_commento; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Like a una discussione')?>:</label> <?= $model->discussioni_like; ?>
    </div>

    <div class="col-xs-12 col-sm-4">
        <label><?=Module::t('gamification', 'Partecipazione a un sondaggio')?>:</label> <?= $model->sondaggi_partec; ?>
    </div>
</div>

