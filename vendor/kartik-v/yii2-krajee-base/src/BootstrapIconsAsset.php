<?php

/**
 * @package   yii2-krajee-base
 * @version   3.0.5
 */

namespace kartik\base;

use yii\web\AssetBundle as YiiAssetBundle;

/**
 * Asset bundle for bootstrap 5 icons
 *
 */
class BootstrapIconsAsset extends YiiAssetBundle
{
    /**
     * @inheritDoc
     */
    public $css = ['https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css'];
}
