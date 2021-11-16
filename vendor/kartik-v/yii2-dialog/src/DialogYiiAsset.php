<?php

/**
 * @package   yii2-dialog
 * @version   1.0.4
 */

namespace kartik\dialog;

use yii\web\View;
use kartik\base\AssetBundle;

/**
 * Asset bundle that overrides Yii's default confirm dialog
 *
 * @since 1.0
 */
class DialogYiiAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $depends = [
        'kartik\dialog\DialogAsset',
        'yii\web\YiiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/dialog-yii']);
        parent::init();
    }
}
