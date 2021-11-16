<?php
/**
 */

namespace dosamigos\fileupload;

use yii\web\AssetBundle;

/**
 * FileUploadPlusAsset
 *
 */
class FileUploadPlusAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $css = [
        'css/jquery.fileupload.css'
    ];
    public $js = [
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload-process.js',
        'js/jquery.fileupload-image.js',
        'js/jquery.fileupload-audio.js',
        'js/jquery.fileupload-video.js',
        'js/jquery.fileupload-validate.js'
    ];
    public $depends = [
        'dosamigos\fileupload\FileUploadAsset',
        'dosamigos\fileupload\BlueimpLoadImageAsset',
        'dosamigos\fileupload\BlueimpCanvasToBlobAsset',
    ];
    public $publishOptions = [
        'except' => [
            'server/*',
            'test'
        ],
    ];
}
