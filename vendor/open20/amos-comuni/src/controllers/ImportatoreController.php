<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\controllers;

use open20\amos\comuni\models\IstatRegioni;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\gii\CodeFile;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use open20\amos\comuni\models\IstatComuni;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\comuni\AmosComuni;
use open20\amos\comuni\controllers\util\XlsUtil;

class ImportatoreController extends Controller {

  protected 
    $file_directory,
    $dati,
    $result_generate,
    $message_generate,
    $arr_up,
    $arr_down
  ;

  public 
    $layout
  ;

  /**
   * 
   */
  public function init() {
    parent::init();
    
    $this->result_generate = false;
    $this->message_generate = '';
    $this->dati = [];
    $this->arr_up = [];
    $this->arr_down = [];
        
    $this->layout = "@vendor/open20/amos-core/views/layouts/form";
  }

  /**
   * 
   * @return type
   */
  public function actionIndex() {
    return $this->render('index', []);
  }

  /**
   * Cerca il file e ne ritorna il contenuto
   * 
   * @param type $filename  Nome del file all'interno del path /amos-comuni/src/file_import/
   * @param type $type      String [default false] se specificato prova a decodificare il contenuto del file
   * @return type
   * @throws Exception
   */
  protected function loadFileData($filename, $type = false) {
    $file = \Yii::getAlias(
      '@vendor/open20/amos-comuni/src/file_import/'
      . $filename
    );

    if (!file_exists($file)) {
      pr("ERRORE FILE MANCANTE IN {$file}");
    }

    switch (strtolower($type)) {
      case 'json':
        return json_decode(file_get_contents($file));
      case 'xls':
      case 'xlsx':
        if (!XlsUtil::load($file)) {
          throw new Exception("Impossibile caricare il file: {$file}", 1);
        }
        // Leggo il primo foglio
        $content_file = XlsUtil::toArray(0);
        return $content_file;
      default:
        return file_get_contents($file);
    }

  }

  /**
   * Viene creata una migration contenente tutti i comuni da aggiornare (se presenti)
   * 
   * usa il file comuni.json per confrontare il codice castastale salvato a DB con quello del file:
   * se differenti viene impostato per essere aggiornato con quello del file.
   * 
   * @return type
   */
  public function actionImportCodiciCatastali() {
    $file_content = $this->loadFileData('comuni.json', 'json');
    foreach ($file_content as $k => $comune) {
      $cod_catastale_input = $comune->codiceCatastale;

      if (isset($comune->codice)) {
        $comuneRecord = $this->findComuneRecordByCodice($comune->codice);

        if (is_object($comuneRecord) && $comuneRecord->id) {

          // Se ho un codice catastale in input, ed è diverso da quello salvato a DB: aggiorno
          if (!empty($cod_catastale_input) && ($comuneRecord->codice_catastale != $cod_catastale_input)) {
            // Memorizzo i dati che andrei ad aggiornare nella migration
            $this->arr_new[] = [
              'columns' => ['codice_catastale' => $cod_catastale_input],
              'conditions' => ['id' => $comuneRecord->id]
            ];

            // Memorizzo i dati per eventuale ripristino con migrate down
            $this->arr_old[] = [
              'columns' => ['codice_catastale' => $comuneRecord->codice_catastale],
              'conditions' => ['id' => $comuneRecord->id]
            ];

            $this->dati[] = [
              'comuneArray' => $comuneRecord->toArray(), 
              'new_codice_catastale' => $cod_catastale_input
            ];
          }
        }
      } else {
        Yii::$app->getSession()->addFlash('danger', Yii::t('app', 'Codice COMUNE mancante'));
      }
    }

    if (!empty($this->arr_new) && !empty(\Yii::$app->request->post('confirm'))) {
      list($this->result_generate, $this->message_generate) = $this->generateMigrationFile(
        'istat_comuni_update_cod_castastale', 
        'update',
        'istat_comuni',
        $this->arr_new,
        $this->arr_old
      );

      $flash_type = ($this->result_generate === true)
        ? 'success'
        : 'danger'
      ;
      
      Yii::$app->getSession()->addFlash(
        $flash_type, 
        $this->message_generate
      );
    }

    return $this->render(
      'import_codici_catastali', 
      [
        'dati' => $this->dati,
        'url' => Url::current(),
        'generate_result' => $this->result_generate,
        'generate_message' => $this->message_generate,
      ]
    );
  }

