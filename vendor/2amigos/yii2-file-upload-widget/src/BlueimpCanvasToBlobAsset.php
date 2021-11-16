<?php
/**
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadPlusAsset
 *
 */
class BlueimpCanvasToBlobAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-canvas-to-blob';
    public $js = [
        'js/canvas-to-blob.min.js',
    ];
}
