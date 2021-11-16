<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\layout
 * @category   CategoryName
 */

namespace open20\amos\layout\assets;

use open20\amos\core\widget\WidgetAbstract;
use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package open20\amos\layout\assets
 */
class BiLessAsset extends AssetBundle
{
    public $js = [
        'js/header.js',
        'js/footer.js',
        'js/hamburger-menu.js',
    ];

    public $css = [
        'less/main-bi.less'
    ];

    public $depends = [
        'open20\amos\layout\assets\CookieBarAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources/base';
        if( !(isset(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader'])) || (isset(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']) && !(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']))){
            $this->js[] = 'js/header-height.js';
        }
        parent::init();
    }
}
