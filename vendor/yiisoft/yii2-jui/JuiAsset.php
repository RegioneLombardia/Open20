<?php
/**
 */

namespace yii\jui;

use yii\web\AssetBundle;

/**
 * @since 2.0
 */
class JuiAsset extends AssetBundle
{
    public $sourcePath = '@bower/jquery-ui';
    public $js = [
        'jquery-ui.js',
    ];
    public $css = [
        'themes/smoothness/jquery-ui.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
