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
            <div class="col-md-4 d-none d-md-block">
                <img class="img-fluid" src="/img/invio.png" title="immagine invio email" alt="immagine invio email"/>
            </div>
            <div class="col-12 col-md-8 px-3">
                <h1 class="h2">Congratulazioni! Hai completato la registrazione con successo</h1>
                <img class="img-fluid d-block d-md-none" src="/img/invio.png" title="immagine invio email" alt="immagine invio email"/>
                <p class="tertiary-color mt-md-5">
                    Adesso puoi usufruire dei servizi proposti dal servizio Lombardia Informa.
                </p>
                <div class=" d-flex justify-content-md-end justify-content-center mt-5">
                    <a href="/preferenceuser/preference/settings" class="btn btn-primary mb-2 float-right" title="accedi all'area riservata">Accedi</a>
                </div>
            </div>
        </div>
    </div>
</div>