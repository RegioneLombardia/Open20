<?php
/**
 */

namespace dosamigos\gallery;

use yii\web\AssetBundle;

/**
 * GalleryAsset
 *
 */
class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-gallery';
    public $css = [
        'css/blueimp-gallery.min.css',
    ];
    public $js = [
        'js/blueimp-gallery.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
