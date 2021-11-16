<?php
/**
 */

namespace dosamigos\gallery;

use yii\web\AssetBundle;

/**
 * DosamigosAsset
 *
 */
class DosamigosAsset extends AssetBundle
{
    public $js = [
        'dosamigos-blueimp-gallery.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}
