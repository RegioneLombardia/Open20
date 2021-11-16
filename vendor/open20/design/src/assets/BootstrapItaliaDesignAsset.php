<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\design\layout
 * @category   CategoryName
 */

namespace open20\design\assets;

use yii\web\AssetBundle;

/**
 * Class BootstrapItaliaDesignAsset
 * @package open20\design\assets
 */

class BootstrapItaliaDesignAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/design/src/assets/resources/bootstrapitaliacustom';

    public $js = [
        'node_modules/bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js', //mantenere come prima riga
        'js/bootstrap-italia-custom.js',
        'js/owl-carousel-design.js',
        'js/footer-height.js',
        'js/hamburger-menu.js',
        'node_modules/svgxuse/svgxuse.min.js', //per far vedere le icone in IE
    ];

    public $css = [
      'MaterialDesign-Webfont-master/css/materialdesignicons.min.css',
      'https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css',
      'scss/main-design.scss'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if( !(isset(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader'])) || (isset(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']) && !(\Yii::$app->params['layoutConfigurations']['enableHeaderStickyHeader']))){
            $this->js[] = 'js/header-height.js';
        }
        parent::init();
    }
}
