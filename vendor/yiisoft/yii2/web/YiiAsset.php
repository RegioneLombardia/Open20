<?php
/**
 */

namespace yii\web;

/**
 * This asset bundle provides the base JavaScript files for the Yii Framework.
 *
 * @since 2.0
 */
class YiiAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    public $js = [
        'yii.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
