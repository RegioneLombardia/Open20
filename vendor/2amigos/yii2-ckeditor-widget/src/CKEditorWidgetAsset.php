<?php
/**
 */
namespace dosamigos\ckeditor;

use yii\web\AssetBundle;

/**
 * CKEditorWidgetAsset
 *
 * @package dosamigos\ckeditor
 */
class CKEditorWidgetAsset extends AssetBundle
{
    public $depends = [
        'dosamigos\ckeditor\CKEditorAsset'
    ];
    public $js = [
        'dosamigos-ckeditor.widget.js'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}
