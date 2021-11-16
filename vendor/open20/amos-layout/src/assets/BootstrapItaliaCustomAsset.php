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

use yii\web\AssetBundle;

/**
 * Class BootstrapItaliaCustomAsset
 * @package open20\amos\layout\assets
 */

class BootstrapItaliaCustomAsset extends AssetBundle
{
    public $sourcePath = '@vendor/open20/amos-layout/src/assets/resources/bootstrap-italia-custom';

    public $js = [
        'js/bootstrap-italia-custom.js',
        'node_modules/svgxuse/svgxuse.min.js', //per far vedere le icone in IE
        'https://use.fontawesome.com/releases/v5.3.1/js/all.js', // fix temporaneo per icone kartik font-awesome
    ];

    public $css = [
      'scss/main-amos-layout.scss',
      'https://use.fontawesome.com/releases/v5.3.1/css/all.css', // fix temporaneo per icone kartik font-awesome
    ];

    public $depends = [
        'open20\amos\layout\assets\FontAsset', //fix temp font
        'open20\amos\layout\assets\IconAsset', // Retrocompatibilità icone
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
