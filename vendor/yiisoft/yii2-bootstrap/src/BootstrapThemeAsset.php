<?php
/**
 */

namespace yii\bootstrap;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap default theme.
 *
 * @since 2.0
 */
class BootstrapThemeAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $css = [
        'css/bootstrap-theme.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