  /**
   * Viene creata una migration contenente tutti i cap da inserire (se presenti)
   * 
   * usa il file comuni.json per confrontare i CAP salvati a DB con quelli del file:
   * se ve ne sono di differenti, vengono impostati per essere inseriti a DB.
   * 
   * @return type
   */
  public function actionImportCap() {
    ini_set("memory_limit", "2G");
    
    // Recupero il contenuto del json comuni
    $file_content = $this->loadFileData('comuni.json', 'json');
    foreach ($file_content as $k => $comune) {
      $caps_comune_json = $comune->cap;

      if (isset($comune->codice)) {
        $comuneRecord = $this->findComuneRecordByCodice($comune->codice);

        if (is_object($comuneRecord) && $comuneRecord->id) {
          // Recupero i cap associati a DB
          $caps_comune_db = $comuneRecord->getComuneCaps();

          // Recupero i cap da inserire facendo la differenza tra quelli a DB e quelli in input da file JSON
          $caps_to_insert = array_merge(
            array_diff(
              $caps_comune_db, 
              $caps_comune_json
            ), 
            array_diff(
              $caps_comune_json, 
              $caps_comune_db
            )
          );

          foreach ($caps_to_insert as $k_1 => $cap_to_insert) {
            // Memorizzo gli inserimenti per migrate UP
            $this->arr_up[] = [
              'columns' => ['comune_id', 'cap', 'sospeso'],
              'values' => [$comuneRecord->id, $cap_to_insert, 0],
            ];

            // Memorizzo le cancellazioni per migrate DOWN
            $this->arr_down[] = [
              'conditions' => ['comune_id' => $comuneRecord->id, 'cap' => $cap_to_insert]
            ];
          }
          // Per visualizzazione in anteprima
          if (!empty($caps_to_insert)) {
            $this->dati[] = ['comuneArray' => $comuneRecord->toArray(), 'new_caps' => $caps_to_insert];
          }
        }
      } else {
        Yii::$app->getSession()->addFlash('danger', Yii::t('app', 'Codice COMUNE mancante'));
      }
    }

    if (!empty($this->arr_up) && !empty(\Yii::$app->request->post('confirm'))) {
      list($this->result_generate, $this->message_generate) = $this->generateMigrationFile(
        'istat_comuni_cap_insert', 
        'insert', 
        'istat_comuni_cap', 
        $this->arr_up,
        $this->arr_down
      );

      $flash_type = ($this->result_generate === true)
        ? 'success'
        : 'danger'
      ;
      
      Yii::$app->getSession()->addFlash(
        $flash_type, 
        $this->message_generate
      );
    }

    return $this->render(
      'import_cap',
      [
        'dati' => $this->dati,
        'url' => Url::current(),
        'generate_result' => $this->result_generate,
        'generate_message' => $this->message_generate,
      ]
    );
  }

