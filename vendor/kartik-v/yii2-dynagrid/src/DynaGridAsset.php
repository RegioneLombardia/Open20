<?php

/**
 * @package   yii2-dynagrid
 * @version   1.5.1
 */

namespace kartik\dynagrid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for DynaGrid Widget
 *
 * @since 1.0
 */
class DynaGridAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-dynagrid']);
        $this->setupAssets('css', ['css/kv-dynagrid']);
        parent::init();
    }

}