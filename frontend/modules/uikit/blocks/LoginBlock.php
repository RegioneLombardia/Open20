<?php

namespace app\modules\uikit\blocks;

use Yii;
use app\modules\uikit\BaseUikitBlock;
use yii\web\Response;
use yii\helpers\Json;
use luya\cms\frontend\Module;
use app\modules\backendobjects\frontend\blockgroups\SviluppoGroup;
use luya\base\ModuleReflection;
use luya\cms\helpers\BlockHelper;
use luya\cms\base\PhpBlock;

/**
 * Module integration Block to render controller and/or actions.
 *
 * @since 1.0.0
 */
final class LoginBlock extends BaseUikitBlock
{
    /**
     * @inheritdoc
     */
    public $cacheEnabled = false;

    /**
     * @inheritdoc
     */
    public function name()
    {
        return Yii::t('backendobjects', 'block_login_module_name');
    }

    /**
     * @inheritdoc
     */
    public function blockGroup()
    {
        return SviluppoGroup::class;
    }



    /**
     * @inheritdoc
     */
    public function icon()
    {
        return 'login';
    }

    /**
     * @inheritdoc
     */
    public function config()
    {
        return [
            /*'vars' => [
                ['var' => 'moduleName', 'label' => Yii::t('backendobjects', 'block_module_modulename_label'), 'type' => self::TYPE_SELECT, 'options' => $this->getModuleNames()],
            ],
            'cfgs' => [
                ['var' => 'moduleController', 'label' => Yii::t('backendobjects', 'block_module_modulecontroller_label'), 'type' => self::TYPE_SELECT, 'options' => BlockHelper::selectArrayOption($this->getControllerClasses())],
                ['var' => 'moduleAction', 'label' => Yii::t('backendobjects', 'block_module_moduleaction_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'moduleActionArgs', 'label' => Yii::t('backendobjects', 'block_module_moduleactionargs_label'), 'type' => self::TYPE_TEXT],
                ['var' => 'strictRender', 'label' => Yii::t('backendobjects', 'block_module_strictrender'), 'type' => self::TYPE_CHECKBOX]
            ],*/
        ];
    }



    /**
     * @inheritdoc
     */
    public function getFieldHelp()
    {
        return [
            'moduleName' => Yii::t('backendobjects', 'block_module_modulename_help'),
            'strictRender' => Yii::t('backendobjects', 'block_module_strictrender_help'),
        ];
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
     * @inheritdoc
     */
    public function admin()
    {
        //return '{% if vars.moduleName is empty %}<span class="block__empty-text">' . Yii::t('backendobjects', 'block_module_no_module') . '</span>{% else %}<p><i class="material-icons">developer_board</i> ' . Yii::t('backendobjects', 'block_module_integration') . ': <strong>{{ vars.moduleName }}</strong></p>{% endif %}';
        return '<p><i class="material-icons">developer_board</i> ' . Yii::t('backendobjects', 'Modulo Login') . '</p>';

    }

    /**
     * Get all module related controllers.
     *
     * @return array
     */
    public function getControllerClasses()
    {
        $moduleName = $this->getVarValue('moduleName', false);

        return (empty($moduleName) || !Yii::$app->hasModule($moduleName)) ? [] : Yii::$app->getModule($moduleName)->getControllerFiles();
    }

    /**
     * Get all available frontend modules to make module selection in block.
     *
     * @return array
     */
    public function getModuleNames()
    {
        $data = [];
        foreach (Yii::$app->getFrontendModules() as $k => $f) {
            $data[] = ['value' => $k, 'label' => $k];
        }
        return $data;
    }

    /**
     * Return the Module output based on the module name.
     *
     * @param string $moduleName
     * @return string|null|\yii\web\Response
     */
    public function moduleContent($moduleName = 'userauthfrontend')
    {

        if ($this->isAdminContext() || empty($moduleName) || count($this->getEnvOptions()) === 0 || !Yii::$app->hasModule($moduleName)) {
            return;
        }

        // get module
        $module = Yii::$app->getModule($moduleName);
        $module->context = 'cms';

        // start module reflection
        $reflection = Yii::createObject(['class' => ModuleReflection::class, 'module' => $module]);
        $reflection->suffix = $this->getEnvOption('restString');

        $args = Json::decode($this->getCfgValue('moduleActionArgs', '{}'));

        // if a controller has been defined we inject a custom starting route for the
        // module reflection object.
        $ctrl = $this->getCfgValue('moduleController');
        if (!empty($ctrl)) {
            $reflection->defaultRoute($ctrl, $this->getCfgValue('moduleAction', 'index'), $args);
        }

        if ($this->getCfgValue('strictRender')) {
            $reflection->setRequestRoute(implode("/", [$this->getCfgValue('moduleController', $module->defaultRoute), $this->getCfgValue('moduleAction', 'index')]), $args);
        } else {
            Yii::$app->request->queryParams = array_merge($reflection->getRequestRoute()['args'], Yii::$app->request->queryParams);
        }

        $response = $reflection->run();

        if ($response instanceof Response) {
            return Yii::$app->end(0, $response);
        }

        return $response;
    }
}