  /**
   * Usa il file Elenco-comuni-italiani.xls per importare i comuni che non sono presenti a DB:
   *
   * Se a DB il comune ricercato per ID non viene trovato => migration per inserimento
   * Se esiste MA il nome del comune NON corrisponde a quello in input => migration per aggiornamento nome
   *
   * Viene creata:
   *  - una migration contentente tutti i comuni da inserire
   *  - una migration contentente tutti i nomi dei comuni da aggiornare
   *
   * INTESTAZIONI
   * [0] => Codice Regione
   * [1] => Codice Città Metropolitana
   * [2] => Codice Provincia (1)
   * [3] => Progressivo del Comune (2)
   * [4] => Codice Comune formato alfanumerico
   * [5] => Denominazione in italiano
   * [6] => Denominazione in tedesco
   * [7] => Codice Ripartizione Geografica
   * [8] => Ripartizione geografica
   * [9] => Denominazione regione
   * [10] => Denominazione Città metropolitana
   * [11] => Denominazione provincia
   * [12] => Flag Comune capoluogo di provincia
   * [13] => Sigla automobilistica
   * [14] => Codice Comune formato numerico
   * [15] => Codice Comune numerico con 110 province (dal 2010 al 2016)
   * [16] => Codice Comune numerico con 107 province (dal 2006 al 2009)
   * [17] => Codice Comune numerico con 103 province (dal 1995 al 2005)
   * [18] => Codice Catastale del comune
   * [19] => Popolazione legale 2011 (09/10/2011)
   * [20] => Codice NUTS1 2010
   * [21] => Codice NUTS2 2010 (3)
   * [22] => Codice NUTS3 2010
   * [23] => Codice NUTS1 2006
   * [24] => Codice NUTS2 2006 (3)
   * [25] => Codice NUTS3 2006
   * 
   * @return type
   */
  public function actionImportComuni() {
    ini_set("memory_limit", "2G");
    
    $errors = [];
    
    // Recupero il contenuto del xls comuni
    $file_content = $this->loadFileData('Elenco-comuni-italiani.xls', 'xls');
    
    // Salto le intestazioni
    $file_content = array_slice($file_content, 1);
    foreach ($file_content as $k => $row_comune) {
      if (empty($row_comune[0])) {
        continue;
      }
      $cod_istat = $row_comune[4];
      $nome_comune = $row_comune[5];

      // Cerco in istatComuni per cod_istat_alfanumerico e nome
      $q_c = IstatComuni::find()->andWhere(['id' => intval($cod_istat)]);
      $exists = $q_c->count();

      // Se il record esiste già controllo che il nome corrisponda: altrimenti va aggiornato
      if ($exists) {
        $comuneRecord = $q_c->one();
        
        // Se il nome a DB è differente da quello in input
        if ($comuneRecord->nome != $nome_comune) {
          $arr_update[] = [
            'columns' => ['nome' => addslashes($nome_comune)],
            'conditions' => ['id' => $comuneRecord->id]
          ];

          $arr_restore[] = [
            'columns' => ['nome' => addslashes($comuneRecord->nome)],
            'conditions' => ['id' => $comuneRecord->id]
          ];

          //dati per anteprima a video
          $this->dati['update'][] = [
            'comuneArray' => $comuneRecord->toArray(), 
            'nuovo_nome' => $nome_comune
          ];
        }
      } else {
        // Creo un array con tutti i valori da inserire per un record istatComuni
        $arr_values = $this->createArrayValuesByComune($row_comune);
        if (!$arr_values) {
          $nome_provincia = (($row_comune[11]) != '-') ? $row_comune[11] : $row_comune[10];
          $errors[] = "ERRORE: Comune NON trovato <strong>cod.ISTAT:</strong> {$row_comune[4]} <strong>Nome:</strong> {$row_comune[5]} <strong>Regione:</strong> {$row_comune[9]} <strong>Provincia:</strong> {$nome_provincia}";
          continue;
        }
        
        // Le colonne sono prese dalla struttura generata
        $arr_columns = array_keys($arr_values);
        $this->arr_up[] = [
          'columns' => $arr_columns,
          'values' => $arr_values,
        ];

        // Memorizzo le cancellazioni per migrate DOWN
        $this->arr_down[] = [
          'conditions' => ['id' => intval($row_comune[4])]
        ];

        // Per visualizzazione in anteprima
        if (!empty($arr_values)) {
          $this->dati['new'][] = ['comuneArray' => $row_comune];
        }
      }
    }
    
    // Se ho errori li visualizzo a video
    if (!empty($errors)) {
      Yii::$app->getSession()->addFlash(
        'danger', 
        join("<br />", $errors)
      );
    }

    // Genero il file MIGRATION per i NUOVI
    if (!empty($this->arr_up) && !empty(\Yii::$app->request->post('confirm'))) {
      list($this->result_generate, $this->message_generate) = $this->generateMigrationFile(
        'istat_comuni_insert', 
        'insert', 
        'istat_comuni', 
        $this->arr_up,
        $this->arr_down
      );

      $flash_type = ($this->result_generate === true)
        ? 'success'
        : 'danger'
      ;
      Yii::$app->getSession()->addFlash(
        $flash_type, 
        $this->message_generate
      );
      
      // Genero il file MIGRATION per il cambio nome comuni
      list($this->result_generate, $this->message_generate) = $this->generateMigrationFile(
        'istat_comuni_update_nome', 
        'update', 
        'istat_comuni', 
        $arr_update, 
        $arr_restore
      );

      $flash_type = ($this->result_generate === true)
        ? 'success'
        : 'danger'
      ;
      Yii::$app->getSession()->addFlash(
        $flash_type, 
        $this->message_generate
      );
    }

    return $this->render(
      'import_comuni',
      [
        'dati' => $this->dati,
        'url' => Url::current(),
        'errors' => $errors,
        /* 'generate_result' => $this->result_generate,
          'generate_message' => $this->message_generate, */
      ]
    );
  }

