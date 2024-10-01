<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\socialwall
 * @category   CategoryName
 */

namespace open20\socialwall;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\privileges\interfaces\CategoriesRolesInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\socialwall\utility\Social;
use yii\helpers\ArrayHelper;


/**
 * Class Module
 * @package open20\socialwall
 */
class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface //, CategoriesRolesInterface
{
    public $rteToolbar = "bold italic strikethrough | removeformat";
    public $modulesIdClassMapEnable;
    public $enableAdvancedSocialConfigs = false;
    public $enabledSocials = [
        Social::FACEBOOK => Social::SOCIAL_LABELS[Social::FACEBOOK],
        Social::INSTAGRAM => Social::SOCIAL_LABELS[Social::INSTAGRAM],
        Social::TWITTER => Social::SOCIAL_LABELS[Social::TWITTER],
    ];
    public $enabledSocialwallTypes = [
        Social::LIVE_WALL => "Social wall",
        Social::PICKED_POSTS_WALL => "Contenuti social correlati",
    ];

    public static $CONFIG_FOLDER = 'config';

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'socialwall';
    }

    public function getWidgetIcons()
    {
        return [];
    }

    public function getWidgetGraphics()
    {
        return [];
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [];
//        return [
//            'AgidOrganizationalUnit' => __NAMESPACE__.'\\'.'models\AgidOrganizationalUnit',
//            'AgidOrganizationalUnitSearch' => __NAMESPACE__.'\\'.'models\search\AgidOrganizationalUnitSearch',
//        ];
    }

    public static function getModelClassName() {
        return null;
//        return Module::instance()->model('AgidOrganizationalUnit');
    }

    public static function getModelSearchClassName() {
        return null;
//        return Module::instance()->model('AgidOrganizationalUnitSearch');
    }

    public static function getModuleIconName() {
        return null;
    }
    
    
    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu();
        $app  = \Yii::$app;
//        if (!$app->user->isGuest && (\Yii::$app->user->can('ADMIN')||\Yii::$app->user->can('AGID_ORGANIZATIONAL_UNIT_ADMIN')||\Yii::$app->user->can('REDACTOR_ORGANIZATIONALUNIT'))) {
        if (!$app->user->isGuest && (\Yii::$app->user->can('ADMIN'))) {
            $menu .= $this->addFrontEndMenu(Module::t('socialwall','Asset Social'), Module::toUrlModule('/socialwall/index'),$dept);
        }
        return $menu;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
        $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
        \Yii::configure($this, ArrayHelper::merge($config, $this));
    }

}
