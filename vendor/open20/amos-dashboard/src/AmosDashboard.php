<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard;

use open20\amos\core\module\AmosModule;
use yii\base\BootstrapInterface;

class AmosDashboard extends AmosModule implements BootstrapInterface {

  public static 
    $CONFIG_FOLDER = 'config';
  
  public 
    $controllerNamespace = 'open20\amos\dashboard\controllers',
    $controllerConsoleNamespace = 'open20\amos\dashboard\commands'
  ;

  /**
   * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
   * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
   * will be taken. If this is false, layout will be disabled within this module.
   */
  public 
    $layout = 'main',
    $name = 'Dashboard'
  ;

  //public $initWidgets = true;

  /**
   * If true the widgets will be refreshed if
   * the AmosWidgets.created_at > AmosUserDashboars.updated_at
   * @var boolean
   */
  public
    $refreshWidgets = true,
    $initIfEmpty = true,
    $initAllWidgets = false,
    $initHierarchyWidgets = true,
    $initChildWidget = false,
    
    $modulesSubdashboard,                           // Array of the modules that have the sub-dashboard
    $useWidgetGraphicDashboardVisible = true,       // If true, only widgets that have dashboard_visible set to 1 will be shown
    $useWidgetGraphicOrder = false
  ;

  /**
   * 
   * @param \yii\console\Application $app
   */
  public function bootstrap($app) {
    if ($app instanceof \yii\console\Application) {
      $this->controllerNamespace = $this->controllerConsoleNamespace;
    }
  }

  /**
   * 
   */
  public function init() {
    parent::init();
    
    \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers/');
    // initialize the module with the configuration loaded from config.php
    //  \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php'));
  }

  /**
   * @return string
   */
  public static function getModuleName() {
    return "dashboard";
  }

  /**
   * 
   */
  public function getWidgetIcons() {}

  /**
   * 
   */
  public function getWidgetGraphics() {}

  /**
   * 
   */
  protected function getDefaultModels() {
    // TODO: Implement getDefaultModels() method.
  }

  /**
   * 
   * @param type $widgets
   */
  public function setModuleSubDashboard($widgets) {
    if (is_array($widgets)) {
      ;
    } else if (is_string($widgets)) {
      $widgets = [$widgets];
    }
  }

}