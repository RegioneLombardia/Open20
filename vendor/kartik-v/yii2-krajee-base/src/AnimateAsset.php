<?php

/**
 * @package   yii2-krajee-base
 * @version   3.0.5
 */

namespace kartik\base;

/**
 * Asset bundle for loading animations.
 *
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
