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
    <div class="privacy-text-container mb-4">
        <p class="tertiary-color">
            Le seguenti norme sulla privacy – da intendersi rese anche quale informativa ai sensi dell’art. 13 del Regolamento europeo 2016/679 “GDPR – hanno lo scopo di descrivere le procedure di raccolta e utilizzo dei dati personali attraverso il sito internet shoptoiletpaper.com e sono rivolte agli utenti e ai fruitori dei relativi servizi.
            Titolare del trattamento dei dati personali è Regione Lombardia - Piazza Città di Lombardia, 1 20124 Milano v.8.3.04-278156-06052019
        </p>

        <p class="tertiary-color">
        FINALITÀ DEL TRATTAMENTO </br>
        Il Titolare tratta i dati personali, esclusivamente di natura non sensibile, comunicati dall’interessato in fase di contatto effettuato con la compilazione dell’apposito form, ovvero con l’accesso alle sezioni dedicate agli acquisti e con l’iscrizione al servizio di newsletter.
        </p>

        <p class="tertiary-color">
        Tali dati saranno trattati – anche senza l’espresso consenso dell’interessato ed in conformità all’art. 6 lett. b, c ed f del GDPR – per le seguenti finalità:
        </p>
        <p class="tertiary-color">
            <ul class="tertiary-color">
                <li>permettere la registrazione all’area riservata del sito web;</li>
                <li>permettere all’utente di procedere all’acquisto di beni e compiere, quindi, qualsivoglia attività connessa (selezione dei prodotti, invio degli ordini e relativa accettazione, consegna, esercizio del diritto di recesso e ritiro dei beni, ovvero ogni altro adempimento necessario previsto dalle condizioni di vendita);</li>
                <li>permettere l’iscrizione al servizio di newsletter fornito dal Titolare e degli ulteriori servizi eventualmente richiesti;</li>
                <li>procedere all’invio di newsletter informative all’utente registrato al relativo servizio;</li>
                <li>adempiere agli obblighi precontrattuali, contrattuali e fiscali;</li>
                <li>riscontrare le richieste avanzate dall’interessato;</li>
                <li>inviare comunicazioni di servizio, anche con riferimento a modifiche di termini contrattuali, contenuti, condizioni e politiche adottate;</li>
                <li>adempiere agli obblighi derivanti dalla legge, da regolament, dalla normativa comunitaria o da un ordine dell’Autorità;</li>
                <li>prevenire o scoprire attività fraudolente o abusive nei confronti del sito web;</li>
                <li>esercitare ogni altro diritto del Titolare, come il diritto di difesa in giudizio, nonché ogni altro trattamento compatibile con le predette finalità.</li>
            </ul>
        </p>
        <p class="tertiary-color">
        I medesimi dati saranno invece trattati, solo previo libero e specifico consenso dell’interessato – manifestato attraverso flag su box dedicato – per il perseguimento delle seguenti finalità:
        </p>
        <p class="tertiary-color">
        – invio di comunicazioni commerciali e/o materiale pubblicitario;
        </p>
        <p class="tertiary-color">
        Si precisa che il consenso prestato dall’interessato può sempre essere revocato, contattando il Titolare all’indirizzo indicato, oppure tramite gestione diretta con ingresso nel profilo personale.
        </p>
        <p class="tertiary-color">
        DURATA DEL TRATTAMENTO
        I dati personali saranno trattati per il tempo necessario a conseguire gli scopi per i quali sono stati raccolti. Si evidenzia in particolare che i dati relativi e connessi alle operazioni di vendita dei beni saranno conservati per il tempo previsto per l’esecuzione del contratto di acquisto, per le attività post vendita, e successivamente, per il tempo necessario a soddisfare finalità contabili e legali. In ogni caso è fatto salvo un periodo di conservazione superiore, ove richiesto da norme di legge o regolamento.
        </p>
        <p class="tertiary-color">
        COMUNICAZIONE A TERZI
        I dati potranno essere comunicati a soggetti pubblici e privati, persone fisiche e/o giuridiche (studi di consulenza legale, amministrativa e fiscale, spedizionieri e corrieri, eventuali società informatiche ed altri) nei cui confronti la comunicazione sia necessaria per il perseguimento di finalità contrattuali, amministrative, contabili, nonché per garantire agli interessati l’utilizzo e la fruizione del sito web. I dati potranno essere comunicati altresì ad altri soggetti, quando la comunicazione sia prevista o imposta dalla legge.
        </p>
        <p class="tertiary-color">
        LUOGO DEL TRATTAMENTO
        I dati personali saranno trattati all’interno del territorio dell’Unione Europea. Qualora per questioni di natura tecnica e/o operativa si renda necessario avvalersi di soggetti ubicati al di fuori dell’Unione Europea, si garantisce sin d’ora che il trasferimento a tali soggetti, limitatamente allo svolgimento di specifiche attività di Trattamento, sarà effettuato in conformità a quanto previsto dal GDPR.
        </p>
        <p class="tertiary-color">
        DIRITTI DEGLI INTERESSATI
        Ai sensi degli artt. 13, 15-22 del GDPR, l’interessato ha il diritto, in particolare:
        </p>
        <p class="tertiary-color">
            <ul>
                <li>di ottenere la conferma che sia o meno in corso un trattamento di dati personali che lo riguardano;</li>
                <li>di ottenere l’accesso ai dati e alle seguenti informazioni (finalità del trattamento, categorie di dati personali, destinatari e/o categorie di destinatari, periodo di conservazione);</li>
                <li>di ottenere la rettifica o integrazione dei dati personali inesatti che lo riguardano;</li>
                <li>di ottenere la cancellazione dei dati personali che lo riguardano nei casi previsti dall’art. 17 GDPR;</li>
                <li>di ottenere che i dati personali che lo riguardano siano solo conservati senza che di essi sia fatto altro uso nei casi previsti dall’art. 18 GDPR;</li>
                <li>di ricevere i dati personali che lo riguardano trattati con mezzi automatizzati, se essi sono trattati in forza di contratto o sulla base del suo consenso.</li>
            </ul>
        </p>
        <p class="tertiary-color">
        Infine, l’interessato ha diritto di rivolgersi all’Autorità di controllo per presentare reclamo.
        </p>
        <p class="tertiary-color">
        Per qualsiasi richiesta o comunicazione, ovvero per esercitare i suoi diritti, l’interessato può contattare il Titolare del trattamento inviando una e-mail all’indirizzo info@shoptoiletpaper.com.
        </p>
        <p class="tertiary-color">
        COOKIES
        Utilizziamo i cookie per registrare informazioni su alcune specifiche della sessione, come ad esempio gli articoli che aggiungi al carrello acquisti. Utilizziamo i coockies per registrare informazioni sulle pagine visitate e per migliorare il servizio quando il cliente torna sul nostro sito, li usiamo anche per personalizzare i contenuti delle pagine web in base al tipo di browser utilizzato dal cliente o ad altre informazioni riferiteci dal cliente.
        </p>
        <p class="tertiary-color">
        MISURE DI SICUREZZA
        Il nostro server permette alla transazione di utilizzare la tecnologia crittografata secondo gli standard del settore e di ricevere lo scambio di dati tra noi e il consumatore. Quando trasferiamo e riceviamo alcuni tipi di informazioni sensibili come informazioni personali o finanziarie, reindirizziamo i visitatori su un server sicuro. Qui abbiamo le misure di sicurezza appropriate per proteggere la perdita, l’uso improprio o l’alterazione delle informazioni che abbiamo su di te. Tutti i nostri articoli sono nuovi e garantiti dalla fabbrica. Se per qualche motivo un articolo dovesse risultare vendibile lo renderà noto al nostro sito web. Tutte le transazioni effettuate tramite carte di credito verranno elaborate dal server di pagamento PayPal ®.
        </p>
        <p class="tertiary-color">
        MODIFICHE ALLA PRESENTE POLICY
        Il Titolare si riserva la facoltà di aggiornare in qualsiasi momento la presente informativa. Si consiglia, pertanto, di procedere a periodiche verifiche, al fine di essere aggiornati sulla privacy policy della Società. In caso di modifiche sostanziali alla politica, verrà pubblicato un avviso sul Sito, insieme alla Politica sulla privacy aggiornata.
        </p>
    </div>

    <div class="privacy-check-container">
    
        <?= $form->field($model, 'privacy')->checkbox() ?>
        
    </div>
</div>

    
<div class="container wizard-button-container d-flex flex-row justify-content-center justify-content-sm-between mb-0 mb-sm-5"> 
    <div class="d-flex flex-row">
        <?php
        echo Html::submitButton(
            'Accetta',
            ['class' => 'btn btn-primary px-5', 'name' => 'submit-action', 'value' => 'forward']
        );
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
