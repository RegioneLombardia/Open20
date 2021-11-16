<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use open20\amos\core\forms\Tabs;

$this->title = 'Conferma Adesione';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="step-container">
    <div class="step-card">
        <div class="vertical-space"></div>
        <div class="tab-contentainer">
            <div class="tab-pane" id="rappresentante">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="info-text">registrazione effettuata con successo</h1>
                        <div class="info-text">Appena avremo verificato le informazioni inserite con esito
                            positivo, sarà inviata una mail di avviso all'indirizzo che è stato indicato per il legale
                            rappresentante; la mail conterrà le informazioni necessarie per accedere alla piattaforma e
                            presentare l'atto unico di adesione e la domanda di ammissione al catalogo. Dopo aver
                            eseguito queste due operazioni sarete abilitati alla presentazione dei corsi.</div>
                    </div>
                </div>
                <div class="help-message">
                    <p>Hai completato la procedura di candidatura dell’ente.</p>
                    <p>Ti abbiamo inviato una mail con le istruzioni per accedere alla piattaforma, controlla la tua
                        casella di posta elettronica.</p>
                    </p>
                    <p>Se hai bisogno di assistenza puoi inviare una mail a: &nbsp;<span class="am am-email"></span>info@example.com
                    </p>
                </div>
                <a class="pull-right" href="/site/index">
                    <div class="btn btn-navigation-primary"><span class="am am-home"></span>Torna alla home page</div>
                </a>
            </div>
        </div>
    </div>
</div>