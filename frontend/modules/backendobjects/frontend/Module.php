<?php

namespace app\modules\backendobjects\frontend;

use app\modules\backendobjects\frontend\blocks\ModuleBackendBlock;
use luya\cms\models\Block;
use luya\cms\models\NavItemPageBlockItem;
use yii\helpers\Url;

/**
 * Backendobjects Admin Module.
 *
 * File has been created with `module/create` command. 
 * 
 * @author
 * @since 1.0.0
 */
class Module extends \luya\base\Module
{

    /**
     * @var bool If enabled the cms content will be compressed (removing of whitespaces and tabs).
     */
    public $contentCompression= true;
    /**
     * @var boolean Whether the overlay toolbar of the CMS should be enabled or disabled.
     */
    public $overlayToolbar = true;

    /**
     * @var bool If enableTagParsing is enabled tags like `link(1)` or `link(1)[Foo Bar]` will be parsed
     * and transformed into links based on the cms.
     */
    public $enableTagParsing = true;
    
    public $modulesEnabled;
    public $urlRules = [
        ['pattern' => 'c/<blockItemId:\d+>/<id:[a-z0-9\-]+>/<relatedDitailPage:[a-z0-9\-]+>', 'route' => 'backendobjects/default/rest-detail'],
	    ['pattern' => 'c/<blockItemId:\d+>/<id:[a-z0-9\-]+>', 'route' => 'backendobjects/default/rest-detail'],
        ['pattern' => 'b/<blockItemId:\d+>/<slug:[a-z0-9\-]+>/<relatedDitailPage:[a-z0-9\-]+>', 'route' => 'backendobjects/default/detail'],
        ['pattern' => 'b/<blockItemId:\d+>/<slug:[a-z0-9\-]+>', 'route' => 'backendobjects/default/detail'],
        ['pattern' => '<amp>/b/<blockItemId:\d+>/<slug:[a-z0-9\-]+>', 'route' => 'backendobjects/default/detail'],
    ];

    
    public function init() {
        parent::init();
        \Yii::$app->setComponents(array(
                    'user'=>array(
                      'class' => 'open20\amos\core\user\AmosUser',
                      'identityClass' => 'open20\amos\core\user\User',
                      'enableAutoLogin' => true,
                    ),
                  ), true);
    }
    /**
     * @inheritdoc
     */
    public static function onLoad() {
        self::registerTranslation('backendobjects', static::staticBasePath() . '/messages', [
            'backendobjects' => 'backendobjects.php',
        ]);
    }

    /**
     * Translations for CMS frontend Module.
     *
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, array $params = []) {
        return parent::baseT('backendobjects', $message, $params);
    }

    public function getPublishedModelClasses() {
        $modelClasses = [];
        $block = Block::findOne(['class' => '\\' . ModuleBackendBlock::className()]);
        if (!$block) {
            return null;
        }
        $navItemPageBlockItems = NavItemPageBlockItem::find(['block_id' => $block->id]);
        foreach ($navItemPageBlockItems->each() as $navItemPageBlockItem) {
            $b = $navItemPageBlockItem->block->classObject;
            if ($b && $navItemPageBlockItem->json_config_values) {
                $b->setVarValues(json_decode($navItemPageBlockItem->json_config_values, true));
                if ($b->getVarValue('backendModule')) {
                    $modelSearchClass = $b->getVarValue('backendModule')::getModelSearchClassName();
                    $modelClasses[$navItemPageBlockItem->id] = $modelSearchClass;
                }
            }
        }

        return $modelClasses;
    }

    public static function getSeoUrl($slug, $blockItemId, $sitemap = false, $relatedDitailPage = false) {
        $navItemPageBlockItem = NavItemPageBlockItem::findOne(['id' => $blockItemId]);
        if (!$navItemPageBlockItem) {
            return null;
        }
        $lang = $navItemPageBlockItem->navItemPage->navItem->lang->short_code;
        if (!$lang) {
            return null;
        }
        $url = Url::to(Url::base(true) . '/' . $lang . '/b/' . $blockItemId . '/' . $slug .( $relatedDitailPage ? '/' . $relatedDitailPage : '' ) );
        
        if($sitemap){
            return [$lang => $url];
        }else{
            return $url;
        }
    }

    /**
     *
     * @param type $id
     * @param type $blockItemId
     * @param type $sitemap
     * @param type $relatedDitailPage
     * @return type
     */
    public static function getBaseUrl($id, $blockItemId, $sitemap = false, $relatedDitailPage = false) {
        $navItemPageBlockItem = NavItemPageBlockItem::findOne(['id' => $blockItemId]);
        if (!$navItemPageBlockItem) {
            return null;
        }
        $lang = $navItemPageBlockItem->navItemPage->navItem->lang->short_code;
        if (!$lang) {
            return null;
        }
        $url = Url::to(Url::base(true) . '/' . $lang . '/c/' . $blockItemId . '/' . $id .( $relatedDitailPage ? '/' . $relatedDitailPage : '' ) );

        if($sitemap){
            return [$lang => $url];
        }else{
            return $url;
        }
    }
}
