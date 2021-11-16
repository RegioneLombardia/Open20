<?php
/**
 */

namespace yii\debug;

use yii\web\AssetBundle;

/**
 * Timeline asset bundle
 *
 * @since 2.0.7
 */
class TimelineAsset extends AssetBundle
{
    public $sourcePath = '@yii/debug/assets';
    public $css = [
        'timeline.css',
    ];
    public $js = [
        'timeline.js',
    ];
}