<?php

use yii\db\Migration;
use preference\userprofile\models\Tag;

/**
 * Class m210504_164200_tag_labels_update
 */
class m210504_164200_tag_labels_update extends Migration
{

    public function safeUp()
    {
//        CITTADINI
        $this->update('tag'
            , ['descrizione' => 'Iniziative, progetti e incentivi attivati da Regione Lombardia per tutelare la qualità dell\'aria, dell\'ambiente, la biodiversità e la sostenibilità.']
            , ['codice' => 'pctopic_cittadino_2']);
        $this->update('tag'
            , ['descrizione' => 'Notizie su bandi promossi da Regione Lombardia e finanziati con fondi regionali, nazionali ed europei. Avvisi su concorsi e borse di studio.']
            , ['codice' => 'pctopic_cittadino_7']);
        $this->update('tag'
            , ['descrizione' => 'Offerta formativa in Lombardia, agevolazioni e opportunità di inserimento nel mondo del lavoro per giovani e adulti.']
            , ['codice' => 'pctopic_cittadino_6']);
        $this->update('tag'
            , ['descrizione' => 'Notizie utili per la tutela della salute e del benessere personale, la prevenzione e la cura delle malattie.']
            , ['codice' => 'pctopic_cittadino_9']);

//        IMPRESA
        $this->update('tag', ['descrizione' => 'Comunicazioni urgenti e informazioni di pubblica utilità rivolte alle imprese.'], ['codice' => 'pctopic_impresa_8']);
        $this->update('tag', ['descrizione' => 'Iniziative, progetti, bandi, servizi e attività formative promosse da Regione Lombardia per le imprese del settore turistico e culturale.'], ['codice' => 'pctopic_impresa_3']);
        $this->update('tag', ['descrizione' => 'Servizi, incentivi, eventi e opportunità formative messi a disposizione da per l\'innovazione delle aziende lombarde.'], ['codice' => 'pctopic_impresa_1']);
        $this->update('tag', ['descrizione' => 'Aggiornamenti sulle iniziative di formazione continua per lavoratori, imprenditori e liberi professionisti promosse da Regione Lombardia per sviluppare la competitività d’impresa.'], ['codice' => 'pctopic_impresa_6']);
        $this->update('tag', ['descrizione' => 'Agevolazioni per il rinnovo del parco macchine aziendale e aggiornamenti su cambi di viabilità.'], ['codice' => 'pctopic_impresa_4']);
        $this->update('tag', ['descrizione' => 'Notizie utili per la tutela della salute e del benessere personale, la prevenzione e la cura delle malattie.'], ['codice' => 'pctopic_impresa_9']);

//        ENTE E OPERATORE
        $this->update('tag'
            , ['descrizione' => 'Comunicazioni urgenti, informazioni di pubblica utilità e aggiornamenti rivolti a enti e operatori.']
            , ['codice' => 'pctopic_enteeoperatore_8']);
        $this->update('tag'
            , ['descrizione' => 'Iniziative, bandi e servizi rivolti a enti e operatori per sostenere lo sviluppo sostenibile.']
            , ['codice' => 'pctopic_enteeoperatore_2']);
        $this->update('tag'
            , ['descrizione' => 'Notizie su bandi, contributi e agevolazioni promossi da Regione Lombardia a favore di enti e operatori.']
            , ['codice' => 'pctopic_enteeoperatore_7']);
        $this->update('tag'
            , ['descrizione' => 'Comunicazioni, provvedimenti e avvisi ufficiali di Regione Lombardia rivolti a enti locali e operatori.']
            , ['codice' => 'pctopic_enteeoperatore_5']);
        $this->update('tag'
            , ['descrizione' => 'Servizi, incentivi e iniziative messi a disposizione da Regione Lombardia per l\'innovazione di enti e operatori.']
            , ['codice' => 'pctopic_enteeoperatore_1']);
        $this->update('tag'
            , ['descrizione' => 'Notizie utili per la tutela della salute e del benessere personale, la prevenzione e la cura delle malattie.']
            , ['codice' => 'pctopic_enteeoperatore_9']);
        $this->update('tag'
            , ['descrizione' => 'Aggiornamenti su iniziative promosse a favore di enti e operatori per polizia locale, sicurezza stradale e sicurezza urbana.']
            , ['codice' => 'pctopic_enteeoperatore_15']);
        $this->update('tag'
            , ['descrizione' => 'Iniziative attivate da Regione Lombardia per sostenere, attraverso enti e operatori, le persone in difficoltà e favorire la conciliazione vita-lavoro.']
            , ['codice' => 'pctopic_enteeoperatore_12']);

        return true;
    }

