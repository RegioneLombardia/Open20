<?php
/**
 */

namespace yii\widgets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files required by [[Pjax]] widget.
 *
 * @since 2.0
 */
class PjaxAsset extends AssetBundle
{
    public $sourcePath = '@bower/yii2-pjax';
    public $js = [
        'jquery.pjax.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
