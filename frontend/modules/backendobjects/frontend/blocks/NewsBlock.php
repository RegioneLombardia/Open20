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
class NewsBlock extends PhpBlock {

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
    public function blockGroup() {
        if (empty(\Yii::$app->getModule('news'))) {
            return \app\modules\backendobjects\frontend\blockgroups\DisabledGroup::class;
        }
        return ContenutoDinamicoGroup::className();
    }

    public function getModuleIdInstalled() {
        return 'news';
    }

    /**
     * @inheritDoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_contenuti_dinamici_news');
    }

    /**
     * @inheritDoc
     */
    public function icon() {
        return 'text-box-multiple-outline';
    }

    /**
     * @inheritDoc
     */
    public function config() {
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
                        ['value' => 'card', 'label' => 'Visualizzazione a card', 'image' => '/img/assets/cards.png'],
                        ['value' => 'item', 'label' => 'Visualizzazione a lista', 'image' => '/img/assets/list.png'],
                        ['value' => 'carousel', 'label' => 'Visualizzazione a carousel', 'image' => '/img/assets/carousel.png'],
                    ],
                ],
                [
                    'var' => 'hideIfEmpty',
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_withoutElements_label'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se selezionato in caso non esistano news da visualizzare viene nascosto tutto il modulo.'),
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
                    'initValue' => 1,
                    'description' => Yii::t('backendobjects', 'Se selezionato viene visualizzato il form di ricerca per gli elementi.')
                ],
                [
                    'var' => 'methodSearch',
                    'label' => 'ModelSearch',
                    'type' => self::TYPE_TEXT,
                    'description' => Yii::t('backendobjects', 'block_module_backend_methodSearch_label')
                ],
                [
                    'var' => 'conditionSearch',
                    'label' => Yii::t('backendobjects', 'block_module_contenuti_dinamici_conditionSearch_label'),
                    'type' => self::TYPE_TEXT,
                    'description' => Yii::t('backendobjects', 'Se inseriti permettono il filtraggio di elementi secondo specifiche condizioni.')
                ],
                [
                    'var' => 'onlyHighlightsNews',
                    'label' => Yii::t('backendobjects', 'Visualizza solo notizie in evidenza'),
                    'type' => self::TYPE_CHECKBOX,
                    'description' => Yii::t('backendobjects', 'Se selezionato vengono visualizzate nell\'elenco solo le notizie contrassegnate come in evidenza. Se impostato il modelSearch verrà ignorato.'),
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFieldHelp() {
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
    public function admin() {
        $str_view_module = '{% if vars.backendModule is empty %}' . '{% else %}<p><i class="material-icons">apps</i> ' . Yii::t('backendobjects', 'block_module_backend_integration') . ': <strong>';
        $moduleName = "";

        $str_view = '';

        $viewImage = 'cards';
        switch ($this->getVarValue('visualization')) {
            case 'card':
                $viewImage = 'cards';
                break;
            case 'item':
                $viewImage = 'list';
                break;
            case 'carousel':
                $viewImage = 'carousel';
                break;
        }


        $moduleName = "News";
        $str_view .= '<img src="/img/assets/' . $viewImage . '.png">';

        return $str_view_module . $moduleName . '</strong></p>{% endif %}' . $str_view;
    }

    /**
     * @inheritdoc
     */
    public function extraVars() {
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
    public function getBackendModules() {
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
    public function getViewFields() {
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
    public function moduleContent() {

        if ($this->isAdminContext() || empty($this->frontendModuleName) || count($this->getEnvOptions()) === 0 || !Yii::$app->hasModule($this->frontendModuleName)) {
            return;
        }

        $frontendModule = Yii::$app->getModule($this->frontendModuleName);
        $frontendModule->context = 'cms';

        // start module reflection
        $reflection = Yii::createObject(['class' => ModuleReflection::class, 'module' => $frontendModule]);
        $reflection->suffix = $this->getEnvOption('restString');

        $moduleView = 'listCardNewsDesign';
        switch ($this->getVarValue('visualization')) {
            case 'card':
                $moduleView = 'listCardNewsDesign';
                break;
            case 'item':
                $moduleView = 'listItemNewsDesign';
                break;
            case 'carousel':
                $moduleView = 'listOwlCarouselSingleCardNewsDesign';
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
        if (!empty($this->getCfgValue('onlyHighlightsNews'))) {
            $modelSearchMethod = 'searchHighlightedAndHomepageNews';
        } else {
            if (!empty($this->getCfgValue('methodSearch'))) {
                $modelSearchMethod = $this->getCfgValue('methodSearch');
            }
        }

        $args = [
            'parms' => [
                "backendModule" => 'open20\\amos\\news\\AmosNews',
                "numElementsPerPage" => $this->getVarValue('numElementsPerPage'),
                "viewFields" => $this->getVarValue('viewFields'),
                "withPagination" => $this->getVarValue('withPagination'),
                "withSearch" => $this->getCfgValue('withSearch'),
                "cssClass" => $this->getCfgValue('cssClass') . ' ' . $additionalClass,
                "listPage" => $this->getCfgValue('listPage', $moduleView),
                "detailPage" => $this->getCfgValue('detailPage'),
                "methodSearch" => $modelSearchMethod,
                "conditionSearch" => $this->getCfgValue('conditionSearch'),
                "blockItemId" => $this->getEnvOption('id')
            ],
        ];

        $reflection->defaultRoute("default", "index", $args);

        $response = $reflection->run();

        if ($response instanceof Response) {
            return Yii::$app->end(0, $response);
        }

        return $response;
    }

    public function getConfigVarsExport() {
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

    public function getConfigCfgsExport() {
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
