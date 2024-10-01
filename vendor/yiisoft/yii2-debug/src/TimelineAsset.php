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
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@yii/debug/assets';
    /**
     * {@inheritdoc}
     */
    public $css = [
        'css/timeline.css',
    ];
    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/timeline.js',
    ];
    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\debug\DebugAsset'
    ];
}
