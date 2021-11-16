<?php
/**
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadAsset
 *
 */
class FileUploadAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $css = [
        'css/jquery.fileupload.css'
    ];
    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'except' => [
            'server/*',
            'test'
        ],
    ];
}
