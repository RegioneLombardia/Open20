<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

use open20\design\components\bootstrapitalia\ActiveForm;
use open20\amos\core\helpers\Html;
use yii\helpers\VarDumper;


use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\design\components\bootstrapitalia\CheckBoxListTopicsIcon;

$bootstrapItaliaAsset = BootstrapItaliaDesignAsset::register($this);
?>

<?php
$form = ActiveForm::begin([
    'options' => [
        'id' => 'preferencies-form',
        'data-fid' => (isset($fid)) ? $fid : 0,
        'data-field' => ((isset($dataField)) ? $dataField : ''),
        'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
        'enctype' => 'multipart/form-data',
    ],
]);
?>


<div class="lightgrey-bg-c1 py-5">
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h1>Informativa Privacy</h1>
          <p class="tertiary-color">Sottoscrivi i termini e le condizioni per la privacy</p>
        </div>
        <div class="col-md-3">
            <ul class="wizard-steps text-center">
                <li class="active-step">
                    <div>1</div>
                </li>
                <li class="active-step  ">
                    <div>2</div>
                </li>
                <li class="active-step  ">
                    <div>3</div>
                </li>
                <li class="active-step current-step">
                    <div>4</div>
                </li>
            </ul>
        </div>
      </div>

    </div>
</div>


<div class="container py-5 mb-5 border-bottom border-light">
    <div class="uk-container">

        <div class="uk-grid-margin" uk-grid="">
            <div id="9ec" class="uk-width-1-1">
                <div id="text-13b">

                    <div>Prima che Lei ci fornisca i dati personali che La riguardano, in armonia con quanto previsto
                        dal Regolamento Europeo sulla protezione dei dati personali 2016/679, dal D.lgs. 30 giugno 2003,
                        n. 196 e dal D.lgs. 10 agosto 2018, n. 101,&nbsp; il cui obiettivo è quello di proteggere i
                        diritti e le libertà fondamentali delle persone fisiche, in particolare il diritto alla
                        protezione dei dati personali, è necessario che Lei prenda visione di una serie di informazioni
                        che La possono aiutare a comprendere le motivazioni per le quali verranno trattati i Suoi dati
                        personali, spiegandoLe quali sono i Suoi diritti e come li potrà esercitare.&nbsp;<br><br></div>
                    <div>Successivamente, se tutto Le sarà chiaro, potrà liberamente decidere se prestare il Suo
                        consenso affinché i Suoi dati personali possano essere trattati, sapendo fin d’ora che tale
                        consenso potrà essere da Lei revocato in qualsiasi momento.
                    </div>

                </div>
                <div id="text-5eb">

                    <div><strong><br>1. Finalità del trattamento dei dati personali</strong></div>
                    <div>I Suoi dati personali (e-mail, C.F. o identificativo eIDAS come dati obbligatori e nome,
                        cognome, data di nascita, sesso, provincia e comune di residenza, e-mail aggiuntive e recapiti
                        telefonici quali campi facoltativi) sono trattati al fine di consentirLe la registrazione alla
                        piattaforma Lombardia Informa che Le permetterà di ricevere la newsletter di Regione Lombardia e
                        di gestire le tipologie di comunicazioni che desidera ricevere, sulla base delle aree tematiche
                        di interesse e delle preferenze da Lei espresse.
                    </div>
                    <div>&nbsp;</div>
                    <div>Base giuridica del trattamento per le finalità sopra dettagliate è il consenso
                        dell’interessato, ai sensi dell’art. 6, par. 1, lett. a) del Regolamento UE 2016/679.
                    </div>

                </div>
                <div id="text-a80">

                    <div><strong><br>2. Modalità del trattamento dei dati</strong></div>
                    <div>Il trattamento è effettuato con l’ausilio di mezzi elettronici o comunque automatizzati e
                        trasmessi attraverso reti telematiche.
                    </div>
                    <div>Il Titolare adotta misure tecniche e organizzative adeguate a garantire un livello di sicurezza
                        idoneo rispetto alla tipologia di dati trattati.
                    </div>

                </div>
                <div id="text-f26">

                    <div><strong><br>3. Titolare del Trattamento</strong></div>
                    <div>Titolare del trattamento dei Suoi dati è Regione Lombardia (C.F. 80050050154, P. IVA
                        12874720159), con sede centrale in Piazza Città di Lombardia 1, 20124 Milano (MI), Italia.
                    </div>

                </div>
                <div id="text-3b1">

                    <div><strong><br>4. Responsabile della Protezione dei dati (RPD)</strong></div>
                    <div>Il Responsabile della Protezione dei dati (RPD) è contattabile al seguente indirizzo mail: <a
                                href="mailto:rpd@regione.lombardia.it">rpd@regione.lombardia.it</a>.
                    </div>

                </div>
                <div id="text-83e">

                    <div><strong><br>5. Facoltatività e obbligatorietà del consenso</strong></div>
                    <div>La informiamo che, in mancanza del Suo consenso, non sarà possibile procedere al trattamento
                        dei suoi dati personali, quindi non Le sarà possibile fruire del servizio.
                    </div>

                </div>
                <div id="text-c7b">

                    <div><strong><br>6. Comunicazione e diffusione dei dati personali</strong></div>
                    <div>I Suoi dati personali saranno trattati esclusivamente dal Titolare del trattamento, dai
                        responsabili nominati, quali ARIA S.p.A., Azienda Regionale per l’Innovazione e gli Acquisti, e
                        da eventuali ulteriori fornitori appositamente nominati.
                    </div>
                    <div>I destinatari dei Suoi dati personali sono stati adeguatamente istruiti per poter trattare i
                        Suoi dati personali, e assicurano il medesimo livello di sicurezza offerto dal Titolare.
                    </div>
                    <div>I Suoi dati personali non saranno diffusi.</div>

                </div>
                <div id="text-113">

                    <div><strong><br>7. Tempi di conservazione dei dati</strong></div>
                    <div>I dati personali saranno conservati per il periodo di tempo necessario a permetterLe di
                        usufruire dei servizi della piattaforma Lombardia Informa e fino al momento in cui Lei non
                        deciderà di terminare la Sua iscrizione al servizio. Al momento della Sua disiscrizione, i Suoi
                        dati personali verranno cancellati e/o resi anonimi in modo da non permettere, anche
                        indirettamente, di identificarLa.
                    </div>

                </div>
                <div id="text-5a5">

                    <div><strong><br>8. Diritti dell'interessato</strong></div>
                    <div>Lei potrà esercitare i diritti di cui agli artt. da 15 a 22 del Regolamento UE 679/2016, ove
                        applicabili con particolare riferimento all’art.13 comma 2 lettera B) che prevede il diritto di
                        accesso ai dati personali, la rettifica, la cancellazione, la limitazione del trattamento,
                        l’opposizione e la portabilità dei dati.
                    </div>
                    <div>Le sue Richieste per l’esercizio dei Suoi diritti dovranno essere inviate all’indirizzo di
                        posta elettronica <a
                                href="mailto:comunicazione_giovani_cittametropolitana@pec.regione.lombardia.it">comunicazione_giovani_cittametropolitana@pec.regione.lombardia.it</a>
                        oppure a mezzo posta raccomandata all'indirizzo Piazza Città di Lombardia 1, 20124 Milano
                        all'attenzione della Direzione Generale Sviluppo Città Metropolitana, Giovani e Comunicazione.
                    </div>
                    <div>Lei ha, inoltre, diritto di proporre reclamo all’Autorità di Controllo competente.</div>

                </div>
            </div>
        </div>
        
    </div>

    <div class="privacy-check-container">
        <?= $form->field($model, 'privacy')->checkbox() ?>
    </div>