  /**
   * Prepara un array con i nomi delle colonne della tabella istat_comuni data una riga del file
   * Ritorna un Array se tutto va bene, false se o Regione o Provinica NON sono state recuperate a DB
   *
   * NB: ricava regione e provincia ID da nome regione e nome provincia (col 9 e 11 (10) dell'array in input)
   * 
   * @param type $comune_row_array  Array rappresentante una riga del file 'Elenco-comuni-italiani.xls'
   * @return array|bool
   */
  private function createArrayValuesByComune($comune_row_array) {
    $RegioneRecord = IstatRegioni::findOne(['nome' => $comune_row_array[9]]);
    $ProvinciaRecord = IstatProvince::findOne(['nome' => $comune_row_array[11]]);
    
    // I campi indicanti la provincia sono "2", se non trovo per il primo, cerco per "denominazione città metropolitana"
    if (!is_object($ProvinciaRecord) || !$ProvinciaRecord->id) {
      $ProvinciaRecord = IstatProvince::findOne(['nome' => $comune_row_array[10]]);
    }

    if (!is_object($RegioneRecord) || !$RegioneRecord->id || !is_object($ProvinciaRecord) || !$ProvinciaRecord->id) {
      return false;
    }

    $ret_array = [
      'id' => intval($comune_row_array[4]),
      'nome' => addslashes($comune_row_array[5]),
      'progressivo' => intval($comune_row_array[3]),
      'nome_tedesco' => $comune_row_array[6],
      'cod_ripartizione_geografica' => $comune_row_array[7],
      'comune_capoluogo_provincia' => $comune_row_array[8],
      'cod_istat_alfanumerico' => $comune_row_array[4],
      'codice_2006_2009' => $comune_row_array[16],
      'codice_1995_2005' => $comune_row_array[17],
      'codice_catastale' => $comune_row_array[18],
      'popolazione_20111009' => $comune_row_array[19],
      'codice_nuts1_2010' => $comune_row_array[20],
      'codice_nuts2_2010' => $comune_row_array[21],
      'codice_nuts3_2010' => $comune_row_array[22],
      'codice_nuts1_2006' => $comune_row_array[23],
      'codice_nuts2_2006' => $comune_row_array[24],
      'codice_nuts3_2006' => $comune_row_array[25],
      'soppresso' => 0,
      'istat_unione_dei_comuni_id' => null,
      'istat_regioni_id' => $RegioneRecord->id,
      'istat_province_id' => $ProvinciaRecord->id,
    ];

    return $ret_array;
  }

  /**
   * Usa il file Variazioni_amministrative_territoriali_dal_01011991.xls per settare sospesi i comuni che non lo sono già a DB:
   *
   * Legge tutti i dati nel file con colonna 'Tipo variazione' = 'ES' cioè estinzione
   * cerca il corrispondente record a DB e se questo NON è giò settato come soppresso => genera migration con i comuni da settare soppressi
   *
   * Viene creata:
   *  - migration con i comuni da settare soppressi
   * 
   * INTESTAZIONI
   * [0] => Anno
   * [1] => Tipo variazione
   * [2] => Codice Regione
   * [3] => Codice Istat del Comune
   * [4] => Denominazione Comune
   * [5] => Codice Istat del Comune associato alla variazione o nuovo codice Istat del Comune
   * [6] => Denominazione Comune associata alla variazione o nuova denominazione
   * [7] => Provvedimento e Documento
   * [8] => Contenuto del provvedimento
   * [9] => Data decorrenza validità amministrativa
   * 
   * @return type
   */
  public function actionImportVariazioniComuni() {
    ini_set("memory_limit", "2G");

    $arr_update = [];
    $arr_restore = [];
    $errors = [];
    
    // Recupero il contenuto del json comuni
    $file_content = $this->loadFileData('Variazioni_amministrative_territoriali_dal_01011991.xls', 'xls');
    
    // Salto le intestazioni
    $file_content = array_slice($file_content, 1);
    foreach ($file_content as $k => $variazione) {
      // Considero SOLO le tipologia ES => estinzione
      if ($variazione['1'] != 'ES') {
        continue;
      }

      $cod_regione_comune = $variazione['2'];
      $cod_istat_comune = $variazione['3'];
      $nome_comune = $variazione['4'];
      $cod_istat_comune_nuovo = $variazione['5'];
      $nome_comune_nuovo = $variazione['6'];

      // Cerco il comune da impostare come soppresso
      $q_c = IstatComuni::find()->andWhere(['id' => intval($cod_istat_comune)]);
      $comuneRecord = $q_c->one();

      if (is_object($comuneRecord) && $comuneRecord->id) {
        // Se il comune è già soppresso a DB: salto
        // NB: NON lo aggiungo come condizione alla query perchè voglio tenere traccia dei record trovati o meno
        if ($comuneRecord->soppresso) {
          continue;
        }
        
        $arr_update[] = [
          'columns' => ['soppresso' => 1],
          'conditions' => ['id' => $comuneRecord->id]
        ];

        $arr_restore[] = [
          'columns' => ['soppresso' => $comuneRecord->soppresso],
          'conditions' => ['id' => $comuneRecord->id]
        ];

        $this->dati['update'][] = ['comuneRecord' => $comuneRecord];
      } else {
        $errors[] = "ERRORE: Comune NON trovato <strong>cod.ISTAT:</strong> {$cod_istat_comune} <strong>Nome:</strong> {$nome_comune}";
      }
    }

    // Se ho errori li visualizzo a video
    if (!empty($errors)) {
      Yii::$app->getSession()->addFlash('danger', join("<br />", $errors));
    }

    // Genero il file MIGRATION per i NUOVI comuni
    if (!empty($arr_update) && !empty(\Yii::$app->request->post('confirm'))) {
      
      // Genero il file MIGRATION per il cambio nome comuni */
      list($this->result_generate, $this->message_generate) = $this->generateMigrationFile(
        'istat_comuni_variazioni', 
        'update', 
        'istat_comuni', 
        $arr_update, 
        $arr_restore
      );

      $flash_type = ($this->result_generate === true)
        ? 'success'
        : 'danger'
      ;
      Yii::$app->getSession()->addFlash(
        $flash_type, 
        $this->message_generate
      );
    }

    return $this->render(
      'import_variazioni_comuni', 
      [
        'dati' => $this->dati,
        'url' => Url::current(),
        /* 'generate_result' => $this->result_generate,
          'generate_message' => $this->message_generate, */
      ]
    );
  }

