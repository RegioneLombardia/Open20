<?php

/**
 * @package   yii2-grid
 * @version   3.2.9
 */

namespace kartik\grid;

use kartik\base\AssetBundle;

/**
 * Asset bundle for row grouping functionality of [[GridView]] widget.
 *
 * @since 1.0
 */
class GridGroupAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/kv-grid-group']);
        parent::init();
    }
}
