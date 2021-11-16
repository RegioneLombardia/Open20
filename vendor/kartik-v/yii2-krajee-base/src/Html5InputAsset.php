<?php

/**
 * @package yii2-krajee-base
 * @version 1.9.9
 */

namespace kartik\base;

/**
 * Asset bundle for the [[Html5Input]] widget.
 *
 * @since 1.0
 */
class Html5InputAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/html5input']);
        parent::init();
    }
}
