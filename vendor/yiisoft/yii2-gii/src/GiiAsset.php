<?php
/**
 */

namespace yii\gii;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @since 2.0
 */
class GiiAsset extends AssetBundle
{
    public $sourcePath = '@yii/gii/assets';
    public $css = [
        'main.css',
    ];
    public $js = [
        'gii.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\gii\TypeAheadAsset',
    ];
}
