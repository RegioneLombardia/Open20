<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\helpers\Html;
use yii\helpers\VarDumper;
?>

<!-- ultima pagina, di comunicazione errore (pagina link di errore) -->

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
                    Per tornare indietro e completare la registrazione, accedi al sito <a href="#" title="accedi all'area riservata" class="primary-color text-decoration-none font-weight-bold">lombardiainforma.it</a>  
                </p>
                <div class="d-flex justify-content-md-end justify-content-center mt-5">
                    <a href="#" class="btn btn-primary mb-2 float-right" title="accedi all'area riservata">Annulla Iscrizione</a>
                </div>
            </div>
        </div>
    </div>
</div>