<?php
/**
 */

namespace yii\debug;

use yii\web\AssetBundle;

/**
 * Debugger asset bundle
 *
 * @since 2.0
 */
class DebugAsset extends AssetBundle
{
    public $sourcePath = '@yii/debug/assets';
    public $css = [
        'main.css',
        'toolbar.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
