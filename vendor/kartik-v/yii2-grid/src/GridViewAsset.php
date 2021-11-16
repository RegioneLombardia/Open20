<?php

/**
 * @package   yii2-grid
 * @version   3.2.9
 */

namespace kartik\grid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for the styling of the [[GridView]] widget.
 *
 * @since 1.0
 */
class GridViewAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge(["kartik\\dialog\\DialogAsset"], $this->depends);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('css', ['css/kv-grid']);
        parent::init();
    }
}
