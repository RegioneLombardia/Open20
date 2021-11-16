<?php
/**
 */

namespace yii\bootstrap;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap javascript files.
 *
 * @since 2.0
 */
class BootstrapPluginAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $js = [
        'js/bootstrap.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
