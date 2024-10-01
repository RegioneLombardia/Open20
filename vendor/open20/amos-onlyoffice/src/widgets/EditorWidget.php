<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\onlyoffice\widgets
 * @category   CategoryName
 */

namespace open20\onlyoffice\widgets;

use open20\amos\admin\models\UserProfile;
use Firebase\JWT\JWT;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class EditorWidget
 *
 * Widget che visualizza l'editor di OnlyOffice
 *
 * @package open20\onlyoffice\widgets
 */
class EditorWidget extends Widget
{
    /**
     * @var \open20\amos\core\record\Record $model
     */
//    public $model;

    /**
     * URL del file da visualizzare/editare
     * @var string
     */
    public $urlFile;
    /**
     * Chiave univoca che identifica il file (max 128 caratteri)
     * @var string
     */
    public $keyFile;
    /**
     * Nome del file
     * @var string
     */
    public $nameFile;
    /**
     * Modalita' in cui deve essere visualizzato il file. Puo' assumere il
     * valore 'view' o 'edit', rispettivamente per l'apertura del file in sola
     * visualizzazione o in modifica
     * @var string
     */
    public $editorMode;

    /**
     * Options array for the widget
     * @var array
     */
    public $options = [];

    /**
     * Opzioni di default (che verranno usate in assenza di quelle specifiche
     * contenute nell'attributo options)
     * @var array
     */
    protected $defaultOptions = [
        'fileType' => 'docx',
        'fileTypeAuto' => false,
        'documentType' => 'word',
        'documentTypeAuto' => false,
        'onlyView' => false,
        'canComment' => true,
        'language' => 'it',
        'autore' => null,
        'dataCreazione' => null,
        'iframeWidth' => '100%',
//        'iframeHeight' => '100%',
        'iframeHeight' => '500',
    ];

//    public $editorConfig = [
//        'customization' => [
//            'anonymous' => [
//                'request' => false
//            ],
//        ],
//        'lang' => 'it',
//        'location' => 'it',
//    ];

    /**
     * Larghezza dell'iframe che dovra' contenere l'editor di OnlyOffice.
     * Impostare questo parametro come se si stesse valorizzando il relativo
     * attributo del tag HTML 'iframe'
     * @var string
     */
    public $iframeWidth = '';
    /**
     * Aletezza dell'iframe che dovra' contenere l'editor di OnlyOffice.
     * Impostare questo parametro come se si stesse valorizzando il relativo
     * attributo del tag HTML 'iframe'
     * @var string
     */
    public $iframeHeight = '500';
//    public $iframeHeight = '100%';

    /**
     * Configurazioni per l'edito JavaScript di OnlyOffice
     * @var array
     */
    public $configForJs;

    /**
     * @inheritdoc
     */
    public function init() {
        if (empty($this->urlFile))
        {
            throw new InvalidConfigException('URL del file non presente');
        }
        if (empty($this->keyFile))
        {
            throw new InvalidConfigException('Chiave identificativa del file'
                . ' non presente');
        }
        if (empty($this->nameFile))
        {
            throw new InvalidConfigException('Nome del file non presente');
        }

        parent::init();
    }

    /**
     * Merge default options
     */
    protected function mergeDefaultOptions()
    {
//        $this->pluginOptions = ArrayHelper::merge($this->defaultPluginOptions, $this->pluginOptions);
        $this->options = ArrayHelper::merge($this->defaultOptions,
            $this->options);
    }

