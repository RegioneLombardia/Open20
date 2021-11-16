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

<div class="py-5 user-success">
    <div class="container">
        <div class="row variable-gutters">
            <div class="col-md-5 col-lg-4 mr-auto d-none d-md-block">
                <img class="img-fluid" src="/img/alert_message.png" title="immagine messaggio di allerta" alt="immagine messaggio di allerta"/>
            </div>
            <div class="col-12 col-md-7 px-3">
                <h1 class="h2">Sei atterrato su questa pagina perchè hai ricevuto l’email per errore</h1>
                <img class="img-fluid mr-auto ml-auto d-block d-md-none" src="/img/alert_message.png" title="immagine messaggio di allerta" alt="immagine messaggio di allerta"/>
                <p class="tertiary-color mt-md-5">
                    Gentile utente confermi di voler fare richiesta di cancellazione permanente dei dati che hai fornito a Regione Lombardia?
                </p>
                <div class="d-flex justify-content-md-end justify-content-center mt-5">
                    <a href="/it/" class="btn btn-primary mb-2 float-right" title="accedi all'area riservata">Cancella</a>
                </div>
            </div>
        </div>
    </div>
</div>