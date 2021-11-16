<?php

namespace app\modules\backendobjects\frontend\blocks;

use Yii;
use yii\web\Response;
use luya\cms\frontend\blockgroups\DevelopmentGroup;
use luya\base\ModuleReflection;
use luya\cms\base\PhpBlock;

/**
 * Module Backend Block.
 *
 * File has been created with `block/create` command. 
 */
class ModuleBackendBlock extends PhpBlock {

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
        return DevelopmentGroup::className();
    }

    /**
     * @inheritDoc
     */
    public function name() {
        return Yii::t('backendobjects', 'block_module_backend_name');
    }

    /**
     * @inheritDoc
     */
    public function icon() {
        return 'apps';
    }

    /**
     * @inheritDoc
     */
    public function config() {
        return [
            'vars' => [
                ['var' => 'backendModule', 'label' => Yii::t('backendobjects', 'block_module_backend_backendModule_label'), 'type' => self::TYPE_SELECT, 'options' => $this->getBackendModules()],
                ['var' => 'viewFields', 'label' => Yii::t('backendobjects', 'block_module_backend_viewFields_label'), 'type' => self::TYPE_CHECKBOX_ARRAY, 'options' => $this->getViewFields()],
                ['var' => 'withPagination', 'label' => Yii::t('backendobjects', 'block_module_backend_withPagination_label'), 'type' => self::TYPE_CHECKBOX],
                ['var' => 'numElementsPerPage', 'label' => Yii::t('backendobjects', 'block_module_backend_numElementsPerPage_label'), 'type' => self::TYPE_NUMBER],
                ['var' => 'cssClass', 'label' => Yii::t('backendobjects', 'block_module_backend_cssClass_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'detailPage', 'label' => Yii::t('backendobjects', 'block_module_backend_detailPage_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'listPage', 'label' => Yii::t('backendobjects', 'block_module_backend_listPage_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'withoutSearch', 'label' => Yii::t('backendobjects', 'block_module_backend_withoutSearch_label'), 'type' => self::TYPE_CHECKBOX],
                ['var' => 'methodSearch', 'label' => Yii::t('backendobjects', 'block_module_backend_methodSearch_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'conditionSearch', 'label' => Yii::t('backendobjects', 'block_module_backend_conditionSearch_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'relatedDetailPage', 'label' => Yii::t('backendobjects', 'block_module_backend_related_detailPage_label'), 'type' => self::TYPE_TEXT],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFieldHelp() {
        return [
            'backendModule' => Yii::t('backendobjects', 'block_module_backend_backendModule_help'),
            'viewFields' => Yii::t('backendobjects', 'block_module_backend_viewFields_help'),
            'numElementsPerPage' => Yii::t('backendobjects', 'block_module_backend_numElementsPerPage_help'),
            'cssClass' => Yii::t('backendobjects', 'block_module_backend_cssClass_help'),
            'detailPage' => Yii::t('backendobjects', 'block_module_backend_detailPage_help'),
            'relatedDetailPage' => Yii::t('backendobjects', 'block_module_backend_related_detailPage_help'),
            'listPage' => Yii::t('backendobjects', 'block_module_backend_listPage_help'),
        ];
    }

    /**
     * {@inheritDoc} 
     *
     */
    public function admin() {

        return '{% if vars.backendModule is empty %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_module_backend_no_module') . '</span>{% else %}<p><i class="material-icons">apps</i> ' . Yii::t('backendobjects', 'block_module_backend_integration') . ': <strong>{{ vars.backendModule}}</strong></p>{% endif %}';
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
        
        $args = [
            'parms' => [
                "backendModule" => $this->getVarValue('backendModule'),
                "numElementsPerPage" => $this->getVarValue('numElementsPerPage'),
                "viewFields" => $this->getVarValue('viewFields'),
                "withPagination" => $this->getVarValue('withPagination'),
                "withoutSearch" => $this->getVarValue('withoutSearch'),
                "cssClass" => $this->getVarValue('cssClass'),
                "listPage" => $this->getVarValue('listPage'),
                "detailPage" => $this->getVarValue('detailPage'),
                "methodSearch" => $this->getVarValue('methodSearch'),
                "conditionSearch" => $this->getVarValue('conditionSearch'),
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

}