</div>

    
<div class="container wizard-button-container d-flex flex-row justify-content-center justify-content-sm-between mb-0 mb-sm-5"> 
    <div>
        <?php
        echo Html::a(Yii::t('preferenceuser', 'Annulla'), ['/'], [
            'class' => 'btn btn-outline-primary d-none d-sm-block px-5',
            'title' => Yii::t('preferenceuser', 'Torna in home page'),
        ]);
        ?>
    </div>
    <div class="d-flex flex-row">
        <?php
        echo Html::a(Yii::t('preferenceuser', 'Indietro'), ['contacts'], [
            'class' => 'btn btn-outline-primary mr-2 px-5',
            'title' => Yii::t('preferenceuser', 'Torna allo step precedente'),
        ]);
        ?>
        <?php
        echo Html::submitButton(
            'Accetta',
            ['class' => 'btn btn-primary px-5', 'name' => 'submit-action', 'value' => 'forward']
        );
        ?>
    </div>
    
</div>
<?php ActiveForm::end(); ?>
<div class="mobile-wizard-button mb-5 d-flex justify-content-center d-sm-none">
    <?php
    echo Html::a(Yii::t('preferenceuser', 'Annulla registrazione'), ['/'], [
        'class' => 'text-decoration-none text-secondary pt-4 px-5',
        'title' => Yii::t('preferenceuser', 'Torna in home page'),
    ]);
    ?>
</div>