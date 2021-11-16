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
                    Ti abbiamo inviato un'email al seguente indirizzo:<br> <span class="primary-color font-weight-bold"><?php echo $email ?></span>
                </p>

                <p class="tertiary-color">
                    Accedi alla tua posta elettronica e clicca sul pulsante “Valida email” per poter attivare il servizio e ricevere le comunicazioni di tuo interesse.
                </p>
                <!-- <div class="d-flex align-items-start">
                    <img class="mt-2 mr-1" src="/img/nuvola_commenti.svg" title="immagine fumetto commento" alt="immagine fumetto commento"/>
                    <p class="tertiary-color font-italic pb-1">
                        <small>Nel caso in cui tu abbia fornito anche contatti telefonici accedi al servizio e inserisci il codice a 6 cifre che hai ricevuto via sms per attivare il servizio. </small>
                    </p>
                </div> -->
                <!-- <div class=" d-flex justify-content-md-end justify-content-center mt-5">
                    <a href="/preferenceuser/preference/settings" class="btn btn-primary mb-2 float-right" title="accedi all'area riservata">Accedi</a>
                </div> -->
            </div>
        </div>
    </div>
</div>