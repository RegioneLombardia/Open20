<?php
/**
 */

namespace yii\bootstrap4;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap javascript files.
 *
 */
class BootstrapPluginAsset extends AssetBundle
{
    public $sourcePath = '@npm/bootstrap/dist';
    public $js = [
        'js/bootstrap.bundle.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
