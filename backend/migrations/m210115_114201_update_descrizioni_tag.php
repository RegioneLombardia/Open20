<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m210115_114201_update_descrizioni_tag extends \yii\db\Migration
{

    public  function safeUp()
    {

        $sqlUpdate =

            "
            Update tag set descrizione = \"Progetti, eventi e incontri promossi da Regione Lombardia per supportare l'innovazione.\" where codice = \"pctopic_cittadino_1\";
            Update tag set descrizione = \"Iniziative, progetti e incentivi  attivati da Regione Lombardia per tutelare la qualità dell'aria, dell'ambiente, la biodiversità, la sostenibilità\" where codice = \"pctopic_cittadino_2\";
            Update tag set descrizione = \"Idee e proposte per il tempo libero: dalla cultura (mostre, musei, concerti) al territorio (agriturismi, rifugi, itinerari turistici).\" where codice = \"pctopic_cittadino_3\";
            Update tag set descrizione = \"Aggiornamenti su cambi di viabilità, tariffe e abbonamenti agevolati per i mezzi pubblici, incentivi per la mobilità sostenibile.\" where codice = \"pctopic_cittadino_4\";
            Update tag set descrizione = \"Comunicazioni, provvedimenti, ordinanze e avvisi ufficiali di Regione Lombardia.\" where codice = \"pctopic_cittadino_5\";
            Update tag set descrizione = \"Offerta formativa in Lombardia, agevolazioni e opportunità di inserimento nel mondo de lavoro per giovani e adulti.\" where codice = \"pctopic_cittadino_6\";
            Update tag set descrizione = \"Notizie sui bandi promossi da Regione Lombardia e finanziati con fondi regionali, nazionali ed europei. Avvisi sui concorsi e borse di studio.\" where codice = \"pctopic_cittadino_7\";
            Update tag set descrizione = \"Informazioni e segnalazioni di pubblica utilità, sia in prevenzione (es. allerta meteo) che in emergenza (es. sanitaria, idrogeologica). \" where codice = \"pctopic_cittadino_8\";
            Update tag set descrizione = \"Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.\" where codice = \"pctopic_cittadino_9\";
            Update tag set descrizione = \"Grandi eventi, servizi, impianti, incentivi per la pratica dello sport in Lombardia.\" where codice = \"pctopic_cittadino_10\";
            Update tag set descrizione = \"Eventi e iniziative di promozione dei prodotti agroalimentari lombardi e di educazione alimentare.\" where codice = \"pctopic_cittadino_11\";
            Update tag set descrizione = \"Iniziative attivate da Regione Lombardia per sostenere le persone in difficoltà e le famiglie in condizioni di vulnerabilità economica e sociale.\" where codice = \"pctopic_cittadino_12\";
            Update tag set descrizione = \"Servizi, incentivi, eventi e opportunità formative messi a disposizione da per l'innovazione delle aziende lombarde.\" where codice = \"pctopic_impresa_1\";
            Update tag set descrizione = \"Iniziative e incentivi per sostenere le imprese impegnate nello sviluppo sostenibile.\" where codice = \"pctopic_impresa_2\";
            Update tag set descrizione = \"Iniziative, progetti, bandi, servizi e attività formative promosse da Regione Lombardia per per le imprese del settore turistico e culturale.\" where codice = \"pctopic_impresa_3\";
            Update tag set descrizione = \"Agevolazioni stanziate per il rinnovamento parco macchine aziendale e notizie relative al  cambio di viabilità.\" where codice = \"pctopic_impresa_4\";
            Update tag set descrizione = \"Comunicazioni, provvedimenti e avvisi ufficiali di Regione Lombardia rivolti agli operatori  del commercio, dell'impresa e dell'artigianato.\" where codice = \"pctopic_impresa_5\";
            Update tag set descrizione = \"Aggiornamenti sulle iniziative di formazione continua per lavoratori, imprenditori e liberi professionisti promosse da Regione Lombardia per migliorare sviluppare la competitività d’impresa.\" where codice = \"pctopic_impresa_6\";
            Update tag set descrizione = \"Notizie su bandi, gare, manifestazioni di interesse, premi e agevolazioni promossi da Regione Lombardia a favore delle imprese. \" where codice = \"pctopic_impresa_7\";
            Update tag set descrizione = \"Comunicazioni urgenti e informazioni di pubblica utilità rivolte alle aziende.\" where codice = \"pctopic_impresa_8\";
            Update tag set descrizione = \"Informazioni, aggiornamenti normativi, progetti e finanziamenti per gli operatori commerciali.\" where codice = \"pctopic_impresa_13\";
            Update tag set descrizione = \"Informazioni, servizi, avvisi, bandi e agevolazioni per gli operatori del settore agricolo e agroalimentare.\" where codice = \"pctopic_impresa_14\";
            Update tag set descrizione = \"Servizi, incentivi ed iniziative messi a disposizione da Regione Lombardia per l'innovazione di enti e operatori.\" where codice = \"pctopic_enteeoperatore_1\";
            Update tag set descrizione = \"Iniziative, bandi e servizi rivolti agli enti e operatori per sostenere lo sviluppo sostenibile.\" where codice = \"pctopic_enteeoperatore_2\";
            Update tag set descrizione = \"Notizie su bandi, finanziamenti, servizi e banche dati messe a disposizione di Regione Lombardia per promuovere la cultura e il turismo sul territorio.\" where codice = \"pctopic_enteeoperatore_3\";
            Update tag set descrizione = \"Novità in materia di bandi, finanziamenti, regolamentazioni della mobilità (strade, autostrade, elettrica, ciclistica), dei trasporti pubblici e della navigazione interna.  \" where codice = \"pctopic_enteeoperatore_4\";
            Update tag set descrizione = \"Comunicazioni, provvedimenti e avvisi ufficiali di Regione Lombardia rivolti ad enti locali ed operatori. \" where codice = \"pctopic_enteeoperatore_5\";
            Update tag set descrizione = \"Aggiornamenti sul Piano regionale  dell'offerta formativa, servizi e opportunità per gli operatori, eventi e progetti per le scuole.\" where codice = \"pctopic_enteeoperatore_6\";
            Update tag set descrizione = \"Notizie su bandi, contributi e agevolazioni promossi da Regione Lombardia a favore di enti ed operatori. \" where codice = \"pctopic_enteeoperatore_7\";
            Update tag set descrizione = \"Comunicazioni urgenti, informazioni di pubblica utilità e aggiornamenti specifici rivolti ad enti ed operatori.\" where codice = \"pctopic_enteeoperatore_8\";
            Update tag set descrizione = \"Iniziative, bandi e finanziamenti attivati da Regione Lombardia a favore di enti e operatori per sostenere la pratica sportiva.\" where codice = \"pctopic_enteeoperatore_10\";
            Update tag set descrizione = \"Iniziative attivate da Regione Lombardia per sostenere, attraverso enti ed operatori, le persone in difficoltà e favorire la conciliazione vita-lavoro.\" where codice = \"pctopic_enteeoperatore_12\";
            Update tag set descrizione = \"Aggiornamenti sulle iniziative promosse a favore di enti e operatori per polizia locale, sicurezza stradale e sicurezza urbana.\" where codice = \"pctopic_enteeoperatore_15\";
            Update tag set descrizione = \"Aggiornamenti in materia di Piani territoriali, paesaggio, difesa del suolo, assetti idrogeologici, valutazioni ambientali.\" where codice = \"pctopic_enteeoperatore_16\";
            Update tag set descrizione = \"Notizie, bandi e informazioni per gli enti e gli operatori del settore sanitario.\" where codice = \"pctopic_enteeoperatore_17\";
                 
                                                              
            ";
        $this->execute($sqlUpdate);

        return true;
    }

}
