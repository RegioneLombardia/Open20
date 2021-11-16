<?php

/**
 * @package   yii2-krajee-base
 * @version   1.9.9
 */

namespace kartik\base;

/**
 * Asset bundle for loading animations.
 *
 * @since 1.0
 */
class AnimateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/animate']);
        parent::init();
    }
}
