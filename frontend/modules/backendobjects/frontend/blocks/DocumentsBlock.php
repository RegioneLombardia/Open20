<?php

namespace app\modules\backendobjects\frontend\blocks;

use Yii;
use yii\web\Response;
use app\modules\backendobjects\frontend\blockgroups\ContenutoDinamicoGroup;
use luya\base\ModuleReflection;
use luya\cms\base\PhpBlock;
use app\modules\uikit\BaseUikitBlock;

/**
 * Module Backend Block.
 *
 * File has been created with `block/create` command. 
 */
class DocumentsBlock extends PhpBlock
{

    private $_vars = [];
    private $_cfgs = [];
    /**
     * @inheritdoc
     */
    public $cacheEnabled = false;
    public $frontendModuleName = "backendobjects";
    public $module = 'backendobjects';

    /**
     * @inheritDoc
     */
    public function blockGroup()
    {
        if(empty(\Yii::$app->getModule('documenti'))){
            return \app\modules\backendobjects\frontend\blockgroups\DisabledGroup::class;
        }
        return ContenutoDinamicoGroup::className();
    }

    public function getModuleIdInstalled()
    {
        return 'documenti';
    }
    /**
     * @inheritDoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_module_contenuti_dinamici_documents');
    }

    /**
     * @inheritDoc
     */
    public function icon()
    {
        return 'file-document-outline';
    }

