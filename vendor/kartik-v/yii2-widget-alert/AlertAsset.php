<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-alert
 * @version 1.1.1
 */

namespace kartik\alert;

use kartik\base\AssetBundle;

/**
 * Asset bundle for the [[Alert]] widget.
 *
 * @since 1.0
 */
class AlertAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/alert']);
        parent::init();
    }
}
