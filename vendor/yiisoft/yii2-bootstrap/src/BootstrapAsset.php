<?php
/**
 */

namespace yii\bootstrap;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Twitter bootstrap css files.
 *
 * @since 2.0
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $css = [
        'css/bootstrap.css',
    ];
}
