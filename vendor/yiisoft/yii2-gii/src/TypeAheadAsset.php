<?php
/**
 */

namespace yii\gii;

use yii\web\AssetBundle;

/**
 * Declares the asset files for jQuery 'typeahead' plugin.
 *
 *
 * @since 2.0
 */
class TypeAheadAsset extends AssetBundle
{
    public $sourcePath = '@bower/typeahead.js/dist';
    public $js = [
        'typeahead.bundle.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
