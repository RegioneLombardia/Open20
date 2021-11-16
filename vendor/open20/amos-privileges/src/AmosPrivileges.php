<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-privileges
 * @category   CategoryName
 */

namespace open20\amos\privileges;

use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use Yii;

/**
 * Class AmosReport
 * @package open20\amos\privileges
 */
class AmosPrivileges extends AmosModule implements ModuleInterface
{
    /**
     * @var array roles that must not be considered by Privileges module
     */
    public $blackListRoles = ['AMMINISTRATORE_CRTT'];
    /**
     * @var array if set, consider only the roles in this list
     */
    public $whiteListRoles = [];
    /**
     * @var array array of modules for which privileges are not considered
     */
    public $blackListModules = ['inforeq', 'proposte_collaborazione', 'proposte_collaborazione_een'];
    /**
     * @var array list of platform roles (not from a single plugin) - override this if necessary
     */
    public $platformRoles = ['ADMIN', 'BASIC_USER'];

    public static $CONFIG_FOLDER = 'config';

    public $enableAgid = false;
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    public $name = 'Privileges';

    public static function getModuleName()
    {
        return "privileges";
    }

    public function init()
    {
        parent::init();

        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');
        // initialize the module with the configuration loaded from config.php
        Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php'));
    }

    public function getWidgetIcons()
    {
        return [

        ];
    }

    public function getWidgetGraphics()
    {
        return [

        ];
    }

    protected function getDefaultModels()
    {
        // TODO: Implement getDefaultModels() method.
    }
}