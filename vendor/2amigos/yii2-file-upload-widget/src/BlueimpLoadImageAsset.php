<?php
/**
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadPlusAsset
 *
 */
class BlueimpLoadImageAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-load-image';
    public $js = [
        'js/load-image.all.min.js',
    ];
}
