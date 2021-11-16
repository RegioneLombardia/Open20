<?php
/**
 */
namespace dosamigos\tinymce;

use yii\web\AssetBundle;

class TinyMceLangAsset extends AssetBundle
{
    public $sourcePath = '@vendor/2amigos/yii2-tinymce-widget/src/assets';

    public $depends = [
        'dosamigos\tinymce\TinyMceAsset'
    ];
}
