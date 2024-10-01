<?php

use yii\db\Migration;
use open20\amos\documenti\models\DocumentiAgidType;
use open20\amos\documenti\models\DocumentiAgidContentType;

class m201118_192800_insert_value_documenti_type_content_type extends Migration
{


    /**
     * action per l'inserimento veloce dei dati dentro le tabelle
     * documenti_agid_content_type, documenti_agid_type
     *
     * @return void
     */
    public function insertDocumentContentType(){

        // insert value in to table documenti_agid_content_type

        $documenti_agid_content_type = [
            'Bandi',
            'Curriculum Vitae',
            'Atto di nomina',
            'Ordinanze',
            'Modulistica',
            'Atti normativi',
            'Documenti (tecnici) di supporto',
            'Amministrazione trasparente'
        ];

        foreach ($documenti_agid_content_type as $key => $value) {

            // check if already exist
            if(null == DocumentiAgidContentType::find()->where(["name" => $value])->one() ){

                $model_documenti_agid_content_type = new DocumentiAgidContentType;
                $model_documenti_agid_content_type->name = $value;

                $model_documenti_agid_content_type->save();


                if( strcmp($value, 'Bandi') == 0 ){

                    $documenti_agid_type['Bandi'] = [
                        'Bandi di concorso',
                        'Nomine in società ed enti',
                        'Bandi immobiliari',
                        'Bandi per contributi',
                        'Altri bandi e avvisi'
                    ];

                    // insert value in to table documenti_agid_type

                    foreach ($documenti_agid_type['Bandi'] as $key => $value) {

                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }

                }elseif( strcmp($value, 'Curriculum Vitae') == 0 ){

                    $documenti_agid_type['Curriculum Vitae'] = [
                        'Curriculum Vitae'
                    ];

                    foreach ($documenti_agid_type['Curriculum Vitae'] as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }

                }elseif( strcmp($value, 'Atto di nomina') == 0 ){

                    $documenti_agid_type['Atto di nomina'] = [
                        'Atto di nomina'
                    ];

                    foreach ($documenti_agid_type['Atto di nomina']as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }

                }elseif( strcmp($value, 'Ordinanze') == 0 ){

                    $documenti_agid_type['Ordinanze'] = [
                        'Ordinanze'
                    ];

                    foreach ($documenti_agid_type['Ordinanze']as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }

                }elseif( strcmp($value, 'Modulistica') == 0 ){

                    $documenti_agid_type['Modulistica'] = [
                        'Modulistica'
                    ];

                    foreach ($documenti_agid_type['Modulistica']as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }

                }elseif( strcmp($value, 'Atti normativi') == 0 ){

                    $documenti_agid_type['Atti normativi'] = [
                        'Atti normativi',
                        'Statuto comunale',
                        'Regolamenti'
                    ];

                    foreach ($documenti_agid_type['Atti normativi']as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }

                }elseif( strcmp($value, 'Documenti (tecnici) di supporto') == 0 ){

                    $documenti_agid_type['Documenti (tecnici) di supporto'] = [
                        'Pianificazione urbanistica',
                        'Autorizzazioni paesaggistiche',
                        'Pubblicazioni statistiche'
                    ];

                    foreach ($documenti_agid_type['Documenti (tecnici) di supporto']as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                    
                }elseif( strcmp($value, 'Amministrazione trasparente') == 0 ){

                    $documenti_agid_type['Amministrazione trasparente'] = [
                        "Disposizioni generali",
                        "Disposizioni generali - Piano triennale per la prevenzione della corruzione e della trasparenza",
                        "Disposizioni generali - Atti generali",
                        "Disposizioni generali - Documenti di programmazione strategico-gestionale",
                        "Disposizioni generali - Burocrazia zero",
                        "Disposizioni generali - Oneri informativi per cittadini e imprese",
                        "Disposizioni generali - Scadenzario dei nuovi obblighi amministrativi",
                        "Organizzazione",
                        "Organizzazione - Titolari di incarichi politici, di amministrazione, di direzione o di governo",
                        "Organizzazione - Dichiarazioni 2019",
                        "Organizzazione - Mandato 2014-2019",
                        "Organizzazione - Sanzioni per mancata comunicazione dei dati",
                        "Organizzazione - Articolazione degli uffici",
                        "Organizzazione - Telefono e posta elettronica",
                        "Organizzazione - Rendiconti gruppi consiliari regionali/provinciali",
                        "Consulenti e collaboratori",
                        "Consulenti e collaboratori - Titolari di incarichi di collaborazione o consulenza",
                        "Consulenti e collaboratori - Collegio dei Revisori dei Conti – triennio 2015-2018",
                        "Consulenti e collaboratori - Collegio dei Revisori dei Conti – triennio 2018-2021",
                        "Personale",
                        "Personale - Titolari di incarichi dirigenziali amministrativi di vertice",
                        "Personale - Titolari di incarichi dirigenziali (dirigenti non generali)",
                        "Personale - Archivio dichiarazioni insussistenza cause di incompatibilità e inconferibilità",
                        "Personale - Archivio estremi degli atti di conferimento degli incarichi dirigenziali",
                        "Personale - Dirigenti cessati",
                        "Personale - Posizioni Organizzative",
                        "Personale - Archivio Posizioni Organizzative",
                        "Personale - Posizioni Organizzative cessate",
                        "Personale - Dotazione organica",
                        "Personale - Personale non a tempo indeterminato",
                        "Personale - Tassi di assenza",
                        "Personale - Archivio Tassi di assenza",
                        "Personale - Incarichi conferiti e autorizzati ai dipendenti (dirigenti e non dirigenti)",
                        "Personale - Contrattazione collettiva",
                        "Personale - Contrattazione integrativa",
                        "Personale - OIV",
                        "Personale - Sanzioni per mancata comunicazione dei dati",
                        "Bandi di concorso",
                        "Performance",
                        "Performance - Sistema di misurazione e valutazione della Performance",
                        "Performance - Piano della Performance",
                        "Performance - Piano della Performance - 2019-2021",
                        "Performance - Piano della Performance - 2018-2020",
                        "Performance - Piano della Performance - 2017-2019",
                        "Performance - Piano della Performance - 2016-2018",
                        "Performance - Piano della Performance - 2015-2017",
                        "Performance - Piano della Performance - 2014-2016",
                        "Performance - Piano della Performance - Anno 2013",
                        "Performance - Relazione sulla Performance",
                        "Performance - Ammontare complessivo dei premi",
                        "Performance - Dati relativi ai premi",
                        "Performance - Benessere organizzativo",
                        "Enti controllati",
                        "Enti controllati - Enti pubblici vigilati",
                        "Enti controllati - Società partecipate",
                        "Enti controllati - Enti di diritto privato controllati",
                        "Enti controllati - Rappresentazione grafica",
                        "Attività e procedimenti",
                        "Attività e procedimenti - Dati aggregati attività amministrativa",
                        "Attività e procedimenti - Tipologie di procedimento",
                        "Attività e procedimenti - Monitoraggio tempi procedimentali",
                        "Attività e procedimenti - Dichiarazioni sostitutive e acquisizione d'ufficio dei dati",
                        "Provvedimenti",
                        "Provvedimenti - Provvedimenti organi indirizzo politico",
                        "Provvedimenti - Provvedimenti dirigenti amministrativi",
                        "Controlli sulle imprese",
                        "Bandi di gara e contratti",
                        "Bandi di gara e contratti - Informazioni sulle singole procedure in formato tabellare",
                        "Bandi di gara e contratti - Atti delle amministrazioni aggiudicatrici e degli enti aggiudicatori distintamente per ogni procedura",
                        "Bandi di gara e contratti - Atti relativi a procedimenti per i quali non è richiesta l'acquisizione del CIG",
                        "Sovvenzioni, contributi, sussidi, vantaggi economici",
                        "Sovvenzioni, contributi, sussidi, vantaggi economici - Criteri e modalità",
                        "Sovvenzioni, contributi, sussidi, vantaggi economici - Atti di concessione",
                        "Bilanci",
                        "Bilanci - Bilancio preventivo e consuntivo",
                        "Bilanci - Piano degli indicatori e dei risultati attesi di bilancio",
                        "Beni immobili e gestione patrimonio",
                        "Beni immobili e gestione patrimonio - Patrimonio immobiliare",
                        "Beni immobili e gestione patrimonio - Canoni di locazione o affitto",
                        "Controlli e rilievi sull'amministrazione",
                        "Controlli e rilievi sull'amministrazione - Organismi indipendenti di valutazione, nuclei di valutazione o altri organismi con funzioni analoghe",
                        "Controlli e rilievi sull'amministrazione - Organi di revisione amministrativa e contabile",
                        "Controlli e rilievi sull'amministrazione - Corte dei conti",
                        "Servizi erogati",
                        "Servizi erogati - Carta dei servizi e standard di qualità",
                        "Servizi erogati - Costi contabilizzati",
                        "Servizi erogati - Class action",
                        "Servizi erogati - Servizi in rete",
                        "Pagamenti dell'amministrazione",
                        "Pagamenti dell'amministrazione - IBAN e pagamenti informatici",
                        "Pagamenti dell'amministrazione - Indicatore di tempestività dei pagamenti",
                        "Pagamenti dell'amministrazione - Dati sui pagamenti",
                        "Opere pubbliche",
                        "Opere pubbliche - Atti di programmazione delle opere pubbliche",
                        "Opere pubbliche - Tempi costi e indicatori di realizzazione delle opere pubbliche",
                        "Pianificazione e governo del territorio",
                        "Informazioni ambientali",
                        "Interventi straordinari e di emergenza",
                        "Altri contenuti",
                        "Altri contenuti - Prevenzione della Corruzione",
                        "Altri contenuti - Aggiornamento del Piano triennale di prevenzione della corruzione e della trasparenza del Comune di Ferrara - Avviso pubblico",
                        "Altri contenuti - Giornata della trasparenza - anno 2020",
                        "Altri contenuti - Accesso civico",
                        "Altri contenuti - Accessibilità e Catalogo di dati metadati e banche dati",
                        "Altri contenuti - Dati ulteriori",
                        "Altri contenuti - Rilevazione Auto comunali",
                        "Altri contenuti - Referto di controllo di gestione",
                        "Altri contenuti - Destinazione fondi quota 5 per mille IRPEF"
                    ];

                    foreach ($documenti_agid_type['Amministrazione trasparente']as $key => $value) {
                        
                        $model_documenti_agid_type = new DocumentiAgidType;
                        $model_documenti_agid_type->name = $value;
                        $model_documenti_agid_type->agid_document_content_type_id = $model_documenti_agid_content_type->id;

                        $model_documenti_agid_type->save();
                    }
                }

            }else{

                echo "<br>warning! campo esistente, l'inserimento è stato saltato per : ".$value;
            }
        }
    }



    public function safeUp()
    {

        $this->insertDocumentContentType();

    }


    public function safeDown()
    {
       
        return true;
    }
}