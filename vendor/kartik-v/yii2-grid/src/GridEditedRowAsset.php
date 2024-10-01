<?php

/**
 * @package   yii2-grid
 * @version   3.5.3
 */

namespace kartik\grid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for edited row focus functionality of the [[GridView]] widget.
 *
 * @since 1.0
 */
class GridEditedRowAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge(["kartik\\grid\\GridViewAsset"], $this->depends);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-grid-edited-row']);
        $this->setupAssets('css', ['css/kv-grid-edited-row']);
        parent::init();
    }
}
