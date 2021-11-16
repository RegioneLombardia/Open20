<?php
/**
 */
namespace dosamigos\ckeditor;

use yii\web\AssetBundle;

/**
 * CKEditorAsset
 *
 * @package dosamigos\ckeditor
 */
class CKEditorAsset extends AssetBundle
{
    public $js = [
        'ckeditor.js',
        'adapters/jquery.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets/ckeditor';
        parent::init();
    }
}
