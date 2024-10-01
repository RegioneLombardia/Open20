<?php

/**
 * @package yii2-krajee-base
 * @version 3.0.5
 */

namespace kartik\base;

/**
 * Asset bundle for the [[Html5Input]] widget.
 *
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
