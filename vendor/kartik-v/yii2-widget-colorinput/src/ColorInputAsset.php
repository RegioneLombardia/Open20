<?php

/**
 * @package yii2-widgets
 * @subpackage yii2-widget-colorinput
 * @version 1.0.6
 */

namespace kartik\color;

/**
 * Asset bundle for ColorInput Widget
 *
 * @since 1.0
 */
class ColorInputAsset extends \kartik\base\AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/spectrum', 'css/spectrum-kv']);
        $this->setupAssets('js', ['js/spectrum', 'js/spectrum-kv']);
        parent::init();
    }
}
