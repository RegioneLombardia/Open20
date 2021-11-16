<?php
/**
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadUIAsset
 *
 */
class BlueimpTmplAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-tmpl';
    public $js = [
        'js/tmpl.min.js',
    ];
}
