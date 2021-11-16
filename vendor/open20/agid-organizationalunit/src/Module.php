<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\organizational_unit
 * @category   CategoryName
 */

namespace open20\agid\organizationalunit;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\privileges\interfaces\CategoriesRolesInterface;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitContentTypeRoles;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use yii\helpers\ArrayHelper;


/**
 * Class Module
 * @package open20\amos\organizzazioni
 */
class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, CategoriesRolesInterface
{

    public static $CONFIG_FOLDER = 'config';

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'organizationalunit';
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
        return [
            'AgidOrganizationalUnit' => __NAMESPACE__.'\\'.'models\AgidOrganizationalUnit',
            'AgidOrganizationalUnitSearch' => __NAMESPACE__.'\\'.'models\search\AgidOrganizationalUnitSearch',
        ];
    }

    public static function getModelClassName() {
        return Module::instance()->model('AgidOrganizationalUnit');
    }

    public static function getModelSearchClassName() {
        return Module::instance()->model('AgidOrganizationalUnitSearch');
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
        if (!$app->user->isGuest && (\Yii::$app->user->can('ADMIN')||\Yii::$app->user->can('AGID_ORGANIZATIONAL_UNIT_ADMIN')||\Yii::$app->user->can('REDACTOR_ORGANIZATIONALUNIT'))) {
            $menu .= $this->addFrontEndMenu(Module::t('amosorganizationalunit','#menu_front_organizationalunit'), Module::toUrlModule('/agid-organizational-unit/index'),$dept);
        }
        return $menu;
    }

    public static function getCategoryArrayRole(){

        return  ArrayHelper::map(AgidOrganizationalUnitContentType::find()->orderBy('name')->all(), 'id', 'name');
    }
    public static function getCategoryArrayRoleAssignedToUser($userId){
        $ids = AgidOrganizationalUnitContentTypeRoles::find()->select('agid_organizational_unit_content_type_id')->andWhere(['user_id' =>$userId])->distinct()->column();
        return  ArrayHelper::map(AgidOrganizationalUnitContentType::find()->orderBy('name')->andWhere(['id' => $ids,])->all(), 'id', 'name');

    }

    public static function getModelCategoryRole(){
        return AgidOrganizationalUnitContentTypeRoles::classname();
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