  /**
   * 
   * @param type $codice_istat  String rappresenta un codice istat nel formato es: 028001
   * @return record|boolean     IstatComuni/ false se problemi
   */
  private function findComuneRecordByCodice($codice_istat) {
    if (!empty($codice_istat)) {
      $id_parsed = intval($codice_istat);
      if ($id_parsed) {
        $record = IstatComuni::findOne(['id' => $id_parsed]);
        if (is_object($record) && $record->id) {
          return $record;
        }
      }
    }
    
    return false;
  }

  /**
   * Crea il file migration in base ai parametri ricevuti in input
   * Ritorna un array con: valore Boolean per esisto operazione, messaggio dell'operazione eseguita
   * 
   * @param type $nome_migration      String nome migration postposta al formato m#DATA_FILE#
   * @param type $type_migration_skel String tipo migration da eseguire ( update/insert )
   * @param type $table_name          String nome tabella
   * @param type $new_data            Array dati per migrationUp
   * @param type $restore_data        Array dati per migrationDown
   * @return type                     Array con result/messaggio operazione
   */
  protected function generateMigrationFile($nome_migration, $type_migration_skel = 'update', $table_name, $new_data, $restore_data) {
    //nome della migration da creare
    $migrationName = 'm' . date('ymd_His') . '_' . $nome_migration;

    // Attivare in caso di debug per evitare che vengano create troppe migration
    /* $migrationName = 'm171231_060001_' . $nome_migration; */

    $path_destination = \Yii::getAlias(
      '@vendor/open20/amos-comuni/src/migrations'
      . '/' 
      . $migrationName . '.php'
    );

    $params = [
      'table_name' => $table_name,
      'new_data' => $new_data,
      'restore_data' => $restore_data,
      'migrationName' => $migrationName,
    ];

    switch ($type_migration_skel) {
      case 'update':
        $path_migration_skel = \Yii::getAlias('@vendor/open20/amos-comuni/src/views/importatore/importatore_migration_skel/MigrationUpdate.php');
        break;
      case 'insert':
        $path_migration_skel = \Yii::getAlias('@vendor/open20/amos-comuni/src/views/importatore/importatore_migration_skel/MigrationInsert.php');
        break;
    }

    $file = new CodeFile(
      $path_destination, 
      $this->renderFile($path_migration_skel, $params)
    );

    if (!$file->save()) {
      return [false, 'Migration NON salvata'];
    }

    return [true, "Migration salvata in: {$path_destination}"];
  }
}