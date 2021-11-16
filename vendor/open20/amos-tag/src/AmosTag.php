<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag;

use open20\amos\core\module\AmosModule;
use open20\amos\core\record\Record;
use open20\amos\tag\widgets\icons\WidgetIconTag;
use open20\amos\tag\widgets\icons\WidgetIconTagManager;
use Yii;

class AmosTag extends AmosModule {

  public $controllerNamespace = 'open20\amos\tag\controllers';

  /**
   * @var string
   */
  public $postKey = 'Tag';

  /**
   * @var array
   */
  public $modelsEnabled = [
  ];
  public $behaviors = [
    'open20\amos\core\behaviors\TaggableBehavior'
  ];
  public $name = 'Tag';

 public $selectSonsOnly = true;
  
  /**
   * 
   */
  public function init() {
    parent::init();
    
    Record::$modulesChainBehavior[] = 'tag';
    Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers', __DIR__ . '/controllers');
    
    //aggiunge le configurazioni trovate nel file config/config.php
    Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
  }

  /**
   * 
   */
  public function bootstrap() {
    $treeManagerModule = Yii::$app->getModule('treemanager');
    if (!$treeManagerModule) {
      Yii::$app->setModule('treemanager', $this->getModule('treemanager'));
    }
  }

  /**
   * 
   * @return string
   */
  public static function getModuleName() {
    return 'tag';
  }

  /**
   * 
   */
  public function getWidgetGraphics() {
    
  }

  /**
   * 
   * @return type
   */
  public function getWidgetIcons() {
    return [
      WidgetIconTagManager::className(),
      WidgetIconTag::className()
    ];
  }

  /**
   * Chiave che verrà spedita in post
   *
   * @return string
   */
  public function getPostKey() {
    return $this->postKey;
  }

  /**
   * @param string $postKey
   */
  public function setPostKey($postKey) {
    $this->postKey = $postKey;
  }

  protected function getDefaultModels() {
    return [
      'Tag' => __NAMESPACE__ . '\\' . 'models\\Tag',
    ];
  }

}