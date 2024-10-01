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
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@yii/debug/assets';
    /**
     * {@inheritdoc}
     */
    public $css = [
        'css/main.css',
        'css/toolbar.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/polyfill.min.js',
        'js/bs4-native.min.js'
    ];
}
