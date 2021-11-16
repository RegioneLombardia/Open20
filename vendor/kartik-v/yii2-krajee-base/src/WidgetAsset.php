<?php

/**
 * @package   yii2-krajee-base
 * @version   1.9.9
 */

namespace kartik\base;

/**
 * Common base widget asset bundle for all Krajee widgets
 *
 * @since 1.0
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
