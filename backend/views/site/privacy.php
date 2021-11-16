<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\views\site
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\forms\ActiveForm $form
 * @var open20\amos\admin\models\UserProfile $model
 * @var open20\amos\core\user\User $user
 */

//check to avoid whole page having title 'Privacy Policy' when rendered in a modal
if(empty($this->title)) {
    $this->title = Yii::t('amosplatform', 'Privacy Policy');
}

?>

<?php
Modal::begin([
    'id' => 'privacy-policy-modal',
    'header' => Yii::t('amosplatform', "Interessato")
]);
?>
<div>
    <?= Yii::t('amosplatform', 'Ai sensi dell’art. 4, co.1, lett. i del Codice Privacy, "interessato" è “la persona fisica cui si riferiscono i dati personali”. In questo caso, sono gli utenti che compilano il form con i propri dati per usufruire del servizio.') ?>
</div>
<?php
Modal::end();
?>

<!--<section>-->
<!--    <div class="row">-->
<!--        <div class="col-xs-12">-->
<!--            <h1 class="text-center text-uppercase bold">< ?= Yii::t('amosplatform', 'Privacy Policy') ?></h1>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<section>
    <p>
        <?= Yii::t('amosplatform', "In questa pagina si descrivono le modalità di gestione del sito in riferimento al trattamento dei dati personali degli utenti che lo consultano. Si tratta di un’informativa che è resa anche ai sensi dell'art. 13 del D.lgs. 196/03 – Codice in materia di protezione dei dati personali - a coloro che interagiscono con il portale web Open Innovation per la protezione dei dati personali, accessibili per via telematica a partire dall'indirizzo:'") ?>
    </p>
    <p>
        <?= Html::a('http://www.openinnovation.regione.lombardia.it', 'http://www.openinnovation.regione.lombardia.it') ?>.
    </p>
    <p>
        <?= Yii::t('amosplatform', "La Privacy Policy è resa solo per il sito suddetto e non anche per altri siti web eventualmente consultati dall'utente tramite link.") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', 'Scopo del servizio') ?></h4>
    <p>
        <?= Yii::t('amosplatform', "La piattaforma di Open Innovation nasce con l'obiettivo di offrire agli operatori dell'innovazione uno spazio di confronto e condivisione per lo scambio di conoscenza e la definizione di progettualità collaborative, facilitare la creazione di partenariati e l'accesso a reti, servizi e piattaforme internazionali aventi scopo coerente con gli obiettivi sopra enunciati, promuovere il confronto fra gli stakeholder regionali e i destinatari delle politiche di supporto alla ricerca, innovazione e competitività, consentire un dialogo continuo con il territorio nella definizione delle politiche e degli strumenti regionali di supporto.'") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "Attraverso la piattaforma possono essere pubblicate notizie, annunci di discussioni, proposte di collaborazione che rientrino nello scopo dell'iniziativa, ovvero promuovere il dialogo e facilitare l'aggregazione di competenze intorno alle tematiche della strategia regionale di specializzazione intelligente per la ricerca e l'innovazione. Non sono in ogni caso ammesse attività finalizzate alla promozione di prestazioni meramente commerciali, intese come la fornitura di un servizio contro corrispettivo.'") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', 'Finalità del trattamento') ?></h4>
    <p>
        <?= Yii::t('amosplatform', "I dati personali forniti al momento della registrazione (rif. Dati di registrazione) verranno utilizzati allo scopo di consentire l'accesso alla piattaforma. Oltre alla finalità riportata precedentemente i dati conferiti potranno essere trattati per l’invio di comunicazioni inerenti alla piattaforma (es. newsletter).'") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', 'Titolare e Responsabili') ?></h4>
    <p>
        <?= Yii::t('amosplatform', "Titolare del trattamento dei dati è Regione Lombardia, con sede in Milano, Piazza Città di Lombardia 1, nella persona del Legale Rappresentante pro-tempore.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "Responsabili del trattamento sono Finlombarda S.p.A., che svolge attività e compiti strumentali e correlati alla fornitura del servizio e, Lombardia Informatica S.p.A., che svolge attività di gestione e manutenzione dei sistemi informativi per conto di Regione Lombardia.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "Entrambi sono designati come Responsabili del trattamento, ai quali Regione Lombardia ha impartito istruzioni operative al fine di poter garantire la riservatezza e la sicurezza dei dati.") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', 'Dati di registrazione') ?></h4>
    <p>
        <?= Yii::t('amosplatform', "I dati personali conferiti dagli utenti in fase registrazione per consentire l’accesso ai servizi on-line di Open Innovation [e/o raccolti successivamente in fase di utilizzo dei servizi stessi, ivi inclusi quelli relativi al traffico telematico (a titolo meramente esemplificativo, l'indirizzo Internet IP)], saranno trattati per la gestione del rapporto contrattuale e l'erogazione dei servizi, per la manutenzione e l’assistenza tecnica; per la gestione di eventuali reclami e contenziosi e per la prevenzione/repressioni di frodi e di qualsiasi attività illecita. Potranno, inoltre, ovviamente, essere trattati per adempiere ad obblighi di legge.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "Per accedere al portale Open Innovation di Regione Lombardia è necessario registrarsi inserendo un’email valida, Nome e Cognome. Una password temporanea, da modificare al primo accesso, verrà inviata all’indirizzo email indicato.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "È anche possibile accedere e/o registrarsi a Open Innovation tramite un Social Network, utilizzando alcuni dati già inseriti all’interno del Social Network. I principali Social Network utilizzati sono:") ?>
    </p>
    <div>
        <ul>
            <li>
                <?= Yii::t('amosplatform', "Linkedin: quando si accede tramite Linkedin, sono richieste le seguenti informazioni:") ?>
                <ul>
                    <li><?= Yii::t('amosplatform', "Panoramica del profilo (la quale include a sua volta nome, foto, sommario e posizione attuale del profilo in oggetto);") ?></li>
                    <li><?= Yii::t('amosplatform', "Indirizzo email, utilizzato anche per l’accesso a Linkedin.") ?></li>
                </ul>
            </li>
            <li>
                <?= Yii::t('amosplatform', "Facebook: accedendo tramite Facebook, i dati che vengono richiesti sono relativi al profilo pubblico e indirizzo email inseriti in Facebook.") ?>
            </li>
            <li>
                <?= Yii::t('amosplatform', "Twitter: se viene scelta questa modalità di accesso saranno richiesti l’indirizzo email e la password utilizzati per il proprio profilo Twitter, e le informazioni che potranno essere trattate sono le seguenti:") ?>
                <ul>
                    <li><?= Yii::t('amosplatform', "I Tweet della propria cronologia;") ?></li>
                    <li><?= Yii::t('amosplatform', "Le persone che sono seguite;") ?></li>
                    <li><?= Yii::t('amosplatform', "Aggiornamento del profilo;") ?></li>
                    <li><?= Yii::t('amosplatform', "Pubblicazione di Tweet dall’account.") ?></li>
                </ul>
            </li>
            <li>
                <?= Yii::t('amosplatform', "Google Plus: l’accesso tramite questo Social Network richiede accesso a:") ?>
                <ul>
                    <li><?= Yii::t('amosplatform', "L'email utilizzata per il profilo di Google Plus") ?></li>
                    <li><?= Yii::t('amosplatform', "Nome completo, l'immagine del profilo e l'URL del profilo") ?></li>
                    <li><?= Yii::t('amosplatform', "Tutte le informazioni di dominio pubblico sul proprio profilo Google+ (se ne esiste uno o se ne verrà creato uno in futuro).") ?></li>
                </ul>
            </li>
        </ul>
    </div>
    <p>
        <?= Yii::t('amosplatform', "L'ulteriore autorizzazione al trattamento dei dati richiesti dai connettori suddetti viene richiesta agli utenti in fase di registrazione dal social network corrispondente.") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', 'Dati di navigazione') ?></h4>
    <p>
        <?= Yii::t('amosplatform', "I sistemi informatici e le procedure software preposte al funzionamento di questo sito web acquisiscono, nel corso del loro normale esercizio, alcuni dati personali la cui trasmissione è implicita nell’uso dei protocolli di comunicazione di Internet. Si tratta di informazioni che non sono raccolte per essere associate a interessati identificati, ma che per loro stessa natura potrebbero, attraverso elaborazioni ed associazioni con dati detenuti da terzi, permettere di identificare gli utenti. In questa categoria di dati rientrano gli indirizzi IP o i nomi a dominio dei computer utilizzati dagli utenti che si connettono al sito e altri parametri relativi al sistema operativo e all’ambiente informatico dell’utente. Questi dati vengono utilizzati al solo fine di ricavare informazioni statistiche anonime sull’uso del sito e per controllarne il corretto funzionamento e vengono cancellati immediatamente dopo l’elaborazione. I dati potrebbero essere utilizzati per l’accertamento di responsabilità in caso di ipotetici reati informatici ai danni del sito: salva questa eventualità, allo stato i dati sui contatti web non persistono.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "Per maggiori dettagli si rimanda alla specifica") ?> <?= Html::a("informativa sull'uso dei cookies", ['/site/cookies']) ?>.
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', "Dati forniti volontariamente dall'utente") ?></h4>
    <p>
        <?= Yii::t('amosplatform', "L'obiettivo della piattaforma di Open Innovation si realizza principalmente attraverso la condivisione e la pubblicazione di informazioni - inclusi i dati di contatto - mediante la piattaforma stessa ed eventualmente mediante il trasferimento a soggetti terzi sulla base di specifici accordi (es. piattaforme e broker internazionali di Open Innovation, reti internazionali di supporto all'innovazione e alla competitività, ecc.).") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "L'utilizzatore si impegna a non condividere mediante la piattaforma informazioni circa dati personali di terze persone non autorizzati e informazioni protette da copyleft, marchi registrati, brevetti, segreti aziendali o altre proprietà intellettuali, personali, contrattuali, di proprietà o diritti di parti terze senza l'esplicito permesso del titolare dei rispettivi diritti. L'utente è l'unico responsabile per danni risultanti dal mancato ottenimento di permessi o da altri danni direttamente o indirettamente cagionati risultanti da contenuti conferiti e/o condivisi attraverso la piattaforma.") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', "Modalità del trattamento") ?></h4>
    <p>
        <?= Yii::t('amosplatform', "Il trattamento dei dati è effettuato nel rispetto della Legge 196/03 in materia di protezione dei dati personali.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "Il trattamento è effettuato principalmente con l’ausilio di mezzi elettronici o comunque automatizzati. Il trattamento dei dati sarà effettuato con logiche di organizzazione ed elaborazione correlate alle finalità di cui alla presente informativa e, comunque, in modo da garantire la sicurezza e la riservatezza dei dati.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "I dati conferiti saranno trattati e conservati per un periodo di tempo non superiore a quello necessario agli scopi per i quali i dati sono stati raccolti o successivamente trattati e comunque nel rispetto delle procedure privacy interne.") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', "I dati possono essere comunicati, in caso di richiesta, alle autorità competenti, in adempimento ad obblighi derivanti da norme inderogabili di legge.") ?>
    </p>

    <br>
    <h4 class="bold"><?= Yii::t('amosplatform', "Diritti degli interessati") ?></h4>
    <p>
        <?= Yii::t('amosplatform', "Gli") . ' ' . Html::a("interessati", null, [
            'data-toggle' => 'modal',
            'data-target' => '#privacy-policy-modal'
        ]) . ' ' . Yii::t('amosplatform', "potranno esercitare in ogni momento i diritti previsti dall’art. 7 del D.lgs.196/2003, consultabile in \"Decreto Legislativo n.196/2003, Art. 7 - Diritto di accesso ai dati personali ed altri diritti\", fra cui quello di ottenere la conferma dell’esistenza dei dati che li riguardano, chiederne l'origine, conoscere l’elenco aggiornato dei Responsabili; verificare l’esattezza dei dati, chiederne la correzione, l'aggiornamento o l'integrazione ovvero la cancellazione o il blocco per quelli trattati in violazione di legge o ancora opporsi al loro utilizzo per motivi legittimi da evidenziare nella richiesta, contattando il Titolare del trattamento.'") ?>
    </p>
    <p>
        <?= Yii::t('amosplatform', 'Il presente documento costituisce la "Privacy Policy" di questo sito.') ?>
    </p>
</section>
