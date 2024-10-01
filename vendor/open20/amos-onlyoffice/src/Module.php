<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\onlyoffice
 * @category   CategoryName
 */
namespace open20\onlyoffice;

use open20\amos\attachments\models\File;
use open20\amos\core\module\AmosModule;
use open20\onlyoffice\models\OnlyofficeFiles;
use yii\base\InvalidConfigException;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\ModelEvent;

/**
 * Class Module
 *
 * @package open20\onlyoffice
 */
class Module extends AmosModule implements BootstrapInterface {
    /**
     * URL del server che fornisce i servizi di OnlyOffice
     * @var string
     */
    public $docServSiteUrl;
    /**
     * Chiave JWT fornita dal server per la modifica e la lettura dei documenti
     * @var string
     */
    public $docServJwtSecret;
    /**
     * Prefisso da assegnare alle chiavi univoce dei file in modo da pemettere
     * di usare il server dei servizi di OnlyOffice con piu' applicativi
     * @var string
     */
    public $keyFilePrefixName;

    /**
     * Path in cui si trovano i file di esempio/vuoti
     * @var string
     */
    public $samplesPath = '@vendor/open20/amos-onlyoffice/src/assets/samples/';
    /**
     * Array che ha come indici i tipi di file (le estensioni senza punto) e
     * come valori i nomi dei relativi file di esempio/vuoti
     * @var array
     */
    public $sampleFiles = [
        'csv' => 'csv.csv',
        'docx' => 'sample.docx',
        'docxf' => 'sample.docxf',
        'pptx' => 'sample.pptx',
        'xlsx' => 'sample.xlsx',
    ];
    /**
     * Un array che elenca tutti i tipi gestibili con il modulo: gli indici sono
     * gli identificativi dei tipi gestibili (le estensioni senza punto) e i
     * valori sono le descrizioni. Questo array contiene quelli effettivamente
     * gestibili da OnlyOffice
     * @var array
     */
    protected $tipiGestibiliOrig = [
        'csv' => 'CSV',
        'docx' => 'Documento di Word (docx)',
        'docxf' => 'Form',
        'pptx' => 'Presentazione di Power Point (pptx)',
        'xlsx' => 'Foglio di Calcolo Excel (xlsx)',
    ];
    /**
     * Un array che elenca tutti i tipi gestibili con il modulo: gli indici sono
     * gli identificativi dei tipi gestibili (le estensioni senza punto) e i
     * valori sono le descrizioni
     * @var array
     */
    public $tipiGestibili = [
        'docx' => 'Documento di Word (docx)',
        'pptx' => 'Presentazione di Power Point (pptx)',
        'xlsx' => 'Foglio di Calcolo Excel (xlsx)',
    ];
    /**
     * Un array che ha come indici i possibili tipi di documenti (parametro
     * 'documentType' dell'editor) e come valori le estensioni relative ad ogni
     * tipo di documento. Questo array contiene quelli effettivamente gestibili
     * da OnlyOffice
     * @var array
     */
    protected $docTypeAndExtensionOrig = [
        'word' => ['doc', 'docm', 'docx', 'docxf', 'dot', 'dotm', 'dotx',
            'epub', 'fodt', 'fb2', 'htm', 'html', 'mht', 'odt', 'oform', 'ott',
            'oxps', 'pdf', 'rtf', 'txt', 'djvu', 'xml', 'xps'],
        'cell' => ['csv', 'fods', 'ods', 'ots', 'xls', 'xlsb', 'xlsm', 'xlsx',
            'xlt', 'xltm', 'xltx'],
        'slide' => ['fodp', 'odp', 'otp', 'pot', 'potm', 'potx', 'pps', 'ppsm',
            'ppsx', 'ppt', 'pptm', 'pptx'],
    ];
    /**
     * Un array che ha come indici i possibili tipi di documenti (parametro
     * 'documentType' dell'editor) e come valori le estensioni relative ad ogni
     * tipo di documento
     * @var array
     */
    public $docTypeAndExtension = [
        'word' => ['doc', 'docx', 'odt'],
        'cell' => ['ods', 'xls', 'xlsx'],
        'slide' => ['odp', 'ppt', 'pptx'],
    ];
    
    public static $registerEvent = true;

