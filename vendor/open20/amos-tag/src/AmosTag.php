<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag;

use open20\amos\core\module\AmosModule;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\record\Record;
use open20\amos\tag\widgets\icons\WidgetIconTag;
use open20\amos\tag\widgets\icons\WidgetIconTagManager;
use Yii;

/**
 * Class AmosTag
 * @package open20\amos\tag
 */
class AmosTag extends AmosModule implements CmsModuleInterface, SearchModuleInterface {

    /**
     * 
     * @var type
     */
    public $controllerNamespace = 'open20\amos\tag\controllers';

    /**
     * @var string
     */
    public $postKey = 'Tag';

    /**
     * @var array
     */
    public $modelsEnabled = [];

    /**
     * 
     * @var type
     */
    public $behaviors = [
        'open20\amos\core\behaviors\TaggableBehavior'
    ];

    /**
     * 
     * @var type
     */
    public $name = 'Tag';

    /**
     * 
     * @var type
     */
    public $selectSonsOnly = true;
    
    /**
     * E.g.: ['open20\amos\news\models\News' => 1]
     * @var type
     */
    public $modelsTagFree = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        Record::$modulesChainBehavior[] = 'tag';
        Yii::setAlias(
                '@open20/amos/'
                . static::getModuleName()
                . '/controllers',
                __DIR__
                . '/controllers'
        );

        //aggiunge le configurazioni trovate nel file config/config.php
        Yii::configure(
                $this,
                require(
                __DIR__
                . DIRECTORY_SEPARATOR
                . 'config'
                . DIRECTORY_SEPARATOR
                . 'config.php'
                )
        );
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
     * @inheritdoc
     */
    public static function getModuleName() {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function getWidgetGraphics() {
        
    }

    /**
     * @inheritdoc
     */
    public function getWidgetIcons() {
        return [
            WidgetIconTagManager::class,
            WidgetIconTag::class
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

    /**
     * @inheritdoc
     */
    protected function getDefaultModels() {
        return [
            'EntitysTagsMm' => __NAMESPACE__ . '\models\EntitysTagsMm',
            'Tag' => __NAMESPACE__ . '\models\Tag',
            'TagModelsAuthItemsMm' => __NAMESPACE__ . '\models\TagModelsAuthItemsMm',
            'TagSearch' => __NAMESPACE__ . '\models\search\TagSearch',
        ];
    }

    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1) {
        $menu = "";
        $app = \Yii::$app;
        if (!$app->user->isGuest) {
            if (Yii::$app->user->can('AMMINISTRATORE_TAG')) {
                $menu .= $this->addFrontEndMenu(
                        AmosTag::t('amostag', '#menu_front_tags'),
                        AmosTag::toUrlModule('/manager'), $dept);
            }
        }

        return $menu;
    }

    /**
     * 
     * @return type
     */
    public static function getModelClassName() {
        return AmosTag::instance()->model('Tag');
    }

    /**
     * 
     * @return type
     */
    public static function getModelSearchClassName() {
        return AmosTag::instance()->model('TagSearch');
    }

    /**
     * 
     * @return type
     */
    public static function getModuleIconName() {
        return null;
    }

}
