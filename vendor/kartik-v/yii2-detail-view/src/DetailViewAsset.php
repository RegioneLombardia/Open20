<?php

/**
 * @package   yii2-detail-view
 * @version   1.8.3
 */

namespace kartik\detail;

use kartik\base\AssetBundle;

/**
 * Asset bundle for DetailView Widget
 *
 * @since 1.0
 */
class DetailViewAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-detail-view']);
        $this->setupAssets('css', ['css/kv-detail-view']);
        parent::init();
    }
}
