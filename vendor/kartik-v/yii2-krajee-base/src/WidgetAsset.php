<?php

/**
 * @package   yii2-krajee-base
 * @version   3.0.5
 */

namespace kartik\base;

/**
 * Common base widget asset bundle for all Krajee widgets
 *
 */
class WidgetAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/kv-widgets']);
        $this->setupAssets('js', ['js/kv-widgets']);
        parent::init();
    }
}
