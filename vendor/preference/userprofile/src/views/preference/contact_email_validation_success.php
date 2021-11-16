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
<!-- terza pagina di conferma registrazione (pagina feedback di conferma validazione email di contatto) -->
<div class="py-5 user-success">
    <div class="container">
        <div class="row variable-gutters">
            <div class="col-md-4 d-none d-md-block">
                <img class="img-fluid" src="/img/conferma.png" title="immagine di conferma" alt="immagine di conferma"/>
            </div>
            <div class="col-12 col-md-8 px-3">
                <h1 class="h2">Verifica del contatto email avvenuta con successo</h1>
                <img class="img-fluid mr-auto ml-auto d-block d-md-none" src="/img/conferma.png" title="immagine di conferma" alt="immagine di conferma"/>
                <p class="tertiary-color mt-5">
                    Ti ringraziamo per aver validato l’email: <strong><?php echo $email ?></strong>
                </p>
                <p class="tertiary-color">
                    Adesso puoi usufruire del servizio Lombardia Informa.<br>
                    Riceverai su questo indirizzo email comunicazioni di interesse per: <strong><?= $labelTarget ?></strong>
                </p>
                <div class="d-flex justify-content-md-end justify-content-center mt-5">
                    <a href="/preferenceuser/preference/settings" class="btn btn-primary mb-2 float-sm-right" title="accedi all'area riservata">Accedi</a>
                </div>
            </div>
        </div>
    </div>
</div>