    /**
     * Estrapola il tipo di file (inteso come estensione del file) dal suo nome
     * @param string $name Nome del file (da cui estrapolare il tipo)
     * @return string|boolean Restituisce il tipo del file o false in caso di
     * errore
     */
    protected function calcolateFyleTypeFromName($name)
    {
        if (empty($name)) {
            return false;
        }
        if (strpos($name, '.') === false)
        {
            return false;
        }
        $nameParts = explode('.', $name);
        $type = array_pop($nameParts);
        return strtolower($type);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
//        parent::run();

        $this->mergeDefaultOptions();

        $module = Yii::$app->getModule('onlyoffice');
        if (empty($module))
        {
            throw new InvalidConfigException('Errore nel recupero del modulo');
        }
        /** @var \open20\onlyoffice\Module $module */
        if (empty($module->docServJwtSecret))
        {
            throw new InvalidConfigException('Chiave per il JWT non presente');
        }
        if (empty($module->docServSiteUrl))
        {
            throw new InvalidConfigException('URL del server non presente');
        }
        if (empty($module->keyFilePrefixName))
        {
            throw new InvalidConfigException('Prefisso per la chiave univoca'
                . ' dei file non impostato');
        }
        if (empty($module->docServApiUrl))
        {
            throw new InvalidConfigException('URL del server in cui recuperare'
                . ' le API non presente');
        }
        if (!empty($this->options['fileTypeAuto'])) {
            $this->options['fileType'] = $this->calcolateFyleTypeFromName(
                $this->nameFile);
        }
        if ((!empty($this->options['documentTypeAuto']))
            && (!empty($this->options['fileType']))) {
            $this->options['documentType'] =
                $module->getDocumentTypeByExtension($this->options['fileType'],
                    true);
        }
        if ((empty($this->options['documentType']))
            || (!in_array($this->options['documentType'],
                $module->getAllDocumentType())))
        {
            throw new InvalidConfigException('Tipo di documento non presente o'
                . ' non valido');
        }

        //gestisco la scelta della lingua
        $lang = 'it';
        $location = '';
        $region = 'it-IT';
        if (!empty($this->options['language']))
        {
            $langLength = strlen($this->options['language']);
            if ($langLength == 2)
            {
                switch ($this->options['language']) {
                    case 'it':
                        //gia' gestito di default
                        break;
                    case 'en':
                        $lang = 'en';
                        $location = 'us';
                        $region = 'en-US';
                        break;
                    default:
                        $lang = $this->options['language'];
                        $location = '';
                        $region = '';
                        break;
                }
            }
            elseif (($langLength == 5)
                && (strpos($this->options['language'], '-')) !== false)
            {
                list($lang, $location) =
                    explode('-', $this->options['language']);
                switch (strtolower($location)) {
                    case 'us':
                    case 'ca':
                        //valori accettati
                        $location = strtolower($location);
                        break;
                    default:
                        $location = '';
                        break;
                }
                $region = $this->options['language'];
            }
        }

        //recupero i dati dell'utente loggato
        $modelUserProfile = UserProfile::find()
            ->andWhere(['user_id' => Yii::$app->user->id])
            ->one();
        
        //verifico se l'utente puo' modificare il file
        $canEdit = true;
        if (!empty($this->options['onlyView']))
        {
            $canEdit = false;
        }
        
        //imposto la modalita' dell'editor: visualizzazione o modifica
        if (!$canEdit)
        {
            $editorMode = 'view';
        }
        else
        {
            switch (strtolower($this->editorMode)) {
                case 'view':
                    $editorMode = 'view';
                    break;
                case 'edit':
                    $editorMode = 'edit';
                    break;
                default:
                    $editorMode = 'edit';
                    break;
            }
        }

        //creo la configurazione per l'editor JavaScript
        $configForJsDefault = [
            'document' => [
                'fileType' => strtolower($this->options['fileType']),
                'permissions' => [
                    'chat' => false,
                    'edit' => $canEdit,
                    'comment' => (($canEdit)
                        && (!empty($this->options['canComment']))),
                    'modifyContentControl' => true,
//                    'modifyContentControl' => false,
                    'editCommentAuthorOnly' => true,
                    'deleteCommentAuthorOnly' => true,
                    'copy' => true,
                    'download' => true,
//                    'modifyFilter' => true,
                    'modifyFilter' => false,
                    'print' => true,
//                    'protect' => true,
                    'protect' => false,
                    'review' => true,
                ],
                'title' => $this->nameFile,
                'url' => $this->urlFile,
                'key' => $this->keyFile,
            ],
            'documentType' => $this->options['documentType'],
            'editorConfig' => [
                'mode' => $editorMode,
                'callbackUrl' => Url::to(['/' . $module->getModuleName()
                    . '/onlyoffice/callback-api'], true),
                'customization' => [
                    'anonymous' => [
                        'request' => false
                    ],
                    'forcesave' => false,
                ],
                'lang' => $lang,
                'location' => $location,
                'region' => $region,
                'user' => [
                    'id' => Yii::$app->user->id,
                    'name' => ((!empty($modelUserProfile))
                        ? $modelUserProfile->nomeCognome : ''),
                ],
            ],
        ];
        $configForJsWidth = $this->options['iframeWidth'];
        $configForJsHeight = $this->options['iframeHeight'];
        if ((empty($this->configForJs))
            || (!is_array($this->configForJs)))
        {
            $this->configForJs = $configForJsDefault;
        }
        else
        {
            //gestisco alcune configurazioni che non vanno inserite nel token
            if (!empty($this->configForJs['iframeWidth']))
            {
                $configForJsWidth = $this->configForJs['iframeWidth'];
                unset($this->configForJs['iframeWidth']);
            }
            if (!empty($this->configForJs['iframeHeight']))
            {
                $configForJsHeight = $this->configForJs['iframeHeight'];
                unset($this->configForJs['iframeHeight']);
            }
            
            if (!empty($this->configForJs))
            {
                $this->configForJs = ArrayHelper::merge($configForJsDefault,
                    $this->configForJs);
            }
            else
            {
                $this->configForJs = $configForJsDefault;
            }
        }
        
        $autore = ArrayHelper::getValue($this->options, 'autore');
        if (!empty($autore))
        {
            ArrayHelper::setValue($this->configForJs, 'document.info.owner',
                $autore);
        }
        $dataCreazione = ArrayHelper::getValue($this->options, 'dataCreazione');
        if (!empty($dataCreazione))
        {
            ArrayHelper::setValue($this->configForJs, 'document.info.uploaded',
                date('Y-m-d h:i A', strtotime($dataCreazione)));
        }
//        pr($this->configForJs); die;

        //calcolo del token e inserimento nella configurazione
        $token = JWT::encode($this->configForJs, $module->docServJwtSecret,
            'HS256');
        $this->configForJs['token'] = $token;

        /**
         * altre configurazioni (che non dovevano essere inserite nel calcolo
         * del token)
         */
        $this->configForJs['width'] = $configForJsWidth;
        $this->configForJs['height'] = $configForJsHeight;

        return $this->render('editor', [
            'config' => $this->configForJs,
            'urlApiJs' => $module->docServSiteUrl . $module->docServApiUrl,
        ]);
    }
}