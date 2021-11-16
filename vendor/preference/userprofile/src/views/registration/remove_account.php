<?php

use open20\amos\core\helpers\Html;
use yii\helpers\Url;

?>
<div class="py-5 user-success">
    <div class="container">
        <div class="row variable-gutters">
            <div class="col-md-4 d-none d-md-block">
                <img class="img-fluid" src="/img/alert_message.png" title="immagine messaggio di allerta" alt="immagine messaggio di allerta"/>
            </div>
            <div class="col-12 col-md-8 px-3">
                <h1 class="h2">Vuoi annullare la tua iscrizione al servizio Lombardia Informa?</h1>
                <img class="img-fluid mr-auto ml-auto d-block d-md-none" src="/img/alert_message.png" title="immagine messaggio di allerta" alt="immagine messaggio di allerta"/>
                <p class="tertiary-color mt-md-5">
                    Puoi annullare la tua registrazione al servizio Lombardia Informa cliccando sul pulsante “Annulla iscrizione”. 
                </p>
                <p class="tertiary-color mt-md-5">
                    Per tornare indietro e completare la registrazione, accedi al sito <?= Html::a('lombardiainforma.it', Url::to('/')) ?>
                </p>
                <div class="d-flex justify-content-md-end justify-content-center mt-5">
                    <a href="<?= Url::to('/') ?>" class="btn btn-primary mb-2 float-right" title="accedi all'area riservata">Annulla iscrizione</a>
                </div>
            </div>
        </div>
    </div>
</div>