    /**
     * URL relativo del server di OnlyOffice con cui integraggire con le API
     * @var string
     */
    public $docServApiUrl = 'web-apps/apps/api/documents/api.js';

//    public $version = '1.3.0';
//
//    public $filesizemax = 5242880;
//    public $storagePath = '/storage';
//    public $alone = true;
//
//    public $docServFillforms = ['.oform', '.docx'];
//    public $docServViewd = ['.pdf', '.djvu', '.xps', '.oxps'];
//    public $docServEdited = ['.docx', '.xlsx', '.csv', '.pptx', '.txt',
//        '.docxf'];
//    public $docServConvert = ['.docm', '.doc', '.dotx', '.dotm', '.dot', '.odt',
//        '.fodt', '.ott', '.xlsm', '.xlsb', '.xls', '.xltx', '.xltm', '.xlt',
//        '.ods', '.fods', '.ots', '.pptm', '.ppt', '.ppsx', '.ppsm', '.pps',
//        '.potx', '.potm', '.pot', '.odp', '.fodp', '.otp', '.rtf', '.mht',
//        '.html', '.htm', '.xml', '.epub', '.fb2'];
//
//    public $docServTimeout = '120000';
//
//    public $docServConverterUrl = 'ConvertService.ashx';
//    public $docServPreloaderUrl = 'web-apps/apps/api/documents/cache-scripts.html';
//    public $docServCommandUrl = 'coauthoring/commandservice.ashx';
//
//    public $docServJwtHeader = 'AuthorizationJwt';
//
//    public $docServVerifyPeerOff = true;
//
//    public $exampleUrl = '';
//
//    public $mobileRegex = 'android|avantgo|playbook|blackberry|blazer|compal'
//        . '|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo'
//        . '|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\\/|plucker'
//        . '|pocket|psp|symbian|treo|up\\.(browser|link)|vodafone|wap|windows'
//        . ' (ce|phone)|xda|xiino';
//
//    public $extsSpreadsheet = ['.xls', '.xlsx', '.xlsm', '.xlsb', '.xlt',
//        '.xltx', '.xltm', '.ods', '.fods', '.ots', '.csv'];
//
//    public $extsPresentation = ['.pps', '.ppsx', '.ppsm', '.ppt', '.pptx',
//        '.pptm', '.pot', '.potx', '.potm', '.odp', '.fodp', '.otp'];
//
//    public $xtsDocument = ['.doc', '.docx', '.docm', '.dot', '.dotx', '.dotm',
//        '.odt', '.fodt', '.ott', '.rtf', '.txt', '.html', '.htm', '.mht',
//        '.xml', '.pdf', '.djvu', '.fb2', '.epub', '.xps', '.oxps', '.oform'];
//
//    public $languages = [
//        'en' => 'English',
//        'az' => 'Azerbaijani',
//        'be' => 'Belarusian',
//        'bg' => 'Bulgarian',
//        'ca' => 'Catalan',
//        'zh' => 'Chinese',
//        'cs' => 'Czech',
//        'da' => 'Danish',
//        'nl' => 'Dutch',
//        'fi' => 'Finnish',
//        'fr' => 'French',
//        'gl' => 'Galego',
//        'de' => 'German',
//        'el' => 'Greek',
//        'hu' => 'Hungarian',
//        'id' => 'Indonesian',
//        'it' => 'Italian',
//        'ja' => 'Japanese',
//        'ko' => 'Korean',
//        'lv' => 'Latvian',
//        'lo' => 'Lao',
//        'nb' => 'Norwegian',
//        'pl' => 'Polish',
//        'pt' => 'Portuguese',
//        'ro' => 'Romanian',
//        'ru' => 'Russian',
//        'sk' => 'Slovak',
//        'sl' => 'Slovenian',
//        'es' => 'Spanish',
//        'sv' => 'Swedish',
//        'tr' => 'Turkish',
//        'uk' => 'Ukrainian',
//        'vi' => 'Vietnamese'
//    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->docServSiteUrl))
        {
            throw new InvalidConfigException('Indirizzo del server dei servizi'
                . ' non impostato');
        }
        if (empty($this->docServJwtSecret))
        {
            throw new InvalidConfigException('Chiave di criptazione JWT non'
                . ' impostata');
        }
        if (empty($this->keyFilePrefixName))
        {
            throw new InvalidConfigException('Prefisso per la chiave univoca'
                . ' dei file non impostato');
        }
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultModels() {
        return [
            'OnlyofficeFiles' => __NAMESPACE__ . '\\models\\OnlyofficeFiles',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getWidgetGraphics() {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getWidgetIcons() {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getModuleName() {
        return 'onlyoffice';
    }

    /**
     * Recupera il nome (eventualmente comprensivo di path) del file da usare
     * come esempio (o per 'partenza') rispetto al tipo passato al metodo
     * @param string $type Tipo di file (estensione senza punto) di cui
     * recuperare il nome del file
     * @param boolean $whithPath Se true il nome del file sara' comprensivo del
     * path (l'alias verra' convertio) in cui si trova, se false il path non
     * verra' aggiunto
     * @return string Restituisce il nome (eventualmente comprensivo di path)
     * del file o la stringa vuota se non presente
     */
    public function getFileSampleByType($type, $whithPath = false)
    {
        $indice = strtolower($type);
        if (!array_key_exists($indice, $this->sampleFiles))
        {
            return '';
        }
        return ((!empty($whithPath)) ? Yii::getAlias($this->samplesPath) : '')
            . $this->sampleFiles[$indice];
    }

    /**
     * Recupera tutti i possibili tipi di documenti accetatti dall'editor di
     * OnlyOffice
     * @return array Restituisce tutti i tipi di documento accettati
     */
    public function getAllDocumentType()
    {
        return array_keys($this->docTypeAndExtension);
    }

    /**
     * Verifica se il tipo di documento passato al metodo e' gestito da
     * OnlyOffice
     * @param string $docType Tipo di docuemnto da verificare
     * @return boolean Restitusce true se il tipo di documento e' valida o false
     * altrimenti
     */
    public function isValidDocumentType($docType)
    {
        return (array_key_exists($docType, $this->docTypeAndExtension));
    }

    /**
     * Recupera tutte le possibili estensioni accetatte dall'editor di
     * OnlyOffice
     * @return array Restituisce tutte le possibi estensioni accettate (senza il
     * '.' iniziale)
     */
    public function getAllExtension()
    {
        $res = [];
        foreach ($this->docTypeAndExtension as $extensions) {
            $res = array_merge($res, $extensions);
        }
        return $res;
    }
    
    /**
     * Il metodo recupare il tipo di documento a partire dall'estensione (o dal
     * tipo) del file passata
     * @param string $extension Estensione (con o senza '.' iniziale) di cui
     * recuperare il tipo di documento
     * @param boolean $transformToLower Se true l'estensione verra 'trasformata'
     * in caratetri minuscoli, se false non subira' variazioni di questo tipo
     * @return string|boolean Restituisce il tipo di documento associato
     * all'estensione passata o false in caso non sia possibile recuperarla
     */
    public function getDocumentTypeByExtension($extension, $transformToLower = true)
    {
        if (empty($extension))
        {
            return false;
        }
        if (!empty($transformToLower))
        {
            $extension = strtolower($extension);
        }
        if (strpos($extension, '.') === 0)
        {
            $extension = substr($extension, 1);
        }
        foreach ($this->docTypeAndExtension as $docType => $extensions) {
            if (in_array($extension, $extensions))
            {
                return $docType;
            }
        }
        return false;
    }

    /**
     * Verifica se l'estensione passata al metodo e' gestita da OnlyOffice
     * @param string $extension Estensione da verificare (con o senza '.'
     * iniziale)
     * @param boolean $caseInsensitive Se true la verifica non terra' conto di
     * maiuscole e minuscole, se false i caratteri maiuscoli e minuscoli saranno
     * considerati differenti
     * @return boolean Restitusce true se l'estensione e' valida o false
     * altrimenti
     */
    public function isValidExtension($extension, $caseInsensitive = true)
    {
        return (!empty($this->getDocumentTypeByExtension($extension,
            $caseInsensitive)));
    }

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            return;
        }

        if (self::$registerEvent) {
            self::$registerEvent = false;
            /**
             * Sarebbe piu' corretto legarlo all'evento EVENT_AFTER_DELETE, ma
             * se entra in gioco la soft-delete questo evento non viene lanciato
             */
            Event::on(
                File::class,
                File::EVENT_BEFORE_DELETE,
                [$this, 'beforeDeleteFile']
            );
        }
    }
    
    /**
     * Metodo che viene richiamato al trigger di EVENT_BEFORE_DELETE della
     * classe File
     * @param ModelEvent $event Evento scatenato dalla cancellazione del file
     */
    public static function beforeDeleteFile($event)
    {
        pr($event->sender->toArray());
        
        $modelFile = $event->sender;
        if (empty($modelFile))
        {
            return;
        }
        if (!($modelFile instanceof File))
        {
            return;
        }
        /** @var File $modelFile */
        if (strcmp($modelFile->model, OnlyofficeFiles::class) == 0)
        {
            //la cancellazione, essendo di OnlyOffice, viene gia' gestita
            return;
        }

        /**
         * verifico se esistono dei dati di OnlyOffice legati al file che sta
         * per essere cancellato
         */
        $modelO = OnlyofficeFiles::searchByModelFile($modelFile);
        if (empty($modelO))
        {
            /**
             * il file non ha dati di OnlyOffice associati e quindi puo' essere
             * tranquillamente cancellato
             */
            return;
        }

        //cancello i dati di OnlyOffice
        $modelO->delete();
    }
}
