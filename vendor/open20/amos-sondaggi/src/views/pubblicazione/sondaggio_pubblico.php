<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\views\pubblicazione
 * @category   CategoryName
 */

use open20\amos\sondaggi\AmosSondaggi;

if ($libero) {
    $link = "/" . $this->context->module->id . "/pubblicazione/sondaggi-pubblici";
    $testoLink = "Sondaggi pubblici";
    $quest = open20\amos\sondaggi\models\Sondaggi::findOne($id);
    $breadcrumb = $quest->titolo;
    $this->title = $breadcrumb;
    $descrizione = $quest->descrizione;
} else { //TODO PER ENTITA' SPECIFICHE
    $link = "/" . $this->context->module->id . "/pubblicazione/sondaggio-pubblico-attivita";
    $testoLink = "Sondaggio di gradimento";
    $quest = open20\amos\sondaggi\models\Sondaggi::findOne($id);
    $breadcrumb = 'DA CONFIGURARE';//backend\modules\attivitaformative\models\PeiAttivitaFormative::findOne(['codice_attivita' => $attivita])->titolo;
    $this->title = $breadcrumb;
    $descrizione = $quest->descrizione;
}
?>
<!--<div class="container">
    <nav role="navigation" aria-label="breadcrumbs" aria-labelledby="bc-title" id="bc">
        <h5 id="bc-title" class="vis-off">Sei qui:</h5>
        <ol class="breadcrumb">
            <li><a href="/site/index">Home</a></li>  
            <li><a href="< ? =$link;?>">< ? =$testoLink;?></a></li>  
            <li class="active">< ? = $breadcrumb ?></li>
        </ol>
    </nav>
</div>-->
<main role="main" id="mainContent">
    <div class="container">
        <div class="page" role="contentinfo">
            <h1><?= $this->title; ?></h1>
            <div class="sondaggi-compilazione marginTB">
                <?= $this->render('/q' . $id . '/Pagina_' . $idPagina, [
                    'model' => $model,
                    'idSessione' => $idSessione,
                    'idPagina' => $idPagina,
                    'attivita' => $attivita,
                    'libero' => $libero,
                    'utente' => NULL
                ]) ?>
            </div>
        </div>
    </div>
</main>