    public function safeDown()
    {
//        CITTADINI
        $this->update('tag'
            , ['descrizione' => 'Iniziative, progetti e incentivi  attivati da Regione Lombardia per tutelare la qualità dell\'aria, dell\'ambiente, la biodiversità, la sostenibilità']
            , ['codice' => 'pctopic_cittadino_2']);
        $this->update('tag'
            , ['descrizione' => 'Notizie sui bandi promossi da Regione Lombardia e finanziati con fondi regionali, nazionali ed europei. Avvisi sui concorsi e borse di studio.']
            , ['codice' => 'pctopic_cittadino_7']);
        $this->update('tag'
            , ['descrizione' => 'Offerta formativa in Lombardia, agevolazioni e opportunità di inserimento nel mondo de lavoro per giovani e adulti.']
            , ['codice' => 'pctopic_cittadino_6']);
        $this->update('tag'
            , ['descrizione' => 'Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.']
            , ['codice' => 'pctopic_cittadino_9']);

//        IMPRESA
        $this->update('tag', ['descrizione' => 'Comunicazioni urgenti e informazioni di pubblica utilità rivolte alle aziende.'], ['codice' => 'pctopic_impresa_8']);
        $this->update('tag', ['descrizione' => 'Iniziative, progetti, bandi, servizi e attività formative promosse da Regione Lombardia per per le imprese del settore turistico e culturale.'], ['codice' => 'pctopic_impresa_3']);
        $this->update('tag', ['descrizione' => 'Servizi, incentivi, eventi e opportunità formative messi a disposizione da Regione Lombardia per l\'innovazione delle aziende lombarde.'], ['codice' => 'pctopic_impresa_1']);
        $this->update('tag', ['descrizione' => 'Aggiornamenti sulle iniziative di formazione continua per lavoratori, imprenditori e liberi professionisti promosse da Regione Lombardia per migliorare sviluppare la competitività d’impresa.'], ['codice' => 'pctopic_impresa_6']);
        $this->update('tag', ['descrizione' => 'Agevolazioni stanziate per il rinnovamento parco macchine aziendale e notizie relative al  cambio di viabilità.'], ['codice' => 'pctopic_impresa_4']);
        $this->update('tag', ['descrizione' => 'Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.'], ['codice' => 'pctopic_impresa_9']);

//        ENTE E OPERATORE
        $this->update('tag'
            , ['descrizione' => 'Comunicazioni urgenti, informazioni di pubblica utilità e aggiornamenti specifici rivolti ad enti ed operatori.']
            , ['codice' => 'pctopic_enteeoperatore_8']);
        $this->update('tag'
            , ['descrizione' => 'Iniziative, bandi e servizi rivolti agli enti e operatori per sostenere lo sviluppo sostenibile.']
            , ['codice' => 'pctopic_enteeoperatore_2']);
        $this->update('tag'
            , ['descrizione' => 'Notizie su bandi, contributi e agevolazioni promossi da Regione Lombardia a favore di enti ed operatori.']
            , ['codice' => 'pctopic_enteeoperatore_7']);
        $this->update('tag'
            , ['descrizione' => 'Comunicazioni, provvedimenti e avvisi ufficiali di Regione Lombardia rivolti ad enti locali ed operatori.']
            , ['codice' => 'pctopic_enteeoperatore_5']);
        $this->update('tag'
            , ['descrizione' => 'Servizi, incentivi ed iniziative messi a disposizione da Regione Lombardia per l\'innovazione di enti e operatori.']
            , ['codice' => 'pctopic_enteeoperatore_1']);
        $this->update('tag'
            , ['descrizione' => 'Notizie utili per la tutela della salute personale e la cura delle malattie, informazioni sulla prevenzione e la tutela del benessere.']
            , ['codice' => 'pctopic_enteeoperatore_9']);
        $this->update('tag'
            , ['descrizione' => 'Aggiornamenti sulle iniziative promosse a favore di enti e operatori per polizia locale, sicurezza stradale e sicurezza urbana.']
            , ['codice' => 'pctopic_enteeoperatore_15']);
        $this->update('tag'
            , ['descrizione' => 'Iniziative attivate da Regione Lombardia per sostenere, attraverso enti ed operatori, le persone in difficoltà e favorire la conciliazione vita-lavoro.']
            , ['codice' => 'pctopic_enteeoperatore_12']);

        return true;
    }

}