    /**
     * @inheritDoc
     */
    public function config()
    {
        return [
            'vars' => [
                [
                    'var' => 'withoutTitle',
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_withoutTitle_label'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se selezionato nasconde la testata del plugin.'),
                ],
                [
                    'var' => 'withPagination', 
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_withPagination_label'), 
                    'type' => self::TYPE_CHECKBOX, 
                    'description' => Yii::t('backendobjects', 'Se selezionato visualizza un numero massimo di elementi per pagina, raggruppando i rimanenti all\interno di un paginatore. Se non selezionato visualizza tutti gli elementi nella prima schermata.'),
                ],
                [
                    'var' => 'numElementsPerPage', 
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_numElementsPerPage_label'), 
                    'type' => self::TYPE_NUMBER,  
                    'description' => Yii::t('backendobjects', 'Da compilare solo se ho selezionato il paginatore, definisce il numero di elementi per ogni pagina.')
                ],
                [
                    'var' => 'visualization',
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_visualization_label'),
                    'initvalue' => '1',
                    'description' => 'Le dimensioni e la formattazione del layout riguardano gli schermi desktop. Per schermi mobile ogni colonna avrà larghezza 100%.',
                    'type' => 'zaa-radio',
                    'options' => [
                        ['value' => 'card', 'label' => 'Visualizzazione a card', 'image' => '/img/assets/doc_card.png'],
                        ['value' => 'item', 'label' => 'Visualizzazione a lista', 'image' => '/img/assets/doc_list.png'],
                        ['value' => 'list', 'label' => 'Visualizzazione a lista di allegati', 'image' => '/img/assets/doc_attach.png'],
                        ['value' => 'explorer', 'label' => 'Visualizzazione vista file explorer', 'image' => '/img/assets/doc_explorer.png'],
                    ],
                ],                
                [
                    'var' => 'hideIfEmpty',
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_withoutElements_label'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se selezionato in caso non esistano documenti da visualizzare viene nascosto tutto il modulo.'),
                ],
                [
                    'var' => 'visibility',
                    'label' => 'Visibilità del blocco',
                    'description' => 'Imposta la visibilità della sezione.',
                    'initvalue' => '',
                    'type' => 'zaa-select', 'options' => [
                        ['value' => '', 'label' => 'Visibile a tutti'],
                        ['value' => 'guest', 'label' => 'Visibile solo ai non loggati'],
                        ['value' => 'logged', 'label' => 'Visibile solo ai loggati'],
                    ],
                ],
                [
                    'var' => 'addclass',
                    'label' => 'Visibilità per profilo',
                    'description' => 'Imposta la visibilità della sezione in base al profilo dell\'utente loggato',
                    'type' => 'zaa-multiple-inputs',
                    'options' => [
                        [
                            'var' => 'class',
                            'type' => 'zaa-select',
                            'initvalue' => '',
                            'options' => BaseUikitBlock::getClasses(),
                        ]
                    ],
                ],


            ],
            'cfgs' => [
                [
                    'var' => 'cssClass', 
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_cssClass_label'), 
                    'type' => self::TYPE_TEXT, 
                    'description' => Yii::t('backendobjects', 'Classe css associata al componente.')
                ],
                [
                    'var' => 'detailPage', 
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_detailPage_label'), 
                    'type' => self::TYPE_TEXT, 
                    'description' => Yii::t('backendobjects', 'Va specificato solo il nome del file, senza estensione. Se non specificato, gli elementi della lista non avranno il link per vedere il dettaglio.')
                ],
                [
                    'var' => 'listPage', 
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_listPage_label'), 
                    'type' => self::TYPE_TEXT, 
                    'description' => Yii::t('backendobjects', 'Va specificato solo il nome del file, senza estensione.')
                ],
                [
                    'var' => 'withSearch', 
                    'label' => Yii::t('backendobjects', 'Visualizza ricerca'),
                    'type' => self::TYPE_CHECKBOX, 
                    'description' => Yii::t('backendobjects', 'Se selezionato viene visualizzato il form di ricerca per gli elementi.')
                ],
                [
                    'var' => 'methodSearch', 
                    'label' => 'ModelSearch', 
                    'type' => self::TYPE_TEXT, 
                    'description' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_methodSearch_label')
                ],
                [
                    'var' => 'conditionSearch', 
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_conditionSearch_label'), 
                    'type' => self::TYPE_TEXT, 
                    'description' => Yii::t('backendobjects', 'Se inseriti permettono il filtraggio di elementi secondo specifiche condizioni.')
                ],
                [
                    'var' => 'onlyHighlightsDocumenti',
                    'label' => Yii::t('backendobjects', 'Visualizza solo documenti in evidenza'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se selezionato vengono visualizzate nell\'elenco solo i documenti contrassegnati come in evidenza. Se impostato il modelSearch verrà ignorato.'),
                ],
                [
                    'var' => 'hideCategory',
                    'label' => Yii::t('backendobjects', 'Nascondi categoria dalla ricerca'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se la ricerca è abilitata nasconde il campo di ricerca categoria.'),
                ],
                [
                    'var' => 'hideFolder',
                    'label' => Yii::t('backendobjects', 'Escludi cartelle dai risultati'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se selezionato, i documenti di tipologia cartella non saranno visualizzati'),
                ],
                [
                    'var' => 'lastUpdateDate',
                    'label' => Yii::t('backendobjects', 'Visualizza data di aggiornamento dei contenuti'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'come data di aggiornamento verrà selezionata la data di pubblicazione più recente dei documenti visualizzati.'),
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFieldHelp()
    {
        return [
            'withPagination' => Yii::t('backendobjects', 'Se selezionato visualizza un numero massimo di elementi per pagina, raggruppando i rimanenti all\interno di un paginatore. Se non selezionato visualizza tutti gli elementi nella prima schermata.'),
            'viewFields' => Yii::t('backendobjects', 'block_module_backend_viewFields_help'),
            'numElementsPerPage' => Yii::t('backendobjects', 'Da compilare solo se ho selezionato il paginatore, definisce il numero di elementi per ogni pagina.'),
            'cssClass' => Yii::t('backendobjects', 'Classe css associata al componente.'),
            'detailPage' => Yii::t('backendobjects', 'Va specificato solo il nome del file, senza estensione. Se non specificato, gli elementi della lista non avranno il link per vedere il dettaglio.'),
            'listPage' => Yii::t('backendobjects', 'Va specificato solo il nome del file, senza estensione.'),
            'withSearch' => Yii::t('backendobjects', 'Se selezionato viene visualizzato il form di ricerca per gli elementi.'),
            'methodSearch' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_methodSearch_label'),
            'conditionSearch' => Yii::t('backendobjects', 'Se inseriti permettono il filtraggio di elementi secondo specifiche condizioni.'),
        ];
    }


    /**
     * {@inheritDoc} 
     *
     */
    public function admin()
    {
        $str_view_module = '{% if vars.backendModule is empty %}' . '{% else %}<p><i class="material-icons">apps</i> ' . Yii::t('backendobjects', 'block_module_backend_integration') . ': <strong>';
        $moduleName = "";

        $str_view = '';

        
        $viewImage = 'doc_card';
        switch ($this->getVarValue('visualization')) {
            case 'card':
                $viewImage = 'doc_card';
                break;
            case 'item':
                $viewImage = 'doc_list';
                break;
            case 'list':
                $viewImage = 'doc_attach';
                break;
            case 'explorer':
                $viewImage = 'doc_explorer';
                break;
        }
        
        $moduleName = "Documenti";
        $str_view .= '<img src="/img/assets/' . $viewImage . '.png">';

        return $str_view_module . $moduleName . '</strong></p>{% endif %}' . $str_view;
    }

    /**
     * @inheritdoc
     */
    public function extraVars()
    {
        return [
            'moduleContent' => $this->moduleContent(),
        ];
    }

    /**
     * Get configurated backend modules to make module selection in block.
     * 
     * Backend modules must implement CmsModuleInterface and search model must implement CmsModelInterface
     *
     * @return array
     */
    public function getBackendModules()
    {
        $frontendModule = Yii::$app->getModule($this->frontendModuleName);

        $data = [];
        if ($frontendModule && $frontendModule->modulesEnabled) {
            foreach ($frontendModule->modulesEnabled as $backModuleEnabled) {
                if (class_exists($backModuleEnabled) && in_array('open20\amos\core\interfaces\CmsModuleInterface', class_implements($backModuleEnabled))) {
                    $moduleName = $backModuleEnabled::getModuleName();
                    $modelSearchClass = $backModuleEnabled::getModelSearchClassName();
                    if (class_exists($modelSearchClass) && in_array('open20\amos\core\interfaces\CmsModelInterface', class_implements($modelSearchClass))) {
                        $data[] = ['value' => $backModuleEnabled, 'label' => $moduleName];
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Get all possible view fileds (of selected backend module) to make view fields selection in block.
     *
     * @return array array format must be like this:
     *  [
     *     'items' => [
     *       ['value' => 'val_1', 'label' => 'label_1'],
     *       ['value' => 'val_2', 'label' => 'label_2'],
     *       ['value' => 'val_3', 'label' => 'label_3'],
     *       ['value' => 'val_4', 'label' => 'label_4'],
     *     ]
     * ]
     */
    public function getViewFields()
    {
        $data = [];
        $backendModule = $this->getVarValue('backendModule', false);

        if (!empty($backendModule)) {
            $modelSearchClass = $backendModule::getModelSearchClassName();
            $searchModel = new $modelSearchClass();
            $viewFields = $searchModel->cmsViewFields();

            $items = [];
            foreach ($viewFields as $field) {
                array_push($items, ['value' => $field->name, 'label' => $field->label]);
            }
            $data = [
                'items' => $items,
            ];
        }

        return $data;
    }

    /**
     * Return the Module output 
     *
     * @return string|null|\yii\web\Response
     */
    public function moduleContent()
    {

        if ($this->isAdminContext() || empty($this->frontendModuleName) || count($this->getEnvOptions()) === 0 || !Yii::$app->hasModule($this->frontendModuleName)) {
            return;
        }

        $frontendModule = Yii::$app->getModule($this->frontendModuleName);
        $frontendModule->context = 'cms';

        $moduleView = 'listCardDocumentsDesign';
        switch ($this->getVarValue('visualization')) {
            case 'card':
                $moduleView = 'listCardDocumentsDesign';
                break;
            case 'item':
                $moduleView = 'listDocumentsDesign';
                break;
            case 'list':
                $moduleView = 'listAttachmentDocumentsDesign';
                break;
            case 'explorer':
                $moduleView = 'fileExplorerDocumentsDesign';
                break;
        }

        $additionalClass = '';
        if ($this->getVarValue('withoutTitle')) {
            $additionalClass = "hide-bi-plugin-header";
        }
        if ($this->getVarValue('hideIfEmpty')) {
            $additionalClass = "hide-module-if-empty-list";
        }

      
       



        $modelSearchMethod = '';
        if (!empty($this->getCfgValue('onlyHighlightsDocumenti'))) {
            $modelSearchMethod = 'searchHighlightedAndHomepageDocumenti';
        } else {
            if (!empty($this->getCfgValue('methodSearch'))) {
                $modelSearchMethod = $this->getCfgValue('methodSearch');
            }
        }


        // start module reflection
        $reflection = Yii::createObject(['class' => ModuleReflection::class, 'module' => $frontendModule]);
        $reflection->suffix = $this->getEnvOption('restString');
        $args = [
            'parms' => [
                "backendModule" => 'open20\\amos\\documenti\\AmosDocumenti',
                "numElementsPerPage" => $this->getVarValue('numElementsPerPage'),
                "viewFields" => $this->getVarValue('viewFields'),
                "withPagination" => $this->getVarValue('withPagination'),   
                "withSearch" => $this->getCfgValue('withSearch'),
                "cssClass" => $this->getCfgValue('cssClass') . ' ' . $additionalClass,
                "listPage" => $this->getCfgValue('listPage', $moduleView),
                "detailPage" => $this->getCfgValue('detailPage'),
                "methodSearch" => $modelSearchMethod,
                "conditionSearch" => $this->getCfgValue('conditionSearch'),
                "blockItemId" => $this->getEnvOption('id'),
                "lastUpdateDate" => $this->getCfgValue('lastUpdateDate'),  
                "hideCategory" => $this->getCfgValue('hideCategory'),  
                "hideFolder" => $this->getCfgValue('hideFolder'),  
                
            ],
        ];

     

        $reflection->defaultRoute("default", "index", $args);

        $response = $reflection->run();

        if ($response instanceof Response) {
            return Yii::$app->end(0, $response);
        }

        return $response;
    }
    
    public function getConfigVarsExport()
    {
        $config = $this->config();
        
        if (isset($config['vars'])) {
            foreach ($config['vars'] as $item) {                
                $iteration = count($this->_vars) + 500;
                $this->_vars[$iteration] = (new \app\modules\uikit\BlockVar($item))->toArray();
            }
        }
       
        ksort($this->_vars);
        return array_values($this->_vars);
    }
    
    public function getConfigCfgsExport()
    {
        $config = $this->config();
        
        if (isset($config['cfgs'])) {
            foreach ($config['cfgs'] as $item) {
                $iteration = count($this->_cfgs) + 500;
                $this->_cfgs[$iteration] = (new \app\modules\uikit\BlockCfg($item))->toArray();
            }
        }
        ksort($this->_cfgs);
        return array_values($this->_cfgs);
    }
}
