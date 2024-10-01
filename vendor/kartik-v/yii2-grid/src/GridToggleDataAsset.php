<?php

/**
 * @package   yii2-grid
 * @version   3.5.3
 */

namespace kartik\grid;

use kartik\base\AssetBundle;

/**
 * Asset bundle used for toggling data (from page mode to all records) for the [[GridView]] widget.
 *
 * @since 1.0
 */
class GridToggleDataAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge(["kartik\\grid\\GridViewAsset"], $this->depends);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-grid-toggle']);
        parent::init();
    }
}
