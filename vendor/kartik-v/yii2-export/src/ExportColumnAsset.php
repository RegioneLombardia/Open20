<?php

/**
 * @package yii2-export
 * @version 1.3.9
 */

namespace kartik\export;

use kartik\base\AssetBundle;

/**
 * Asset bundle for ExportMenu Widget (for export columns selector)
 *
 * @since 1.0
 */
class ExportColumnAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-export-columns']);
        $this->setupAssets('css', ['css/kv-export-columns']);
        parent::init();
    }
}