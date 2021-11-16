<?php
/**
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadUIAsset
 *
 */
class FileUploadUIAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $css = [
        'css/jquery.fileupload.css'
    ];
    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/jquery.fileupload-process.js',
        'js/jquery.fileupload-image.js',
        'js/jquery.fileupload-audio.js',
        'js/jquery.fileupload-video.js',
        'js/jquery.fileupload-validate.js',
        'js/jquery.fileupload-ui.js',

    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'dosamigos\fileupload\BlueimpLoadImageAsset',
        'dosamigos\fileupload\BlueimpCanvasToBlobAsset',
        'dosamigos\fileupload\BlueimpTmplAsset'
    ];
    public $publishOptions = [
        'except' => [
            'server/*',
            'test'
        ],
    ];
}
